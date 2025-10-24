<?php
include 'config.php';

$periode_keseluruhan = $_POST['periode_keseluruhan'] ?? '';
$periode_moving = isset($_POST['periode_moving_keseluruhan']) ? (int)$_POST['periode_moving_keseluruhan'] : 0;

$query = $conn->query("SELECT * FROM view_bahan_perhari");
$data_view = $query ? $query->fetch_all(MYSQLI_ASSOC) : [];

$bahan = [];
if (!empty($data_view)) {
    $fields = array_keys($data_view[0]);
    $bahan = array_slice($fields, 2);
}

$total_mad = $total_mse = $total_mape = 0;
$jumlah_bahan_valid = 0;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cetak Hasil Peramalan Keseluruhan</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 40px;
            position: relative;
        }

        h2, h3 {
            text-align: center;
            margin-bottom: 10px;
        }

        .logo {
    position: absolute;
    top: 20px;
    right: 40px;
    width: 100px;
    z-index: 1;
}

.header-space {
    height: 40px;
}

        .watermark {
            position: fixed;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 48px;
            color: rgba(0, 0, 0, 0.05);
            z-index: -1;
            pointer-events: none;
            user-select: none;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        th {
            background-color: #f2f2f2;
        }

        th, td {
            border: 1px solid #aaa;
            padding: 8px 12px;
            text-align: center;
        }

        p {
            text-align: center;
        }

         @page {
        size: A4;
        margin: 20mm;
    }

    .btn-cetak, .no-print {
        display: none !important;
    }


        .btn-cetak button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        .btn-cetak button:hover {
            background-color: #45a049;
        }

       @media print {
    .logo {
        width: 80px; /* Ukuran lebih kecil untuk cetak */
        top: 10px;
        right: 20px;
    }
       }
    .header-space {
        height: 40px; /* Pastikan teks utama tidak tertutup logo */
    }
    </style>
</head>
<body>
 <div class="header-space"></div>
    <img src="assets/logo.png" class="logo" alt="Logo">
    <div class="watermark">Resto Mie Jawa Anglo</div>

    <h2>Laporan Peramalan Keseluruhan Bahan</h2>
    <p><strong>Periode:</strong> <?= htmlspecialchars($periode_keseluruhan) ?> |
       <strong>Moving Average:</strong> <?= $periode_moving ?> Periode</p>

    <table>
        <tr>
            <th>Bahan</th>
            <th>SMA (Terakhir)</th>
            <th>MAD</th>
            <th>MSE</th>
            <th>MAPE</th>
        </tr>
        <?php foreach ($bahan as $bhn): 
            $actual = array_column($data_view, $bhn);
            $jumlah_data = count($actual);
            if ($jumlah_data <= $periode_moving) continue;

            $sma = $error_abs = $error_sq = $error_pct = [];

            for ($i = $periode_moving - 1; $i < $jumlah_data; $i++) {
                $sum = 0;
                for ($j = $i - $periode_moving + 1; $j <= $i; $j++) {
                    $sum += $actual[$j];
                }
                $forecast = $sum / $periode_moving;
                $sma[] = $forecast;

                $error = $actual[$i] - $forecast;
                $error_abs[] = abs($error);
                $error_sq[] = pow($error, 2);
                $error_pct[] = $actual[$i] != 0 ? abs($error / $actual[$i]) * 100 : 0;
            }

            $n = count($error_abs);
            if ($n === 0) continue;

            $mad = array_sum($error_abs) / $n;
            $mse = array_sum($error_sq) / $n;
            $mape = array_sum($error_pct) / $n;

            $total_mad += $mad;
            $total_mse += $mse;
            $total_mape += $mape;
            $jumlah_bahan_valid++;
        ?>
        <tr>
            <td><?= $bhn ?></td>
            <td><?= number_format(end($sma), 2) ?></td>
            <td><?= number_format($mad, 2) ?></td>
            <td><?= number_format($mse, 2) ?></td>
            <td><?= number_format($mape, 2) ?>%</td>
        </tr>
        <?php endforeach; ?>
    </table>

    <?php if ($jumlah_bahan_valid > 0): ?>
        <h3>Rata-rata Error Keseluruhan</h3>
        <table>
            <tr>
                <th>Total MAD</th><th>Total MSE</th><th>Total MAPE</th>
            </tr>
            <tr>
                <td><?= number_format($total_mad / $jumlah_bahan_valid, 2) ?></td>
                <td><?= number_format($total_mse / $jumlah_bahan_valid, 2) ?></td>
                <td><?= number_format($total_mape / $jumlah_bahan_valid, 2) ?>%</td>
            </tr>
        </table>
    <?php else: ?>
        <p style="color:red;">Tidak ada data yang cukup untuk dihitung.</p>
    <?php endif; ?>

    <div class="btn-cetak" id="btnCetak">
        <button onclick="window.print()">Cetak Lagi</button>
    </div>

    <script>
        window.onload = function() {
            window.print();
            setTimeout(() => {
                document.getElementById('btnCetak').style.display = 'block';
            }, 1000);
        }
    </script>
</body>
</html>
