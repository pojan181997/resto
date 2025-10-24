<?php include 'header.php'; ?>
<?php
include 'config.php';

// === Logic Add ===
if (isset($_POST['add'])) {
    $kode = $_POST['kode'];
    $nama_jenis = $_POST['nama_jenis'];
    $query = "INSERT INTO stok_bahan (kode, nama_jenis) VALUES ('$kode', '$nama_jenis')";
    if ($conn->query($query) === TRUE) {
        header('Location: crud_jenisdata.php');
        exit();
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }
}

// === Logic Edit ===
if (isset($_POST['edit'])) {
    $kode = $_POST['kode'];
    $nama_jenis = $_POST['nama_jenis'];
    $query = "UPDATE stok_bahan SET nama_jenis='$nama_jenis' WHERE kode='$kode'";
    if ($conn->query($query) === TRUE) {
        header('Location: crud_jenisdata.php');
        exit();
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }
}

// === Logic Delete ===
if (isset($_GET['delete'])) {
    $kode = $_GET['delete'];
    $query = "DELETE FROM stok_bahan WHERE kode='$kode'";
    if ($conn->query($query) === TRUE) {
        echo "<script>alert('Data berhasil dihapus'); window.location.href='crud_jenisdata.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data');</script>";
    }
}
?>

<style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #000;
    color: rgb(255, 5, 5);
}

h2 {
    text-align: left;
    color: #black;
    margin-left: 20px;
}

a {
    text-decoration: none;
    color: #ffd700;
    margin-left: 20px;
    font-weight: bold;
}

a:hover {
    color: #fff;
}

table {
    width: 90%;
    margin: 20px auto;
    border-collapse: collapse;
    background-color: #111;
    color: #fff;
    box-shadow: 0px 4px 10px rgb(255, 255, 255);
}

table, th, td {
    border: 1px solid #ffd700;
}

th {
    background-color: #222;
    color: #ffd700;
    padding: 10px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

td {
    padding: 10px;
    text-align: center;
}

td a {
    color: #ffd700;
    margin: 0 5px;
    transition: color 0.3s ease;
}

td a:hover {
    color: #fff;
}

/* Popup Overlay */
.overlay {
    visibility: hidden;
    opacity: 0;
    position: fixed;
    top: 0; left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.8);
    display: flex;
    justify-content: center;
    align-items: center;
    transition: visibility 0s, opacity 0.5s;
    z-index: 9999;
}

.overlay:target {
    visibility: visible;
    opacity: 1;
}

.popup {
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    width: 400px;
    color: #000;
    text-align: center;
    position: relative;
}

.popup input {
    width: 90%;
    margin: 10px 0;
    padding: 10px;
}

.popup button {
    margin-top: 10px;
    width: 95%;
    color: black;
    background: #ffd700;
    border: none;
    padding: 10px;
    border-radius: 5px;
    font-weight: bold;
    cursor: pointer;
}

.popup .close {
    position: absolute;
    top: 10px; right: 15px;
    text-decoration: none;
    font-size: 24px;
    color: #000;
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

@media (max-width: 768px) {
    table {
        width: 100%;
    }

    h2 {
        text-align: center;
        margin: 10px 0;
    }

    a {
        margin-left: 0;
        text-align: center;
        display: block;
    }

    th, td {
        padding: 8px;
        font-size: 14px;
    }
}
</style>

<div>
    <h2>Data Jenis Stok Bahan</h2>
    <a href="#overlay-add"><button>Tambah Data</button></a>
    <table>
        <thead>
            <tr>
                <th>Kode</th>
                <th>Jenis Data</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT * FROM stok_bahan";
            $result = $conn->query($query);
            while ($row = $result->fetch_assoc()):
            ?>
            <tr>
                <td><?= htmlspecialchars($row['kode']); ?></td>
                <td><?= htmlspecialchars($row['nama_jenis']); ?></td>
                <td>
                    <a href="#overlay-edit<?= $row['kode']; ?>">Edit</a>
                    <a href="crud_jenisdata.php?delete=<?= urlencode($row['kode']); ?>" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                </td>
            </tr>

            <!-- Popup Edit -->
            <div id="overlay-edit<?= $row['kode']; ?>" class="overlay">
                <div class="popup">
                    <a href="#" class="close">&times;</a>
                    <h2>Edit Jenis Data</h2>
                    <form action="crud_jenisdata.php" method="POST">
                        <input type="hidden" name="kode" value="<?= htmlspecialchars($row['kode']); ?>">
                        <input type="text" name="nama_jenis" value="<?= htmlspecialchars($row['nama_jenis']); ?>" required>
                        <button type="submit" name="edit">Update</button>
                    </form>
                </div>
            </div>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Popup Add -->
<div id="overlay-add" class="overlay">
    <div class="popup">
        <a href="#" class="close">&times;</a>
        <h2>Tambah Jenis Data</h2>
        <form action="crud_jenisdata.php" method="POST">
            <input type="text" name="kode" placeholder="Kode" required>
            <input type="text" name="nama_jenis" placeholder="Jenis Data" required>
            <button type="submit" name="add">Simpan</button>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>
