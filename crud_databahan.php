<?php include 'header.php'; ?>
<?php
include 'config.php';

// Inisialisasi nilai default
$edit = isset($_GET['edit']);
$showForm = $edit || isset($_GET['add']); // Tampilkan popup form jika sedang tambah atau edit

// Inisialisasi data default untuk form
$data = array_fill_keys([
   'no', 'tanggal', 'ayam_pecel', 'ayam_suwir', 'lele', 'mie', 'udang',
    'bumbu_basah', 'bumbu_kering', 'pisang', 'tempe', 'kecap', 'minyak'
], 0);

// Ambil data untuk form edit jika parameter ?edit= ada
if ($edit) {
    $id = $_GET['edit'];
    $query = "SELECT * FROM view_bahan_perhari WHERE No = '$id'";
    $res = mysqli_query($conn, $query);
    if ($res && mysqli_num_rows($res) > 0) {
        $data = mysqli_fetch_assoc($res);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $no = isset($_POST['edit']) && $_POST['edit'] !== '' ? $_POST['edit'] : mysqli_real_escape_string($conn, $_POST['no']);
    $tanggal = mysqli_real_escape_string($conn, $_POST['tanggal']);

    $fields = [
        'ayam_pecel'     => 'tb_ayam_pecel',
        'ayam_suwir'     => 'tb_ayam_suwir',
        'lele'           => 'tb_lele',
        'mie'            => 'tb_mie',
        'udang'          => 'tb_udang',
        'bumbu_basah'    => 'tb_bumbu_basah',
        'bumbu_kering'   => 'tb_bumbu_kering',
        'pisang'         => 'tb_pisang',
        'tempe'          => 'tb_tempe',
        'kecap'          => 'tb_kecap',
        'minyak'         => 'tb_minyak'
    ];

    foreach ($fields as $key => $table) {
        $jumlah = mysqli_real_escape_string($conn, $_POST[$key]);

        $cek = mysqli_query($conn, "SELECT 1 FROM $table WHERE No = '$no'");
        if (mysqli_num_rows($cek) > 0) {
            $query = "UPDATE $table SET jumlah = '$jumlah', tanggal = '$tanggal' WHERE No = '$no'";
        } else {
            $query = "INSERT INTO $table (No, tanggal, jumlah) VALUES ('$no', '$tanggal', '$jumlah')";
        }

        mysqli_query($conn, $query) or die("Gagal eksekusi query: $query - " . mysqli_error($conn));
    }

    header("Location: crud_databahan.php");
    exit;
}


// Proses Hapus
if (isset($_GET['delete'])) {
    $no = $_GET['delete'];
    $tables = [
        'tb_ayam_pecel', 'tb_ayam_suwir', 'tb_lele', 'tb_mie', 'tb_udang',
        'tb_bumbu_basah', 'tb_bumbu_kering', 'tb_pisang', 'tb_tempe', 'tb_kecap', 'tb_minyak'
    ];

    foreach ($tables as $table) {
        $query = "DELETE FROM $table WHERE No = '$no'";
        mysqli_query($conn, $query) or die("Gagal hapus data: $query - " . mysqli_error($conn));
    }

    header("Location: crud_databahan.php");
    exit;
}
?>

<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #1e1e1e;
        color: #ffffff;
    }

    h2 {
        color: rgb(251, 0, 0);
        margin-left: 20px;
    }

    a {
        text-decoration: none;
        margin-left: 20px;
        font-weight: bold;
        transition: color 0.3s;
    }

    a:hover {
        color: #ff4500;
    }

    .table-container {
        background-color: #2f2f2f;
        padding: 20px;
        margin: 20px;
        border-radius: 15px;
        max-height: 400px;
        overflow: auto;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.4);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        color: #ffffff;
        min-width: 1000px;
    }

    th, td {
        border: 1px solid #ffd700;
        padding: 10px;
        text-align: center;
    }

    th {
        background-color: #ffd700;
        color: #1e1e1e;
    }

    td {
        background-color: #3e3e3e;
    }

    td a {
        color: #ffd700;
        margin: 0 5px;
        transition: color 0.3s;
    }

    td a:hover {
        color: #ff4500;
    }

    @media (max-width: 768px) {
        .table-container {
            margin: 10px;
            padding: 10px;
        }

        th, td {
            font-size: 14px;
        }

        h2, a {
            font-size: 16px;
            margin-left: 10px;
        }
    }

    /* Modal Popup */
    .popup-overlay {
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.6);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 999;
    }

    .popup {
        background-color: #1e1e1e;
        border: 2px solid #ffd700;
        padding: 20px;
        border-radius: 10px;
        width: 90%;
        max-width: 500px;
        color: #ffffff;
        overflow-y: auto;
        max-height: 90vh;
    }

    /* Styling untuk semua label agar konsisten dan rapi */
    .popup label {
        display: block;
        margin-top: 12px;
        font-weight: bold;
        color: #ffd700;
    }

    .popup input[type="date"],
    .popup input[type="number"],
    .popup input[type="text"] {
        width: 100%;
        padding: 10px;
        margin-top: 6px;
        background-color: #2f2f2f;
        border: 1px solid #ccc;
        border-radius: 5px;
        color: #ffffff;
        box-sizing: border-box;
    }

    .popup button {
        background-color: #ffd700;
        color: #1e1e1e;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
        margin-top: 20px;
        margin-right: 10px;
    }

    .popup button:hover {
        background-color: #ff4500;
        color: white;
    }

    .popup a button {
        background-color: #555555;
        color: white;
        margin-top: 20px;
    }

    .popup a button:hover {
        background-color: #ff4500;
    }
