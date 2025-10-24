<?php
// Koneksi ke database
include 'header_pimpinan.php';

// Ambil nilai dari form input dengan nilai default
$jenis = $_POST['jenis'] ?? '';
$periode_moving = (int) ($_POST['periode_moving'] ?? 2);
$jumlah_periode_diramal = (int) ($_POST['jumlah_periode_diramal'] ?? 1);
$periode = $_POST['periode'] ?? 'Bulanan';

$hasilSMA = [];
$errors = [];
$data = [];
$forecast = [];

$tables = [
    'tb_ayam_pecel', 'tb_ayam_suwir', 'tb_lele', 'tb_mie', 'tb_udang',
    'tb_bumbu_basah', 'tb_bumbu_kering', 'tb_pisang', 'tb_tempe', 'tb_kecap', 'tb_minyak'
];

// Fungsi untuk menghitung Simple Moving Average (SMA)
function hitungSMA($data, $periode_moving) {
    $hasil = [];
    $jumlahData = count($data);
    for ($i = 0; $i <= $jumlahData - $periode_moving; $i++) {
        $sum = 0;
        for ($j = 0; $j < $periode_moving; $j++) {
            $sum += $data[$i + $j]['nilai'];
        }
        $hasil[] = [
            'tanggal' => $data[$i + $periode_moving - 1]['tanggal'],
            'sma' => $sum / $periode_moving
        ];
    }
    return $hasil;
}

// Fungsi untuk menghitung error
function hitungError($data, $sma) {
    $mad = $mse = $mape = 0;
    $n = count($sma);

    for ($i = 0; $i < $n; $i++) {
        $actual = $data[$i]['nilai'];
        $forecast = $sma[$i]['sma'];
        $error = $actual - $forecast;

        $mad += abs($error);
        $mse += pow($error, 2);
        $mape += ($actual != 0) ? abs($error / $actual) : 0;
    }

    return [
        'MAD' => ($n > 0) ? $mad / $n : 0,
        'MSE' => ($n > 0) ? $mse / $n : 0,
        'MAPE' => ($n > 0) ? ($mape / $n) * 100 : 0
    ];
}

// Proses utama
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $jenis) {

    if ($jenis === 'Semua Data') {
        $semuaData = [];
        foreach ($tables as $table) {
            $query = mysqli_query($conn, "SELECT tanggal, jumlah AS nilai FROM `$table` ORDER BY tanggal ASC");
            if ($query) {
                while ($row = mysqli_fetch_assoc($query)) {
                    $row['tabel'] = $table;
                    $semuaData[] = $row;
                }
            } else {
                echo "<p>Gagal mengambil data dari tabel $table: " . mysqli_error($conn) . "</p>";
            }
        }
        // Opsional: lakukan pengolahan terhadap $semuaData jika ingin agregasi dari semua tabel
    } else {
        $nama_tabel = "tb_" . strtolower($jenis);
        if (in_array($nama_tabel, $tables)) {
            $sql_data = "SELECT tanggal, jumlah AS nilai FROM `$nama_tabel` ORDER BY tanggal ASC";
            $result_data = $conn->query($sql_data);

            if ($result_data && $result_data->num_rows > 0) {
                while ($row = $result_data->fetch_assoc()) {
                    $data[] = $row;
                }

                $hasilSMA = hitungSMA($data, $periode_moving);

                if (!empty($hasilSMA)) {
                    $data_for_error = array_slice($data, $periode_moving - 1);
                    $errors = hitungError($data_for_error, $hasilSMA);

                    // Proyeksi (forecast)
                    $forecast_data = array_column($data, 'nilai');
                    for ($i = 0; $i < $jumlah_periode_diramal; $i++) {
                        $next = array_slice($forecast_data, -$periode_moving);
                        $next_sma = array_sum($next) / $periode_moving;
                        $forecast[] = [
                            'periode' => "Periode Ke-" . ($i + 1),
                            'forecast' => $next_sma
                        ];
                        $forecast_data[] = $next_sma;
                    }
                }

            } else {
                echo "<p>Data tidak ditemukan pada tabel <strong>$nama_tabel</strong>.</p>";
            }
        } else {
            echo "<p>Tabel tidak valid.</p>";
        }
    }
}

$query = $conn->query("SELECT * FROM view_bahan_perhari");
$data_view = $query ? $query->fetch_all(MYSQLI_ASSOC) : [];

