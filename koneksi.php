<?php
$server = "localhost";
$user = "root";
$password = "";
$nama_database = "db_pdo";

try {
    // Membuat koneksi ke database menggunakan PDO
    $db = new PDO("mysql:host=$server;dbname=$nama_database;charset=utf8", $user, $password);

    // Mengatur mode error agar PDO melempar exception jika terjadi kesalahan
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Mengatur mode fetch sebagai default (optional, FETCH_ASSOC adalah mode umum)
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Pesan opsional jika koneksi berhasil (untuk debugging, bisa dihapus saat produksi)
    // echo "Koneksi berhasil";
} catch (PDOException $e) {
    // Menangkap kesalahan dan menampilkan pesan error
    die("Gagal terhubung dengan database: " . $e->getMessage());
}
?>
