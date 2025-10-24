<?php
include 'config.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Bahan Baku Resto Mie Jawa Anglo</title>
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
            font-size: 14px;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        .noprint {
            margin: 10px 0 20px 0;
            text-align: left;
        }

        .btn-cetak {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.2s ease;
            display: inline-block;
        }

        .btn-cetak:hover {
            background-color: #43a047;
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
            color: rgba(0, 0, 0, 0.04);
            white-space: nowrap;
            z-index: 0;
            pointer-events: none;
        }

        @media print {
            .noprint {
                display: none;
            }

            body::before {
                font-size: 70px;
                color: rgba(0, 0, 0, 0.02);
            }
        }
    </style>
</head>
<body onload="window.print()">

<!-- Header Space & Logo -->
<div class="header-space">
    <img src="assets/logo.png" alt="Logo Resto" class="logo-kanan">
</div>

<!-- Tombol Cetak Lagi -->
<div class="noprint">
    <a href="#" class="btn-cetak" onclick="window.print()">Cetak Lagi</a>
</div>

<!-- Info & Judul -->
<p style="text-align: right;">Tanggal Cetak: <?= date('d-m-Y') ?></p>
<h2>Laporan Bahan Baku Resto Mie Jawa Anglo</h2>

<!-- Tabel Laporan -->
<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Ayam Pecel</th>
            <th>Ayam Suwir</th>
            <th>Lele</th>
            <th>Mie</th>
            <th>Udang</th>
            <th>Bumbu Basah</th>
            <th>Bumbu Kering</th>
            <th>Pisang</th>
            <th>Tempe</th>
            <th>Kecap</th>
            <th>Minyak</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;
        $data = $conn->query("SELECT * FROM makanan");
        while ($row = $data->fetch_assoc()):
        ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= htmlspecialchars($row['tanggal']); ?></td>
            <td><?= htmlspecialchars($row['ayam_pecel']); ?></td>
            <td><?= htmlspecialchars($row['ayam_suwir']); ?></td>
            <td><?= htmlspecialchars($row['lele']); ?></td>
            <td><?= htmlspecialchars($row['mie']); ?></td>
            <td><?= htmlspecialchars($row['udang']); ?></td>
            <td><?= htmlspecialchars($row['bumbu_basah']); ?></td>
            <td><?= htmlspecialchars($row['bumbu_kering']); ?></td>
            <td><?= htmlspecialchars($row['pisang']); ?></td>
            <td><?= htmlspecialchars($row['tempe']); ?></td>
            <td><?= htmlspecialchars($row['kecap']); ?></td>
            <td><?= htmlspecialchars($row['minyak']); ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>
