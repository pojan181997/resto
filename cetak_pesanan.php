<?php
include 'config.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pesanan Resto Mie Jawa Anglo</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 40px 60px;
            background: #f8f8f8;
            color: #333;
            position: relative;
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
            margin-bottom: 30px;
            font-size: 24px;
            color: #4CAF50;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            background: white;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px 8px;
            text-align: center;
        }

        th {
            background: linear-gradient(#4CAF50, #45A049);
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        .noprint {
            text-align: center;
            margin-bottom: 20px;
        }

        .noprint button, .noprint a {
            padding: 10px 20px;
            margin: 5px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-weight: bold;
        }

        .noprint button {
            background-color: rgb(255, 255, 255);
            color: white;
        }

        .noprint a {
            background-color: #f44336;
            color: white;
        }

        /* Watermark */
        body::before {
            content: "Resto Mie Jawa Anglo";
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 80px;
            color: rgba(0, 0, 0, 0.04);
            white-space: nowrap;
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
                font-size: 70px;
                color: rgba(0, 0, 0, 0.02); /* lebih redup agar tidak ganggu */
            }
        }
        .btn-cetak {
    background-color: #4CAF50;
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    font-weight: bold;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.btn-cetak:hover {
    background-color: #43a047;
    transform: scale(1.05);
}

    </style>
</head>
<body onload="window.print()">
<div class="noprint">
    <a href="#" onclick="window.print()" class="btn-cetak">Cetak Lagi</a>
</div>

<div class="header-space">
    <img src="assets/logo.png" class="logo-kanan" alt="Logo Resto">
</div>

<p style="text-align: right;">Tanggal Cetak: <?= date('d-m-Y') ?></p>
<h2>Laporan Pesanan Resto Mie Jawa Anglo</h2>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>ID Pesanan</th>
            <th>ID Transaksi</th>
            <th>Menu</th>
            <th>Jumlah</th>
            <th>Harga Satuan</th>
            <th>Total Harga</th>
            <th>Catatan</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;
        $query = $conn->query("SELECT * FROM pesanan ORDER BY id_pesanan ASC");
        while ($row = $query->fetch_assoc()):
            $total = $row['jumlah'] * $row['harga_satuan'];
        ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= htmlspecialchars($row['id_pesanan']); ?></td>
            <td><?= htmlspecialchars($row['id_transaksi']); ?></td>
            <td><?= htmlspecialchars($row['menu']); ?></td>
            <td><?= htmlspecialchars($row['jumlah']); ?></td>
            <td>Rp <?= number_format($row['harga_satuan'], 0, ',', '.'); ?></td>
            <td>Rp <?= number_format($total, 0, ',', '.'); ?></td>
            <td><?= htmlspecialchars($row['catatan']); ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>
