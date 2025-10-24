<?php
session_start();
include 'config.php';
include 'crud_functions.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if (isset($_POST['submit'])) {
    $No = $_POST['No'];
    $tanggal = $_POST['tanggal'];
    $ayam_pecel = $_POST['ayam_pecel'];
    $ayam_suwir = $_POST['ayam_suwir'];
    $lele = $_POST['lele'];
    $mie = $_POST['mie'];
    $udang = $_POST['udang'];
    $bumbu_basah = $_POST['bumbu_basah'];
    $bumbu_kering = $_POST['bumbu_kering'];
    $pisang = $_POST['pisang'];
    $tempe = $_POST['tempe'];
    $kecap = $_POST['kecap'];
    $minyak = $_POST['minyak'];
    
   

    if (createDataMakanan($conn, $No, $tanggal, $ayam_pecel, $ayam_suwir, $lele, $mie, $udang, $bumbu_basah, $bumbu_kering, $pisang, $tempe, $kecap, $minyak)) {
        header("Location: crud_databahan.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Bahan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fff;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        h2 {
            color: #bfa02f;
            margin-bottom: 20px;
        }

        form {
            background-color: #ffffff;
            border: 1px solid #bfa02f;
            border-radius: 10px;
            padding: 20px 30px;
            width: 80%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
        }

        label {
            font-weight: bold;
            color: #bfa02f;
        }

        input[type="text"], input[type="date"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #bfa02f;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 14px;
            background-color: #fdf9f0;
            color: #333;
        }

        input[type="text"]:focus, input[type="date"]:focus {
            border-color: #d6b022;
            outline: none;
        }

        .full-width {
            grid-column: span 4;
        }

        button {
            grid-column: span 4;
            padding: 10px;
            background-color: #bfa02f;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.1s ease;
        }

        button:hover {
            background-color: #d6b022;
        }

        button:active {
            background-color: #997a1f;
            transform: scale(0.98);
        }
    </style>
</head>
<body>
    <h2>Tambah Data Bahan</h2>
    <form method="POST" action="">
        <label for="No">No:</label>
        <input type="text" id="No" name="No" required>

        <label for="tanggal">Tanggal:</label>
        <input type="date" id="tanggal" name="tanggal" required>

        <label for="ayam_pecel">Ayam Pecel:</label>
        <input type="text" id="ayam_pecel" name="ayam_pecel" required>

        <label for="ayam_suwir">Ayam Suwir:</label>
        <input type="text" id="ayam_suwir" name="ayam_suwir" required>

        <label for="lele">Lele:</label>
        <input type="text" id="lele" name="lele" required>

        <label for="mie">Mie:</label>
        <input type="text" id="mie" name="mie" required>

        <label for="udang">Udang:</label>
        <input type="text" id="udang" name="udang" required>

        <label for="bumbu_basah">Bumbu Basah:</label>
        <input type="text" id="bumbu_basah" name="bumbu_basah" required>

        <label for="bumbu_kering">Bumbu Kering:</label>
        <input type="text" id="bumbu_kering" name="bumbu_kering" required>

        <label for="pisang">Pisang:</label>
        <input type="text" id="pisang" name="pisang" required>

        <label for="tempe">Tempe:</label>
        <input type="text" id="tempe" name="tempe" required>

        <label for="kecap">Kecap:</label>
        <input type="text" id="kecap" name="kecap" required>

        <label for="minyak">Minyak:</label>
        <input type="text" id="minyak" name="minyak" required>

        <button type="submit" name="submit">Simpan</button>
    </form>
</body>
</html>
