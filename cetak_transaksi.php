<?php
include 'config.php';

// Ambil semua data transaksi
$dataTransaksi = $conn->query("SELECT * FROM data_transaksi ORDER BY id_transaksi ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Transaksi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px 60px;
            color: #000;
            position: relative;
            background: #fff;
        }

        .header-space {
            height: 100px;
            position: relative;
        }

        .logo-kanan {
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
        }

        h2 {
            text-align: center;
            margin-top: 20px;
            margin-bottom: 30px;
            font-size: 24px;
            color: #4CAF50;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            background: white;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        }

        th, td {
            border: 1px solid #000;
            padding: 8px 12px;
            text-align: center;
            font-size: 14px;
        }

        th {
            background: #f2f2f2;
        }

        .noprint {
            text-align: center;
            margin-bottom: 20px;
        }

        .btn-print {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-print:hover {
            background-color: #388E3C;
            transform: scale(1.05);
        }

        /* Watermark */
        body::before {
            content: "Resto Mie Jawa Anglo";
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 80px;
            color: rgba(0, 0, 0, 0.03);
            z-index: 0;
            pointer-events: none;
        }

        @media print {
            .noprint {
                display: none;
            }

            .logo-kanan {
                display: block;
            }

            body::before {
                color: rgba(0, 0, 0, 0.02);
            }
        }
    </style>
</head>
<body onload="window.print()">

<div class="header-space">
    <img src="assets/logo.png" class="logo-kanan" alt="Logo Resto">
</div>

<div class="noprint">
    <a href="#" onclick="window.print()" class="btn-print">Cetak Lagi</a>
</div>

<p style="text-align: right;">Tanggal Cetak: <?= date('d-m-Y') ?></p>

<h2>Laporan Data Transaksi</h2>

<table>
    <thead>
        <tr>
            <th>ID Transaksi</th>
            <th>Tanggal</th>
            <th>Menu</th>
            <th>Harga Satuan</th>
            <th>Metode Pembayaran</th>
            <th>Status Pembayaran</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $dataTransaksi->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['id_transaksi']) ?></td>
            <td><?= htmlspecialchars($row['tanggal_transaksi']) ?></td>
            <td><?= htmlspecialchars($row['menu']) ?></td>
            <td>Rp <?= number_format($row['harga_satuan'], 0, ',', '.') ?></td>
            <td><?= htmlspecialchars($row['metode_pembayaran']) ?></td>
            <td><?= htmlspecialchars($row['status_pembayaran']) ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>
