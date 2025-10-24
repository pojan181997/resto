<?php
include 'config.php'; // ganti dengan file koneksi Anda

$jenis = $_POST['jenis'] ?? '';
$periode = (int) $_POST['periode_moving'] ?? 2;
$jumlah_ramal = (int) $_POST['jumlah_periode_diramal'] ?? 1;

$data = [];
$tabel = "tb_" . strtolower($jenis);
$sql = "SELECT tanggal, jumlah AS nilai FROM $tabel ORDER BY tanggal ASC";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

function hitungSMA($data, $periode) {
    $hasil = [];
    for ($i = 0; $i <= count($data) - $periode; $i++) {
        $sum = 0;
        for ($j = 0; $j < $periode; $j++) {
            $sum += $data[$i + $j]['nilai'];
        }
        $hasil[] = [
            'tanggal' => $data[$i + $periode - 1]['tanggal'],
            'sma' => $sum / $periode
        ];
    }
    return $hasil;
}

function hitungError($data, $sma, $periode) {
    $mad = $mse = $mape = 0;
    $n = count($sma);
    for ($i = 0; $i < $n; $i++) {
        $aktual = $data[$i + ($periode - 1)]['nilai'];
        $prediksi = $sma[$i]['sma'];
        $error = $aktual - $prediksi;
        $mad += abs($error);
        $mse += pow($error, 2);
        if ($aktual != 0) $mape += abs($error / $aktual);
    }
    return [
        'MAD' => ($n > 0) ? $mad / $n : 0,
        'MSE' => ($n > 0) ? $mse / $n : 0,
        'MAPE' => ($n > 0) ? ($mape / $n) * 100 : 0
    ];
}

$hasilSMA = hitungSMA($data, $periode);
$errors = hitungError($data, $hasilSMA, $periode);

$tanggalCetak = date('d-m-Y');
?>


<!DOCTYPE html>
<html>
<head>
    <style>
    body {
        font-family: 'Segoe UI', Tahoma, sans-serif;
        margin: 40px;
        color: #333;
        background-color: #fefefe;
    }

    h2, h3 {
        text-align: center;
        margin: 0;
    }

    h2 {
        margin-bottom: 5px;
        font-size: 22px;
        text-transform: uppercase;
        color: #2c3e50;
    }

    h3 {
        margin-bottom: 25px;
        font-size: 17px;
        color: #34495e;
    }

    .info {
        margin-bottom: 25px;
        font-size: 14px;
    }

    table {
        width: 85%;
        margin: auto;
        border-collapse: collapse;
        font-size: 14px;
        background-color: #fff;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }

    th, td {
        border: 1px solid #ccc;
        padding: 10px 8px;
        text-align: center;
    }

    th {
        background-color: #ecf0f1;
        color: #2c3e50;
    }

    tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    tbody tr:hover {
        background-color: #e8f6ff;
    }

    .ttd {
        margin-top: 60px;
        width: 85%;
        font-size: 14px;
        text-align: right;
    }

    .ttd p {
        margin: 4px 0;
    }

    @media print {
        body {
            margin: 10mm;
            background-color: #fff;
        }

        .no-print {
            display: none;
        }
    }



    </style>
</head>
<body onload="window.print()">
<div style="text-align: center; margin-bottom: 20px;">
    <img src="assets/logo.png" alt="Logo" style="height: 70px;">
</div>

    <h2>LAPORAN PERAMALAN SIMPLE MOVING AVERAGE (SMA)</h2>
    <h3>Jenis: <?= strtoupper($jenis) ?></h3>
    
    <div class="info">
        <strong>Periode SMA:</strong> <?= $periode ?><br>
        <strong>Tanggal Cetak:</strong> <?= $tanggalCetak ?>
    </div>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Jumlah Aktual</th>
                <th>Hasil SMA</th>
            </tr>
        </thead>
        <tbody>
            <?php for ($i = 0; $i < count($hasilSMA); $i++): ?>
            <tr>
                <td><?= $hasilSMA[$i]['tanggal'] ?></td>
                <td><?= $data[$i + $periode - 1]['nilai'] ?></td>
                <td><?= round($hasilSMA[$i]['sma'], 2) ?></td>
            </tr>
            <?php endfor; ?>
        </tbody>
    </table>

    <h3>Perhitungan Error</h3>
    <table style="width: 50%; margin: auto;">
        <tr><th>MAD</th><td><?= round($errors['MAD'], 2) ?></td></tr>
        <tr><th>MSE</th><td><?= round($errors['MSE'], 2) ?></td></tr>
        <tr><th>MAPE (%)</th><td><?= round($errors['MAPE'], 2) ?></td></tr>
    </table>

    <div class="ttd">
        <p>Padang, <?= $tanggalCetak ?></p>
        <p><br><br>____________________</p>
        <p><i>Tanda Tangan</i></p>
    </div>

</body>
</html>
