<?php
session_start();
include 'config.php';
include 'crud_functions.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if (isset($_POST['submit'])) {
    $id_karyawan = $_POST['id_karyawan'];
    $nama = $_POST['nama'];
    $jabatan = $_POST['jabatan'];
    $email = $_POST['email'];
    $no_hp = $_POST['no_hp'];

    if (createDataKaryawan($conn, $id_karyawan, $nama, $jabatan, $email, $no_hp)) {
        header("Location: crud.php");
        exit();
    } else {
        echo "<script>alert('Gagal menambahkan data.');</script>";
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
        background-color: #f5f5f5; /* Warna putih pucat untuk latar belakang */
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100vh;
    }

    h2 {
        color: #4e342e; /* Warna coklat tua */
        margin-bottom: 20px;
    }

    form {
        background-color: #ffffff; /* Warna putih */
        border: 1px solid #8d6e63; /* Warna coklat untuk border */
        border-radius: 10px;
        padding: 20px 30px;
        width: 400px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
        color: #3e2723; /* Warna coklat tua */
    }

    input[type="text"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #d7ccc8; /* Warna coklat muda */
        border-radius: 5px;
        box-sizing: border-box;
        font-size: 14px;
        background-color: #fafafa; /* Warna putih sedikit gelap */
    }

    button {
        width: 100%;
        padding: 10px;
        background-color: #4e342e; /* Warna coklat tua */
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    button:hover {
        background-color: #3e2723; /* Warna coklat lebih gelap saat hover */
    }

    button:active {
        background-color: #2e1d18; /* Warna coklat sangat gelap saat ditekan */
    }
</style>

</head>
<body>
    <h2>Tambah Data Karyawan</h2>
    <form method="POST" action="">
        <label for="id_karyawan">ID Karyawan:</label>
        <input type="text" id="id_karyawan" name="id_karyawan" required>

        <label for="nama">Nama:</label>
        <input type="text" id="nama" name="nama" required>

        <label for="jabatan">Jabatan:</label>
        <input type="text" id="jabatan" name="jabatan" required>

        <label for="email">Email:</label>
        <input type="text" id="email" name="email" required>

        <label for="no_hp">No HP:</label>
        <input type="text" id="no_hp" name="no_hp" required>

        <button type="submit" name="submit">Simpan</button>
    </form>
</body>
</html>
