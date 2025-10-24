<?php
session_start();
include 'config.php';
include 'crud_functions.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if (isset($_POST['submit'])) {
    $kode = $_POST['kode'];
    $nama_jenis = $_POST['nama_jenis'];
   

    if (createDataStokBahan($conn, $kode, $nama_jenis)) {
        header("Location: crud_jenisdata.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #2f2f2f; /* Hitam */
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100vh;
    }

    h2 {
        color: #d4af37; /* Emas */
    }

    form {
        background-color: #3e2f20; /* Coklat tua */
        border: 1px solid #d4af37; /* Emas */
        border-radius: 8px;
        padding: 20px;
        width: 300px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
        color: #f5f5f5; /* Putih */
    }

    input[type="text"], button {
        width: 100%;
        padding: 8px;
        margin-bottom: 15px;
        border: 1px solid #d4af37; /* Emas */
        border-radius: 4px;
        box-sizing: border-box;
        background-color: #2f2f2f; /* Hitam */
        color: #f5f5f5; /* Putih */
    }

    input[type="text"]:focus {
        outline: none;
        border-color: #b08b2f; /* Coklat emas */
    }

    button {
        background-color: #d4af37; /* Emas */
        color: #2f2f2f; /* Hitam */
        border: none;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
    }

    button:hover {
        background-color: #b08b2f; /* Coklat emas */
    }
</style>

</head>
<body>
    <h2>Tambah Data Karyawan</h2>
    <form method="POST" action="">
        <label for="kode">kode:</label>
        <input type="text" id="kode" name="kode" required>

        <label for="nama">Nama bahan:</label>
        <input type="text" id="nama_jenis" name="nama_jenis" required>

        <button type="submit" name="submit">Simpan</button>
    </form>
</body>
</html>
