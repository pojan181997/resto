<?php include 'header_pimpinan.php'; ?>
<?php
include 'config.php';

// Delete Data
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query = "DELETE FROM pesanan WHERE id_pesanan ='$id'";
    if ($conn->query($query) === TRUE) {
        header('Location: data_pesanan_pimpinan.php');
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

// Inisialisasi variabel
$id_pesanan = $id_transaksi = $menu = $jumlah = $harga_satuan = $catatan = "";

// Handle request EDIT data
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = $conn->query("SELECT * FROM pesanan WHERE id_pesanan = '$id'");
    if ($result && $result->num_rows > 0) {
        $data = $result->fetch_assoc();
        $id_pesanan = $data['id_pesanan'];
        $id_transaksi = $data['id_transaksi'];
        $menu = $data['menu'];
        $jumlah = $data['jumlah'];
        $harga_satuan = $data['harga_satuan'];
        $catatan = $data['catatan'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pesanan = htmlspecialchars($_POST['id_pesanan']);
    $id_transaksi = htmlspecialchars($_POST['id_transaksi']);
    $menu_array = $_POST['menu']; // ini array
    $jumlah = htmlspecialchars($_POST['jumlah']);
    $catatan = htmlspecialchars($_POST['catatan']);

    // Daftar harga per menu
    $harga_per_menu = [
        'Ayam Goreng' => 15000,
        'Mie Jawa' => 12000,
        'Lele Bakar' => 17000,
        'Nasi Pecel' => 10000,
    ];

    // Hitung harga satuan (pakai menu pertama yang dipilih, misal)
    $menu = implode(", ", $menu_array); // gabung menu jadi satu string
    $harga_satuan = 0;
    foreach ($menu_array as $m) {
        $harga_satuan += $harga_per_menu[$m];
    }

    if (isset($_GET['edit'])) {
        $query = "UPDATE pesanan SET 
                    id_transaksi='$id_transaksi',
                    menu='$menu',
                    jumlah='$jumlah',
                    harga_satuan='$harga_satuan',
                    catatan='$catatan'
                  WHERE id_pesanan='$id_pesanan'";
    } else {
        $query = "INSERT INTO pesanan (id_pesanan, id_transaksi, menu, jumlah, harga_satuan, catatan) 
                  VALUES ('$id_pesanan', '$id_transaksi', '$menu', '$jumlah', '$harga_satuan', '$catatan')";
    }

    if ($conn->query($query) === TRUE) {
        header("Location: data_pesanan_pimpinan.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}


// Ambil semua data untuk ditampilkan
function readDataPesanan($conn) {
    return $conn->query("SELECT * FROM pesanan ORDER BY id_pesanan");
}

$DataPesanan = readDataPesanan($conn);
?>

<!-- Styling tetap sama -->
<style>
   body {
        font-family: Arial, sans-serif;
        margin: 0; padding: 0;
        background-color: #2f2f2f;
        color: #f5f5f5;
    }
    h2, a {
        margin-left: 20px;
        color: black;
        text-decoration: none;
    }
    a:hover {
        color: #ccc;
    }
    .container {
        margin: 20px auto;
        width: 90%;
        max-width: 1200px;
        background: #FAE3C6;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        overflow: hidden;
        padding: 20px;
    }
    table {
        width: 100%;
        margin-top: 20px;
        background: #3e2f20;
        border-collapse: collapse;
        color: #f5f5f5;
    }
    th, td {
        padding: 12px;
        border: 1px solid #ddd;
        text-align: center;
    }
    th {
        background-color: #111;
    }
    td a {
        color: #fff;
        margin: 0 5px;
    }
    td a:hover {
        color: red;
    }
    button {
        padding: 10px 20px;
        background: #D4A76A;
        color: #6A3400;
        border: none;
        font-weight: bold;
        border-radius: 5px;
        cursor: pointer;
        margin-bottom: 10px;
    }
    button:hover {
        background: #C1935B;
    }
    .overlay {
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

.popup {
    background: #fff;
    padding: 30px;
    border-radius: 12px;
    width: 90%;
    max-width: 500px;
    color: #333;
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    position: relative;
}

.popup h2 {
    text-align: center;
    color: #6A3400;
}

.popup form {
    display: flex;
    flex-direction: column;
}

.popup label {
    margin-top: 10px;
    font-weight: bold;
}

.popup input, 
.popup select {
    padding: 10px;
    margin-top: 5px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 14px;
}

.popup select[multiple] {
    height: 120px;
}

.popup button[type="submit"] {
    margin-top: 20px;
    padding: 12px;
    background: #D4A76A;
    color: #fff;
    border: none;
    font-weight: bold;
    border-radius: 6px;
    cursor: pointer;
    font-size: 16px;
}

.popup button[type="submit"]:hover {
    background: #C1935B;
}

.cancel-button {
    display: inline-block;
    margin-top: 10px;
    text-align: center;
    background: #ccc;
    padding: 10px;
    color: #000;
    border-radius: 6px;
    text-decoration: none;
    font-weight: bold;
    transition: background 0.3s;
}

.cancel-button:hover {
    background: #bbb;
}

.close {
    position: absolute;
    top: 10px;
    right: 20px;
    font-size: 28px;
    font-weight: bold;
    color: #666;
    text-decoration: none;
}

.close:hover {
    color: #000;
}
.btn-cetak {
    background-color: rgb(46, 174, 210);
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    font-weight: bold;
    display: inline-block;
    margin-bottom: 20px;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.btn-cetak:hover {
    background-color: rgb(30, 150, 190);
    transform: scale(1.05);
}
</style>

<div>
    <h2>Data Pesanan</h2>
    <a href="cetak_pesanan.php" target="_blank" class="btn-cetak">
    Cetak PDF
</a>

    <div class="table-container">
    <table border="1">
        <thead>
            <tr>
                <th>ID Pesanan</th>
                <th>ID Transaksi</th>
                <th>Menu</th>
                <th>Jumlah</th>
                <th>Harga Satuan</th>
                <th>Catatan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $DataPesanan->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['id_pesanan']); ?></td>
                <td><?= htmlspecialchars($row['id_transaksi']); ?></td>
                <td><?= htmlspecialchars($row['menu']); ?></td>
                <td><?= htmlspecialchars($row['jumlah']); ?></td>
                <td>Rp <?= number_format($row['harga_satuan'], 0, ',', '.'); ?></td>
                <td><?= htmlspecialchars($row['catatan']); ?></td>
                <td>
                    <a href="data_pesanan_pimpinan.php?edit=<?= $row['id_pesanan']; ?>">Edit</a>
                    <a href="data_pesanan_pimpinan.php?delete=<?= $row['id_pesanan']; ?>" onclick="return confirm('Yakin ingin menghapus pesanan ini?')">Hapus</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php if (isset($_GET['add']) || isset($_GET['edit'])): ?>
<div class="overlay" id="popup">
    <div class="popup">
        <a href="data_pesanan_pimpinan.php" class="close">&times;</a>
        <h2 style="margin-top: 0;"><?= isset($_GET['edit']) ? 'Edit Pesanan' : 'Tambah Pesanan'; ?></h2>
        <form method="POST" action="">
            <label>ID Pesanan:</label>
            <input type="text" name="id_pesanan" value="<?= htmlspecialchars($id_pesanan ?? ''); ?>" required>

            <label>ID Transaksi:</label>
            <input type="text" name="id_transaksi" value="<?= htmlspecialchars($id_transaksi ?? ''); ?>" required>

            <label>Menu (Pilih Banyak):</label>
<div class="menu-checkbox-group">
    <?php 
    $menu_list = [
        'Ayam Goreng' => 15000,
        'Mie Jawa' => 12000,
        'Lele Bakar' => 17000,
        'Nasi Pecel' => 10000
    ];
    $menu_selected = explode(', ', $menu ?? '');

    foreach ($menu_list as $menu_name => $harga) {
        $checked = in_array($menu_name, $menu_selected) ? 'checked' : '';
        echo '<div class="menu-item">';
        echo '<input type="checkbox" class="menu-checkbox" name="menu[]" value="'.$menu_name.'" data-harga="'.$harga.'" '.$checked.'>';
        echo '<label>'.$menu_name.' (Rp '.number_format($harga, 0, ',', '.').')</label>';
        echo '</div>';
    }
    ?>
</div>


            <label>Jumlah:</label>
            <input type="number" name="jumlah" value="<?= htmlspecialchars($jumlah ?? ''); ?>" required>

            <label>total harga:</label>
<input type="number" id="harga_satuan" name="harga_satuan" value="<?= htmlspecialchars($harga_satuan ?? ''); ?>" readonly required>


            <label>Catatan:</label>
            <input type="text" name="catatan" value="<?= htmlspecialchars($catatan ?? ''); ?>">

            <button type="submit" name="submit"><?= isset($_GET['edit']) ? 'Update' : 'Simpan'; ?></button>
            <a href="data_pesanan_pimpinan.php" class="cancel-button">Batal</a>
        </form>
    </div>
</div>
<?php endif; ?>


<?php include 'footer_pimpinan.php'; ?>
