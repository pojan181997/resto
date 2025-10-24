<?php
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: login.php"); // Redirect ke halaman login jika belum login
    exit();
}

// Periksa apakah pengguna memiliki role yang sesuai
if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'pimpinan') {
    header("Location: home.php"); // Redirect ke halaman utama jika bukan admin atau pimpinan
    exit();
}
?>
