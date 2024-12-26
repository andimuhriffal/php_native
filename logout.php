<?php
session_start();

// Menghancurkan semua sesi
session_destroy();

// Mengarahkan pengguna ke halaman login
header('location:login.php');
exit();
?>
