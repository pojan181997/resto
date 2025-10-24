<?php
session_start();
include 'config.php';
include 'crud_functions.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Ambil ID karyawan dari URL
$id = $_GET['id'];

// Ambil data karyawan berdasarkan ID
$data = getDataKaryawanById($conn, $id);

if (isset($_POST['submit'])) {
    $nama = $_POST['nama'];
    $jabatan = $_POST['jabatan'];
    $email = $_POST['email'];
    $no_hp = $_POST['no_hp'];

    // Update data karyawan
    if (updateDataKaryawan($conn, $id, $nama, $jabatan, $email, $no_hp)) {
        header("Location: crud.php");
        exit();
    } else {
        echo "<script>alert('Gagal mengupdate data');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<style>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Karyawan</title>
    body {
    font-family: Arial, sans-serif;
    background-color: #2f2f2f;
    color: #f5f5f5;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 600px;
    margin: 50px auto;
    background-color: #3e2f20;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

h2 {
    text-align: center;
    color: #f5f5f5;
    margin-bottom: 20px;
}

label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
    color: #f5f5f5;
}

input[type="text"],
input[type="email"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ddd;
    border-radius: 4px;
    background-color: #f9f9f9;
    color: #333;
}

input[type="text"]:focus,
input[type="email"]:focus {
    border-color: #d4af37;
    outline: none;
}

.btn-submit {
    display: block;
    width: 100%;
    padding: 10px;
    background-color: #d4af37;
    color: #fff;
    border: none;
    border-radius: 4px;
    font-weight: bold;
    cursor: pointer;
}

.btn-submit:hover {
    background-color: #b08b2f;
}
</style>
</head>
<body>
    <div class="container">
        <h2>Edit Data Karyawan</h2>
        <form method="POST" action="">
            <label>ID Karyawan:</label>
            <input type="text" name="id_karyawan" value="<?= htmlspecialchars($data['id_karyawan']); ?>" readonly><br><br>
            <label>Nama:</label>
            <input type="text" name="nama" value="<?= htmlspecialchars($data['nama']); ?>" required><br><br>
            <label>Jabatan:</label>
            <input type="text" name="jabatan" value="<?= htmlspecialchars($data['jabatan']); ?>" required><br><br>
            <label>Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($data['email']); ?>" required><br><br>
            <label>No HP:</label>
            <input type="text" name="no_hp" value="<?= htmlspecialchars($data['no_hp']); ?>" required><br><br>
            <button type="submit" name="submit" class="btn-submit">Simpan Perubahan</button>
        </form>
    </div>
</body>
</html>

