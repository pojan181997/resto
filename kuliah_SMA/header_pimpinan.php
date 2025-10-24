<?php
include 'config.php';
include 'crud_functions.php';
include 'session_check.php'
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Badan Kepegawaian Daerah Provinsi Sumatera Barat</title>
    <style>
    /* Import font Poppins */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

/* Import font Jawa tradisional */
@import url('https://fonts.googleapis.com/css2?family=Noto+Serif+JP:wght@400;700&display=swap');

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    display: flex;
    flex-direction: column;
    align-items: center;
    background: linear-gradient(135deg, #5C3B1E, #F4E1C4);
    min-height: 100vh;
}

header {
    width: 100%;
    background: #8B4513;
    color: white;
    padding: 15px;
    text-align: center;
    font-size: 24px;
    font-weight: bold;
    position: relative;
    font-family: 'Noto Serif JP', serif;
}

.container {
    display: flex;
    width: 90%;
    max-width: 1200px;
    background: #FAE3C6;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    overflow: hidden;
    margin-top: 20px;
}

.sidebar {
    width: 25%;
    background: #6A3400;
    padding: 20px;
    display: flex;
    flex-direction: column;
}

.sidebar a, .dropdown-btn {
    display: block;
    color: white;
    text-decoration: none;
    padding: 12px;
    font-size: 16px;
    border-radius: 5px;
    transition: 0.3s;
    background: transparent;
    border: none;
    text-align: left;
    cursor: pointer;
    font-family: 'Noto Serif JP', serif;
}

.sidebar a:hover, .dropdown-btn:hover {
    background: #D4A76A;
    color: #6A3400;
}

.dropdown-content {
    display: none;
    flex-direction: column;
    margin-left: 10px;
}

.dropdown:hover .dropdown-content {
    display: flex;
}

.content {
    width: 75%;
    padding: 30px;
}

.header-container {
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
}

.logout-button {
    position: absolute;
    left: 20px;
    top: 50%;
    transform: translateY(-50%);
}

.logo {
    position: absolute;
    right: 20px;
    top: 50%;
    transform: translateY(-50%);
    height: 50px;
    width: auto;
}

.title {
    flex-grow: 1;
    text-align: center;
    font-family: 'Noto Serif JP', serif;
    font-weight: bold;
}

button {
    padding: 10px;
    background: #D4A76A;
    color: #6A3400;
    border: none;
    font-size: 16px;
    font-weight: bold;
    border-radius: 5px;
    cursor: pointer;
    transition: 0.3s;
}

button:hover {
    background: #C1935B;
}
</style>
</head>
<body>
<header>
    <div class="header-container">
        <div class="logout-button">
            <form method="POST" action="logout.php">
                <button type="submit">Logout</button>
            </form>
        </div>
        <div class="title">Resto Mie Jawa Anglo</div>
        <img src="assets/logo.png" class="logo" alt="Logo">
    </div>
</header>
    
    <div class="container">
    <div class="sidebar">
        <a href="home_pimpinan.php">Home</a>

        <div class="dropdown">
            <button class="dropdown-btn">Data Management ▼</button>
            <div class="dropdown-content">
                <a href="crud_jenisdata_pimpinan.php">Laporan Data Produk</a>
                <a href="crud_databahan_pimpinan.php">Laporan Data Bahan Baku</a>
                <a href="data_pesanan_pimpinan.php">  Laporan Pesanan</a>
                <a href="data_transaksi_pimpinan.php">Laporan Transaksi</a>
            </div>
        </div>
        <div class="dropdown">
            <button class="dropdown-btn">Data olah ▼</button>
            <div class="dropdown-content">
                <a href="hitungSMA_pimpinan.php">Rank</a>
            </div>
        </div>
        
    </div>
        
        <div class="content">
