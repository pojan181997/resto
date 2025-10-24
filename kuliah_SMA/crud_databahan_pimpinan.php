<?php include 'header_pimpinan.php'; ?>
<?php
include 'config.php';

// Ambil data untuk edit
$No = $tanggal = $ayam_pecel = $ayam_suwir = $lele = $mie = $udang = $bumbu_basah = $bumbu_kering = $pisang = $tempe = $kecap = $minyak = "";

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = $conn->query("SELECT * FROM makanan WHERE No='$id'");
    if ($result && $result->num_rows > 0) {
        $data = $result->fetch_assoc();
        $No = $data['No'];
        $tanggal = $data['tanggal'];
        $ayam_pecel = $data['ayam_pecel'];
        $ayam_suwir = $data['ayam_suwir'];
        $lele = $data['lele'];
        $mie = $data['mie'];
        $udang = $data['udang'];
        $bumbu_basah = $data['bumbu_basah'];
        $bumbu_kering = $data['bumbu_kering'];
        $pisang = $data['pisang'];
        $tempe = $data['tempe'];
        $kecap = $data['kecap'];
        $minyak = $data['minyak'];
    }
}

// Insert Data
if (isset($_POST['add'])) {
    $No = $_POST['No'];
    $tanggal = $_POST['tanggal'];
    $ayam_pecel = $_POST['ayam_pecel'];
    $ayam_suwir = $_POST['ayam_suwir'];
    $lele = $_POST['lele'];
    $mie = $_POST['mie'];
    $udang = $_POST['udang'];
    $bumbu_basah = $_POST['bumbu_basah'];
    $bumbu_kering = $_POST['bumbu_kering'];
    $pisang = $_POST['pisang'];
    $tempe = $_POST['tempe'];
    $kecap = $_POST['kecap'];
    $minyak = $_POST['minyak'];

    $query = "INSERT INTO makanan (No, tanggal, ayam_pecel, ayam_suwir, lele, mie, udang, bumbu_basah, bumbu_kering, pisang, tempe, kecap, minyak)
              VALUES ('$No', '$tanggal', '$ayam_pecel', '$ayam_suwir', '$lele', '$mie', '$udang', '$bumbu_basah', '$bumbu_kering', '$pisang', '$tempe', '$kecap', '$minyak')";

    if ($conn->query($query) === TRUE) {
        header('Location: crud_databahan.php');
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

// Update Data
if (isset($_POST['edit'])) {
    $No = $_POST['No'];
    $tanggal = $_POST['tanggal'];
    $ayam_pecel = $_POST['ayam_pecel'];
    $ayam_suwir = $_POST['ayam_suwir'];
    $lele = $_POST['lele'];
    $mie = $_POST['mie'];
    $udang = $_POST['udang'];
    $bumbu_basah = $_POST['bumbu_basah'];
    $bumbu_kering = $_POST['bumbu_kering'];
    $pisang = $_POST['pisang'];
    $tempe = $_POST['tempe'];
    $kecap = $_POST['kecap'];
    $minyak = $_POST['minyak'];

    $query = "UPDATE makanan SET 
                tanggal='$tanggal', 
                ayam_pecel='$ayam_pecel', 
                ayam_suwir='$ayam_suwir', 
                lele='$lele', 
                mie='$mie', 
                udang='$udang', 
                bumbu_basah='$bumbu_basah', 
                bumbu_kering='$bumbu_kering', 
                pisang='$pisang', 
                tempe='$tempe', 
                kecap='$kecap', 
                minyak='$minyak' 
              WHERE No='$No'";

    if ($conn->query($query) === TRUE) {
        header('Location: crud_databahan.php');
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

// Delete Data
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $query = "DELETE FROM makanan WHERE No='$id'";
    if ($conn->query($query) === TRUE) {
        header('Location: crud_databahan.php');
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
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
        text-align: left;
        color:rgb(0, 0, 0);
        margin-left: 20px;
    }

    a {
        text-decoration: none;
        color:rgb(0, 0, 0);
        margin-left: 20px;
        font-weight: bold;
        transition: color 0.3s;
    }

    a:hover {
        color: #ffffff;
    }

    .table-container {
    background-color: #2f2f2f;
    padding: 20px;
    margin: 20px;
    border-radius: 15px;
    max-height: 400px; /* Batas tinggi, scroll muncul kalau data banyak */
    overflow: auto; /* Scroll ke samping dan bawah */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.4);
}

    table {
        width: 100%;
        border-collapse: collapse;
        color: #ffffff;
        min-width: 1000px; /* agar scroll muncul saat layar kecil */
    }

    th, td {
        border: 1px solid #ffd700;
    }

    th {
        background-color: #ffd700;
        color: #1e1e1e;
        padding: 10px;
    }

    td {
        padding: 10px;
        text-align: center;
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
            padding: 8px;
        }

        h2 {
            font-size: 20px;
            margin-left: 10px;
        }

        a {
            font-size: 14px;
            margin-left: 10px;
        }
    }

    @media (max-width: 480px) {
        h2 {
            font-size: 18px;
            margin-left: 5px;
        }

        table {
            font-size: 12px;
        }

        a {
            font-size: 12px;
            margin-left: 5px;
        }
    }
    /* Popup styling */
    .popup-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 999;
    overflow-y: auto;
    padding: 20px;
}

.popup {
    background: black;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    width: 100%;
    max-width: 400px;
    max-height: 90vh; /* Biar tidak melebihi layar */
    overflow-y: auto;  /* Scroll muncul kalau konten tinggi */
    text-align: center;
}


.popup input {
    width: 100%;
    padding: 8px;
    margin-top: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.popup button {
    margin-top: 10px;
}
.btn-add {
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

.btn-add:hover {
    background-color:rgb(0, 0, 0); /* warna hijau lebih gelap saat hover */
    transform: scale(1.05);     /* efek sedikit membesar */
}

</style>

<div>
    <h2>Data Stok Bahan</h2>
   <a href="laporan_databahan_pimpinan.php" target="_blank" class="btn-add">Cetak PDF</a>


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
            <?php 
            $data = readDataMakanan($conn);
            while ($row = $data->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['No']); ?></td>
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
                <td>
                    <a href="crud_databahan.php?edit=<?= $row['No']; ?>">Edit</a> |
                    <a href="crud_databahan.php?delete=<?= $row['No']; ?>" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    </div>
</div>

<?php if (isset($_GET['add']) || isset($_GET['edit'])): ?>
<div class="popup-overlay">
    <div class="popup">
        <h2><?= isset($_GET['edit']) ? 'Edit Data' : 'Tambah Data'; ?></h2>
        <button onclick="window.location.href='crud_databahan.php'" style="float: right; background: transparent; border: none; font-size: 20px; color: #666;">&times;</button>
        <form method="POST" action="">
            <label>No:</label>
            <input type="text" name="No" value="<?= htmlspecialchars($No); ?>" required <?= isset($_GET['edit']) ? 'readonly' : ''; ?>>

            <label>Tanggal:</label>
            <input type="date" name="tanggal" value="<?= htmlspecialchars($tanggal); ?>" required>

            <label>Ayam Pecel:</label>
            <input type="text" name="ayam_pecel" value="<?= htmlspecialchars($ayam_pecel); ?>" required>

            <label>Ayam Suwir:</label>
            <input type="text" name="ayam_suwir" value="<?= htmlspecialchars($ayam_suwir); ?>" required>

            <label>Lele:</label>
            <input type="text" name="lele" value="<?= htmlspecialchars($lele); ?>" required>

            <label>Mie:</label>
            <input type="text" name="mie" value="<?= htmlspecialchars($mie); ?>" required>

            <label>Udang:</label>
            <input type="text" name="udang" value="<?= htmlspecialchars($udang); ?>" required>

            <label>Bumbu Basah:</label>
            <input type="text" name="bumbu_basah" value="<?= htmlspecialchars($bumbu_basah); ?>" required>

            <label>Bumbu Kering:</label>
            <input type="text" name="bumbu_kering" value="<?= htmlspecialchars($bumbu_kering); ?>" required>

            <label>Pisang:</label>
            <input type="text" name="pisang" value="<?= htmlspecialchars($pisang); ?>" required>

            <label>Tempe:</label>
            <input type="text" name="tempe" value="<?= htmlspecialchars($tempe); ?>" required>

            <label>Kecap:</label>
            <input type="text" name="kecap" value="<?= htmlspecialchars($kecap); ?>" required>

            <label>Minyak:</label>
            <input type="text" name="minyak" value="<?= htmlspecialchars($minyak); ?>" required>

            <button type="submit" name="<?= isset($_GET['edit']) ? 'edit' : 'add'; ?>">Simpan</button>
            <a href="crud_databahan.php" class="btn btn-cancel" style="background:#ccc; color:#000; text-decoration:none; padding:8px 12px; margin-left:10px;">Batal</a>
        </form>
    </div>
</div>
<?php endif; ?>

<?php include 'footer_pimpinan.php'; ?>