<?php
include('koneksi.php');

if ($_GET['proses'] == 'insert') {
    if (isset($_POST['proses'])) {
        try {
            $stmt = $db->prepare("INSERT INTO dosen(nip, nama_dosen, email, prodi_id, notelp, alamat) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $_POST['nip'],
                $_POST['nama_dosen'],
                $_POST['email'],
                $_POST['prodi_id'],
                $_POST['notelp'],
                $_POST['alamat']
            ]);
            echo "<script>window.location='dosen.php?p=list'</script>";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}

if ($_GET['proses'] == 'update') {
    if (isset($_POST['proses'])) {
        try {
            $stmt = $db->prepare("UPDATE dosen SET nip = ?, nama_dosen = ?, email = ?, prodi_id = ?, notelp = ?, alamat = ? WHERE id = ?");
            $stmt->execute([
                $_POST['nip'],
                $_POST['nama_dosen'],
                $_POST['email'],
                $_POST['prodi_id'],
                $_POST['notelp'],
                $_POST['alamat'],
                $_POST['id']
            ]);
            echo "<script>window.location='dosen.php?p=list'</script>";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}

if ($_GET['proses'] == 'delete') {
    if (isset($_GET['id'])) {
        try {
            $stmt = $db->prepare("DELETE FROM dosen WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            echo "<script>window.location='dosen.php?p=list'</script>";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>