// Ambil nama-nama bahan dari kolom (kecuali 'No' dan 'tanggal')
$bahan = [];
if (!empty($data_view)) {
    $fields = array_keys($data_view[0]);
    $bahan = array_slice($fields, 2); // kolom bahan mulai dari indeks ke-2
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perhitungan SMA dan Error</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            color: #333;
            margin: 0;
            padding: 0;
        }

        h1, h2 {
            text-align: center;
            color: #006699;
            margin-top: 20px;
        }

        

        label {
            font-weight: bold;
            color: #006699;
            margin-bottom: 5px;
            display: block;
        }

        input, select, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
        }

        button {
            background-color: #006699;
            color: #fff;
            font-weight: bold;
            border: none;
            transition: background-color 0.3s;
            cursor: pointer;
        }

        button:hover {
            background-color: #005580;
        }

        /* Table */
        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #006699;
            color: #ffffff;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .table-wrapper {
    width: 95%;
    margin: 30px auto;
    overflow-x: auto;
}

table.sma-table,
table.error-table {
    border-collapse: collapse;
    width: 100%;
    background-color: #fff;
    margin-top: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

table.sma-table th,
table.sma-table td,
table.error-table th,
table.error-table td {
    padding: 10px 12px;
    border: 1px solid #ddd;
    text-align: center;
    font-size: 14px;
}

table.sma-table th,
table.error-table th {
    background-color: #006699;
    color: white;
}

table.sma-table tr:nth-child(even),
table.error-table tr:nth-child(even) {
    background-color: #f9f9f9;
}
 .error-table {
        width: 60%;
        margin: 20px auto;
        border-collapse: collapse;
        font-family: Arial, sans-serif;
        font-size: 15px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .error-table th, .error-table td {
        border: 1px solid #ddd;
        padding: 12px;
        text-align: center;
    }

    .error-table th {
        background-color: #f4f4f4;
        color: #333;
        text-transform: uppercase;
    }

    .error-table tr:nth-child(even) {
        background-color: #fafafa;
    }

    .error-table caption {
        caption-side: top;
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 10px;
        color: #444;
    }
    @media print {
        button {
            display: none;
        }
        select, input, form {
            display: none !important;
        }
    }
    </style>
</head>
<body>
    <h1>Perhitungan Simple Moving Average (SMA) dan Error</h1>

    <form method="POST" action="">
        <label for="jenis">Pilih Jenis:</label>
        <select id="jenis" name="jenis" required>
            <option value="">-- Pilih Jenis --</option>
            <option value="ayam_pecel" <?= $jenis === 'ayam_pecel' ? 'selected' : '' ?>>Ayam Pecel</option>
            <option value="ayam_suwir" <?= $jenis === 'ayam_suwir' ? 'selected' : '' ?>>Ayam Suwir</option>
            <option value="lele" <?= $jenis === 'lele' ? 'selected' : '' ?>>Lele</option>
            <option value="mie" <?= $jenis === 'mie' ? 'selected' : '' ?>>Mie</option>
            <option value="udang" <?= $jenis === 'udang' ? 'selected' : '' ?>>Udang</option>
            <option value="bumbu_basah" <?= $jenis === 'bumbu_basah' ? 'selected' : '' ?>>Bumbu basah</option>
            <option value="bumbu_kering" <?= $jenis === 'bumbu_kering' ? 'selected' : '' ?>>Bumbu Kering</option>
            <option value="pisang" <?= $jenis === 'pisang' ? 'selected' : '' ?>>Pisang</option>
            <option value="tempe" <?= $jenis === 'tempe' ? 'selected' : '' ?>>Tempe</option>
            <option value="kecap" <?= $jenis === 'kecap' ? 'selected' : '' ?>>Kecap</option>
            <option value="minyak" <?= $jenis === 'minyak' ? 'selected' : '' ?>>Minyak</option>
            

        </select>

        <label for="periode">Periode:</label>
        <select id="periode" name="periode" required>
            <option value="Bulanan" <?= $periode === 'Bulanan' ? 'selected' : '' ?>>Bulanan</option>
            <option value="Mingguan" <?= $periode === 'Mingguan' ? 'selected' : '' ?>>Mingguan</option>
        </select>

        <label for="periode_moving">Periode Moving:</label>
        <input type="number" id="periode_moving" name="periode_moving" value="<?= htmlspecialchars($periode_moving) ?>" min="1" required>

        <label for="jumlah_periode_diramal">Jumlah Periode Diramal:</label>
        <input type="number" id="jumlah_periode_diramal" name="jumlah_periode_diramal" value="<?= htmlspecialchars($jumlah_periode_diramal) ?>" min="1" required>

        
        <button type="submit">Hitung</button>
    </form>


   <?php if (!empty($data)): ?>
    <h2>Hasil SMA</h2>
    <div class="table-wrapper">
        <table class="sma-table" border="1" cellpadding="5" cellspacing="0">
            <tr>
                <th>Periode (t)</th>
                <th>At (Aktual)</th>
                <th>Rumus</th>
                <th>Jumlah</th>
                <th>SMA (Ft)</th>
            </tr>
            <?php
            $hasilSMA = [];

            for ($i = $periode_moving - 1; $i < count($data); $i++) {
                $sum = 0;
                $formula = '';

                for ($j = 0; $j < $periode_moving; $j++) {
                    $index = $i - $j;
                    $nilai = $data[$index]['nilai'];

                    $sum += $nilai;
                    $formula .= $nilai;

                    if ($j != $periode_moving - 1) {
                        $formula .= ' + ';
                    }
                }

                $ma = $sum / $periode_moving;

                $hasilSMA[] = [
                    'periode' => $data[$i]['tanggal'],
                    'sma' => $ma,
                ];

                echo "<tr>
                    <td>" . ($i + 1) . "</td>
                    <td>" . $data[$i]['nilai'] . "</td>
                    <td>(" . $formula . ") / $periode_moving</td>
                    <td>" . round($sum, 2) . "</td>
                    <td>" . round($ma, 2) . "</td>
                </tr>";
            }
            ?>
        </table>
    </div>

    <h2>Perhitungan Error</h2>
    <div class="table-wrapper">
        <table class="error-table" border="1" cellpadding="5" cellspacing="0">
            <tr>
                <th>No</th>
                <th>Periode</th>
                <th>At (Aktual)</th>
                <th>Ft (Forecast)</th>
                <th>ABS Error (MAD)</th>
                <th>Error MSE</th>
                <th>MAPE %</th>
            </tr>
            

            <?php
            $total_mad = 0;
            $total_mse = 0;
            $total_mape = 0;
            $errors = [];

            $actual = array_slice($data, $periode_moving - 1);

            for ($i = 0; $i < count($actual); $i++) {
                $periode = $actual[$i]['tanggal'];
                $at = $actual[$i]['nilai'];
                $ft = $hasilSMA[$i]['sma'];

                $error = abs($at - $ft);
                $mse = pow($error, 2);
                $mape = ($at != 0) ? ($error / $at) * 100 : 0;

                $total_mad += $error;
                $total_mse += $mse;
                $total_mape += $mape;

                echo "<tr>
                    <td>" . ($i + 1) . "</td>
                    <td>" . $periode . "</td>
                    <td>" . round($at, 2) . "</td>
                    <td>" . round($ft, 2) . "</td>
                    <td>" . round($error, 2) . "</td>
                    <td>" . round($mse, 2) . "</td>
                    <td>" . round($mape, 2) . "%</td>
                </tr>";
            }

            $n = count($actual);
            $errors['MAD'] = round($total_mad / $n, 2);
            $errors['MSE'] = round($total_mse / $n, 2);
            $errors['MAPE'] = round($total_mape / $n, 2);

            echo "<tr style='font-weight:bold; background-color:#e0f7fa;'>
                <td colspan='4'>Rata-rata</td>
                <td>{$errors['MAD']}</td>
                <td>{$errors['MSE']}</td>
                <td>{$errors['MAPE']}%</td>
            </tr>";
            ?>
        </table>
        <form action="cetak_peramalan.php" method="post" target="_blank">
    <input type="hidden" name="jenis" value="<?= $jenis ?>">
    <input type="hidden" name="periode_moving" value="<?= $periode_moving ?>">
    <input type="hidden" name="jumlah_periode_diramal" value="<?= $jumlah_periode_diramal ?>">
    <button type="submit">Cetak PDF</button>
</form>

    </div>
<?php endif; ?>
   <h2>Perhitungan Keseluruhan Semua Bahan</h2>

<form method="POST" action="">
    <input type="hidden" name="aksi" value="keseluruhan">

    <label for="periode_keseluruhan">Periode:</label>
    <select id="periode_keseluruhan" name="periode_keseluruhan" required>
        <option value="">-- Pilih Periode --</option>
        <option value="Bulanan" <?= isset($_POST['periode_keseluruhan']) && $_POST['periode_keseluruhan'] === 'Bulanan' ? 'selected' : '' ?>>Bulanan</option>
        <option value="Mingguan" <?= isset($_POST['periode_keseluruhan']) && $_POST['periode_keseluruhan'] === 'Mingguan' ? 'selected' : '' ?>>Mingguan</option>
    </select>

    <label for="periode_moving_keseluruhan">Periode Moving:</label>
    <input type="number" id="periode_moving_keseluruhan" name="periode_moving_keseluruhan" value="<?= isset($_POST['periode_moving_keseluruhan']) ? htmlspecialchars($_POST['periode_moving_keseluruhan']) : '' ?>" min="1" required>

    <button type="submit">Hitung Keseluruhan</button>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aksi']) && $_POST['aksi'] === 'keseluruhan') {
    $periode = isset($_POST['periode_moving_keseluruhan']) ? (int)$_POST['periode_moving_keseluruhan'] : 0;

    if ($periode <= 0) {
        echo "<p style='color:red;'>Periode Moving tidak boleh nol atau kosong.</p>";
    } else {
        echo "<table border='1'>
            <tr>
                <th>Bahan</th>
                <th>SMA (Terakhir)</th>
                <th>MAD</th>
                <th>MSE</th>
                <th>MAPE</th>
            </tr>";


        $total_mad = $total_mse = $total_mape = 0;
        $jumlah_bahan_valid = 0;

        foreach ($bahan as $bhn) {
            $actual = array_column($data_view, $bhn);
            $jumlah_data = count($actual);
            
            if ($jumlah_data <= $periode) continue;

            $sma = [];
            $error_abs = [];
            $error_sq = [];
            $error_pct = [];

            // Mulai dari index = periode karena butuh data sebelumnya sebanyak periode
            for ($i = $periode - 1; $i < count($actual); $i++) {
    $sum = 0;
    for ($j = $i - $periode + 1; $j <= $i; $j++) {
        $sum += $actual[$j];
    }

    $forecast = $sum / $periode;
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

            echo "<tr>
                    <td>$bhn</td>
                    <td>" . number_format(end($sma), 2) . "</td>
                    <td>" . number_format($mad, 2) . "</td>
                    <td>" . number_format($mse, 2) . "</td>
                    <td>" . number_format($mape, 2) . " %</td>
                </tr>";
        }

        echo "</table>";

        // Total keseluruhan
        if ($jumlah_bahan_valid > 0) {
            echo "<h3>Validasi Error Total (dibagi $jumlah_bahan_valid bahan)</h3>
            <table border='1'>
                <tr>
                    <th>Total MAD</th><th>Total MSE</th><th>Total MAPE</th>
                </tr>
                <tr>
                    <td>" . number_format($total_mad / $jumlah_bahan_valid, 2) . "</td>
                    <td>" . number_format($total_mse / $jumlah_bahan_valid, 2) . "</td>
                    <td>" . number_format($total_mape / $jumlah_bahan_valid, 2) . " %</td>
                </tr>
            </table>";
        } else {
            echo "<p>Tidak ada data yang cukup untuk dihitung.</p>";
        }
    }
}

?>
<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aksi']) && $_POST['aksi'] === 'keseluruhan' && $jumlah_bahan_valid > 0) {
        // Ambil nilai yang sudah dihitung
        $periode_keseluruhan = htmlspecialchars($_POST['periode_keseluruhan']);
        $periode_moving_keseluruhan = htmlspecialchars($_POST['periode_moving_keseluruhan']);
?>

<form action="cetak_keseluruhan.php" method="post" target="_blank">
    <input type="hidden" name="periode_keseluruhan" value="<?= $periode_keseluruhan ?>">
    <input type="hidden" name="periode_moving_keseluruhan" value="<?= $periode_moving_keseluruhan ?>">
    <button type="submit">Cetak PDF</button>
</form>


<?php
    }
?>

</body>
</html>


