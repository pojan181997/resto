<?php
session_start();
include 'config.php';
include 'crud_functions.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Ambil kode dari URL
$kode = $_GET['kode'] ?? null;

// Validasi keberadaan parameter kode
if (!$kode) {
    echo "<script>alert('Kode tidak ditemukan'); window.location.href='crud_jenisdata.php';</script>";
    exit();
}

// Ambil data stok bahan berdasarkan kode
$data = getDataStokBahanById($conn, $kode);

// Periksa apakah data ditemukan
if (!$data) {
    echo "<script>alert('Data tidak ditemukan'); window.location.href='crud_jenisdata.php';</script>";
    exit();
}

// Proses update data
if (isset($_POST['submit'])) {
    $nama_jenis = $_POST['nama_jenis'];

    if (updateDataStokBahan($conn, $kode, $nama_jenis)) {
        echo "<script>alert('Data berhasil diupdate'); window.location.href='crud_jenisdata.php';</script>";
    } else {
        echo "<script>alert('Gagal mengupdate data');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Stok Bahan</title>
    <style>
 /* CSS untuk halaman Edit Jenis Data */
body {
    font-family: Arial, sans-serif;
    background-color: #1e1e1e; /* Latar belakang hitam */
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.container {
    background: #2f2f2f; /* Warna abu gelap */
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    width: 400px;
    padding: 20px;
    text-align: center;
}

h2 {
    margin-bottom: 20px;
    font-size: 24px;
    color: #ffd700; /* Warna emas */
}

form {
    display: flex;
    flex-direction: column;
}

label {
    font-size: 14px;
    margin-bottom: 5px;
    color: #ffffff; /* Warna putih */
    text-align: left;
}

input[type="text"] {
    font-size: 16px;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ffd700; /* Border emas */
    border-radius: 5px;
    background-color: #3e3e3e; /* Latar belakang input abu gelap */
    color: #ffffff; /* Teks putih */
    width: 100%;
    box-sizing: border-box;
}

input[type="text"]:focus {
    border-color: #ffa500; /* Warna emas lebih terang */
    outline: none;
    box-shadow: 0 0 5px rgba(255, 165, 0, 0.8); /* Bayangan emas terang */
}

.btn-submit {
    background-color: #ffd700; /* Warna emas */
    color: #1e1e1e; /* Teks hitam */
    font-size: 16px;
    padding: 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.2s;
}

.btn-submit:hover {
    background-color: #ffa500; /* Emas lebih terang */
}

.btn-submit:active {
    background-color: #cc8400; /* Emas lebih gelap */
    transform: scale(0.98); /* Efek klik */
}

/* Tambahan untuk responsif */
@media (max-width: 480px) {
    .container {
        width: 90%;
        padding: 15px;
    }

    h2 {
        font-size: 20px;
    }

    input[type="text"], .btn-submit {
        font-size: 14px;
    }
}


    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Data Stok Bahan</h2>
        <form method="POST" action="">
            <label>Kode:</label>
            <input type="text" name="kode" value="<?= htmlspecialchars($data['kode']); ?>" readonly>
            <label>Nama Bahan:</label>
            <input type="text" name="nama_jenis" value="<?= htmlspecialchars($data['nama_jenis']); ?>" required>
            <button type="submit" name="submit" class="btn-submit">Simpan Perubahan</button>
        </form>
    </div>
</body>
</html>
