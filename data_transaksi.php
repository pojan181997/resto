<?php
include 'config.php';
include 'header.php';

// Inisialisasi variabel
$id_transaksi = $tanggal_transaksi = $menu = $harga_satuan = $metode_pembayaran = $status_pembayaran = "";

// Fungsi ambil daftar pesanan
function getPesananList($conn) {
    return $conn->query("SELECT id_transaksi, menu, harga_satuan FROM pesanan");
}

// Fungsi ambil data pesanan berdasarkan id_transaksi
function getPesananById($conn, $id_transaksi) {
    $result = $conn->query("SELECT menu, harga_satuan FROM pesanan WHERE id_transaksi='$id_transaksi'");
    return $result->fetch_assoc();
}

// Fungsi ambil data transaksi berdasarkan ID
function getDataTransaksi($conn, $id) {
    $result = $conn->query("SELECT * FROM data_transaksi WHERE id_transaksi='$id'");
    return $result->fetch_assoc();
}

// Handle submit Tambah
if (isset($_POST['tambah'])) {
    $id_transaksi = htmlspecialchars($_POST['id_transaksi']);
    $tanggal_transaksi = htmlspecialchars($_POST['tanggal_transaksi']);
    $menu = htmlspecialchars($_POST['menu']);
    $harga_satuan = htmlspecialchars($_POST['harga_satuan']);
    $metode_pembayaran = htmlspecialchars($_POST['metode_pembayaran']);
    $status_pembayaran = htmlspecialchars($_POST['status_pembayaran']);

    $query = "INSERT INTO data_transaksi (id_transaksi, tanggal_transaksi, menu, harga_satuan, metode_pembayaran, status_pembayaran)
              VALUES ('$id_transaksi', '$tanggal_transaksi', '$menu', '$harga_satuan', '$metode_pembayaran', '$status_pembayaran')";
    if ($conn->query($query)) {
        header("Location: data_transaksi.php");
        exit();
    }
}



// Kalau lagi tambah dan user pilih id_transaksi, reload form
if (isset($_POST['id_transaksi']) && isset($_GET['action']) && $_GET['action'] == 'add') {
    $id_transaksi = $_POST['id_transaksi'];
    header("Location: data_transaksi.php?action=add&pilih_id_transaksi=$id_transaksi");
    exit();
}

// Cek auto-fill Menu dan Harga berdasarkan id_transaksi
if (isset($_GET['pilih_id_transaksi'])) {
    $id_transaksi = $_GET['pilih_id_transaksi'];
    $pesanan = getPesananById($conn, $id_transaksi);
    $menu = $pesanan['menu'] ?? '';
    $harga_satuan = $pesanan['harga_satuan'] ?? '';
}

// Ambil semua data transaksi
$dataTransaksi = $conn->query("SELECT * FROM data_transaksi ORDER BY id_transaksi ASC");

// Ambil semua data pesanan
$pesananList = getPesananList($conn);

// Untuk Edit
$editData = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id_transaksi'])) {
    $editData = getDataTransaksi($conn, $_GET['id']);
}
?>

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
        background: rgba(0,0,0,0.7);
        display: flex;
        justify-content: center;
        align-items: center;
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
</style>


    <h2>Data Transaksi</h2>
    <a href="?action=add" class="btn-add"><button>Tambah Transaksi</button></a>
    <div class="container">
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
</div>

<?php if (isset($_GET['action']) && ($_GET['action'] == 'add' || $_GET['action'] == 'edit')): ?>
<div class="overlay">
    <div class="popup">
        <a href="data_transaksi.php" class="close">×</a>
        <h2><?= ($_GET['action'] == 'add') ? 'Tambah' : 'Edit' ?> Transaksi</h2>

        <form method="POST" action="">
            <?php if ($_GET['action'] == 'edit'): ?>
                <input type="hidden" name="id" value="<?= htmlspecialchars($editData['id']) ?>">
            <?php endif; ?>

            <label>ID Transaksi</label>
            <select name="id_transaksi" onchange="this.form.submit()" required>
                <option value="">-- Pilih --</option>
                <?php
                $pesananList2 = getPesananList($conn);
                while ($row = $pesananList2->fetch_assoc()):
                ?>
                    <option value="<?= $row['id_transaksi'] ?>"
                        <?= ($id_transaksi == $row['id_transaksi'] || (isset($editData) && $editData['id_transaksi'] == $row['id_transaksi'])) ? 'selected' : '' ?>>
                        <?= $row['id_transaksi'] ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label>Tanggal Transaksi</label>
            <input type="date" name="tanggal_transaksi" value="<?= $editData['tanggal_transaksi'] ?? date('Y-m-d') ?>" required>

            <label>Menu</label>
            <input type="text" name="menu" value="<?= $menu ?? ($editData['menu'] ?? '') ?>" readonly required>

            <label>Harga Satuan</label>
            <input type="number" name="harga_satuan" value="<?= $harga_satuan ?? ($editData['harga_satuan'] ?? '') ?>" readonly required>

            <label>Metode Pembayaran</label>
            <select name="metode_pembayaran" required>
                <option value="">-- Pilih --</option>
                <option value="Cash" <?= (isset($editData) && $editData['metode_pembayaran'] == 'Cash') ? 'selected' : '' ?>>Cash</option>
                <option value="Transfer" <?= (isset($editData) && $editData['metode_pembayaran'] == 'Transfer') ? 'selected' : '' ?>>Transfer</option>
                <option value="E-Wallet" <?= (isset($editData) && $editData['metode_pembayaran'] == 'E-Wallet') ? 'selected' : '' ?>>E-Wallet</option>
            </select>

            <label>Status Pembayaran</label>
            <select name="status_pembayaran" required>
                <option value="">-- Pilih --</option>
                <option value="Lunas" <?= (isset($editData) && $editData['status_pembayaran'] == 'Lunas') ? 'selected' : '' ?>>Lunas</option>
                <option value="Belum Lunas" <?= (isset($editData) && $editData['status_pembayaran'] == 'Belum Lunas') ? 'selected' : '' ?>>Belum Lunas</option>
            </select>

            <button type="submit" name="<?= ($_GET['action'] == 'add') ? 'tambah' : 'update' ?>">
                <?= ($_GET['action'] == 'add') ? 'Simpan' : 'Update' ?>
            </button>
        </form>

        <a href="data_transaksi.php" class="cancel-button">Batal</a>
    </div>
</div>
<?php endif; ?>

<?php include 'footer.php'; ?>