</style>

<?php if ($showForm): ?>
<!-- MODAL FORM -->
<div class="popup-overlay">
    <div class="popup">
        <h2><?= $edit ? 'Edit' : 'Tambah' ?> Data Bahan</h2>
        <form method="post" action="">
            <?php if ($edit): ?>
                <input type="hidden" name="edit" value="<?= $data['No'] ?>">
            <?php else: ?>
                <label for="no">No:</label>
                <input type="text" name="no" id="no" required>
            <?php endif; ?>

            <label for="tanggal">Tanggal:</label>
            <input type="date" name="tanggal" id="tanggal" required value="<?= $data['tanggal'] ?>">

            <?php
            $bahan = ['ayam_pecel','ayam_suwir','lele','mie','udang','bumbu_basah','bumbu_kering','pisang','tempe','kecap','minyak'];
            foreach ($bahan as $b) {
                $labelText = ucfirst(str_replace('_', ' ', $b));
                $val = isset($data[$b]) ? $data[$b] : '';
                echo "<label for='$b'>$labelText:</label>";
                echo "<input type='number' name='$b' id='$b' min='0' step='any' required value='$val'>";
            }
            ?>

            <button type="submit"><?= $edit ? 'Update' : 'Simpan' ?></button>
            <a href="crud_databahan.php" style="text-decoration:none;">
                <button type="button">Batal</button>
            </a>
        </form>
    </div>
</div>
<?php endif; ?>


<!-- TAMPIL DATA -->
<h2>Data Stok Bahan</h2>
<a href="?add=true" style="background-color: #ffd700; padding: 8px 15px; border-radius: 5px;">+ Tambah Baru</a>

<br><br>
<?php
$query = "SELECT * FROM view_bahan_perhari ORDER BY tanggal ASC";
$result = mysqli_query($conn, $query);
$no = 1;
?>
<div class="table-container">
    <table border="1">
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
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?= 'P' . str_pad($no++, 2, '0', STR_PAD_LEFT) ?></td>
                    <td><?= htmlspecialchars($row['tanggal']) ?></td>
                    <td><?= htmlspecialchars($row['ayam_pecel']) ?></td>
                    <td><?= htmlspecialchars($row['ayam_suwir']) ?></td>
                    <td><?= htmlspecialchars($row['lele']) ?></td>
                    <td><?= htmlspecialchars($row['mie']) ?></td>
                    <td><?= htmlspecialchars($row['udang']) ?></td>
                    <td><?= htmlspecialchars($row['bumbu_basah']) ?></td>
                    <td><?= htmlspecialchars($row['bumbu_kering']) ?></td>
                    <td><?= htmlspecialchars($row['pisang']) ?></td>
                    <td><?= htmlspecialchars($row['tempe']) ?></td>
                    <td><?= htmlspecialchars($row['kecap']) ?></td>
                    <td><?= htmlspecialchars($row['minyak']) ?></td>
                    <td>
                        <a href="?edit=<?= $row['No'] ?>">Edit</a> |
                        <a href="?delete=<?= $row['No'] ?>" onclick="return confirm('Yakin ingin hapus data ini?')">Hapus</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
