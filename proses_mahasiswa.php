<?php
include 'koneksi.php'; // Pastikan koneksi.php menggunakan PDO

if ($_GET['proses'] === 'insert') {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Pastikan hobi diolah dengan benar
        $hobi = isset($_POST['hobi']) ? (is_array($_POST['hobi']) ? implode(",", $_POST['hobi']) : $_POST['hobi']) : null;

        try {
            $stmt = $db->prepare("INSERT INTO mahasiswa (nim, nama_mhs, tgl_lahir, jenis_kelamin, email, prodi_id, nohp, hobi, alamat) 
                VALUES (:nim, :nama_mhs, :tgl_lahir, :jenis_kelamin, :email, :prodi_id, :nohp, :hobi, :alamat)");
            
            $stmt->bindParam(':nim', $_POST['nim']);
            $stmt->bindParam(':nama_mhs', $_POST['nama_mhs']);
            $stmt->bindParam(':tgl_lahir', $_POST['tgl_lahir']);
            $stmt->bindParam(':jenis_kelamin', $_POST['jenis_kelamin']);
            $stmt->bindParam(':email', $_POST['email']);
            $stmt->bindParam(':prodi_id', $_POST['prodi_id']);
            $stmt->bindParam(':nohp', $_POST['nohp']);
            $stmt->bindParam(':hobi', $hobi);
            $stmt->bindParam(':alamat', $_POST['alamat']);

            if ($stmt->execute()) {
                echo "<script>alert('Data berhasil disimpan'); window.location='index.php?p=mhs';</script>";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}

if ($_GET['proses'] === 'update') {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Pastikan hobi diolah dengan benar
        $hobi = isset($_POST['hobi']) ? (is_array($_POST['hobi']) ? implode(",", $_POST['hobi']) : $_POST['hobi']) : null;

        try {
            $stmt = $db->prepare("UPDATE mahasiswa SET 
                nama_mhs = :nama_mhs, 
                tgl_lahir = :tgl_lahir, 
                jenis_kelamin = :jenis_kelamin, 
                email = :email, 
                prodi_id = :prodi_id, 
                nohp = :nohp, 
                hobi = :hobi, 
                alamat = :alamat 
                WHERE nim = :nim");
            
            $stmt->bindParam(':nim', $_POST['nim']);
            $stmt->bindParam(':nama_mhs', $_POST['nama_mhs']);
            $stmt->bindParam(':tgl_lahir', $_POST['tgl_lahir']);
            $stmt->bindParam(':jenis_kelamin', $_POST['jenis_kelamin']);
            $stmt->bindParam(':email', $_POST['email']);
            $stmt->bindParam(':prodi_id', $_POST['prodi_id']);
            $stmt->bindParam(':nohp', $_POST['nohp']);
            $stmt->bindParam(':hobi', $hobi);
            $stmt->bindParam(':alamat', $_POST['alamat']);

            if ($stmt->execute()) {
                echo "<script>alert('Data berhasil diupdate'); window.location='index.php?p=mhs';</script>";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
