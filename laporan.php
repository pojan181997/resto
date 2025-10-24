<!-- laporan.php -->
<?php
// Koneksi ke database
include 'header.php'; // Pastikan file ini ada dan koneksi $conn berhasil

// Ambil data dari form
$jenis = $_POST['jenis'] ?? '';
$periode_moving = (int) ($_POST['periode_moving'] ?? 2);
$jumlah_periode_diramal = (int) ($_POST['jumlah_periode_diramal'] ?? 1);
$periode = $_POST['periode'] ?? 'Bulanan';

$hasilSMA = [];
$errors = [];

// Fungsi perhitungan SMA
function hitungSMA($data, $periode_moving) {
    $hasil = [];
    $jumlahData = count($data);
    for ($i = 0; $i <= $jumlahData - $periode_moving; $i++) {
        $sum = 0;
        for ($j = 0; $j < $periode_moving; $j++) {
            $sum += $data[$i + $j]['nilai'];
        }
        $sma = $sum / $periode_moving;
        $hasil[] = [
            'tanggal' => $data[$i + $periode_moving - 1]['tanggal'],
            'sma' => $sma
        ];
    }
    return $hasil;
}

// Fungsi perhitungan error
function hitungError($data, $sma) {
    $mad = 0;
    $mse = 0;
    $mape = 0;
    $n = count($sma);
    for ($i = 0; $i < $n; $i++) {
        $actual = $data[$i]['nilai'];
        $forecast = $sma[$i]['sma'];
        $error = $actual - $forecast;
        $mad += abs($error);
        $mse += pow($error, 2);
        $mape += abs($error / $actual);
    }
    return [
        'MAD' => $mad / $n,
        'MSE' => $mse / $n,
        'MAPE' => ($mape / $n) * 100
    ];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $jenis) {
    $sql_data = "SELECT tanggal, `$jenis` AS nilai FROM makanan ORDER BY tanggal ASC";
    $result_data = $conn->query($sql_data);

    $data = [];
    if ($result_data && $result_data->num_rows > 0) {
        while ($row = $result_data->fetch_assoc()) {
            $data[] = $row;
        }
    }

    if (!empty($data)) {
        $hasilSMA = hitungSMA($data, $periode_moving);

        if (!empty($hasilSMA)) {
            $errors = hitungError(array_slice($data, $periode_moving - 1), $hasilSMA);
        }
    } else {
        echo "<p>Data tidak ditemukan untuk jenis bahan yang dipilih.</p>";
    }

    $forecast = [];
    if (!empty($hasilSMA)) {
        $last_sma = end($hasilSMA)['sma'];
        for ($i = 1; $i <= $jumlah_periode_diramal; $i++) {
            $forecast[] = [
                'periode' => "Periode Ke-$i",
                'forecast' => $last_sma
            ];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Peramalan SMA</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            color: #333;
            margin: 0;
            padding: 0;
        }

        #laporan-header, #laporan-footer {
            text-align: center;
            margin: 20px 0;
        }

        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background: #fff;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background: #006699;
            color: white;
        }

        .print-button {
            text-align: center;
            margin: 30px;
        }

        .print-button button {
            background-color: #28a745;
            font-size: 18px;
            padding: 12px 24px;
            border-radius: 8px;
        }

        @media print {
            body {
                background: white;
            }

            table {
                width: 100%;
                page-break-inside: avoid;
            }

            h1, h2, th, td {
                color: black;
            }
        }
    </style>
</head>
<body>

<div id="laporan-header">
    <h1>Laporan Peramalan SMA</h1>
    <p>Jenis: <?= htmlspecialchars($jenis) ?></p>
    <p>Periode: <?= htmlspecialchars($periode) ?></p>
</div>

<?php if (!empty($hasilSMA)): ?>
    <h2>Hasil SMA</h2>
    <table>
        <thead>
            <tr><th>Tanggal</th><th>SMA</th></tr>
        </thead>
        <tbody>
            <?php foreach ($hasilSMA as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['tanggal']) ?></td>
                <td><?= number_format($row['sma'], 2) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Error Peramalan</h2>
    <table>
        <thead>
            <tr><th>Error</th><th>Nilai</th></tr>
        </thead>
        <tbody>
            <tr><td>MAD</td><td><?= number_format($errors['MAD'], 2) ?></td></tr>
            <tr><td>MSE</td><td><?= number_format($errors['MSE'], 2) ?></td></tr>
            <tr><td>MAPE</td><td><?= number_format($errors['MAPE'], 2) ?>%</td></tr>
        </tbody>
    </table>

    <h2>Peramalan Periode Berikutnya</h2>
    <table>
        <thead>
            <tr><th>Periode</th><th>Forecast</th></tr>
        </thead>
        <tbody>
            <?php foreach ($forecast as $f): ?>
            <tr>
                <td><?= htmlspecialchars($f['periode']) ?></td>
                <td><?= number_format($f['forecast'], 2) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<div class="print-button">
    <button onclick="window.print()">Cetak Laporan ke PDF</button>
</div>

<div id="laporan-footer">
    <p>Dicetak pada: <span id="tanggalCetak"></span></p>
</div>

<script>
    var tanggal = new Date();
    var options = { year: 'numeric', month: 'long', day: 'numeric' };
    document.getElementById("tanggalCetak").textContent = tanggal.toLocaleDateString("id-ID", options);
</script>

</body>
</html>
