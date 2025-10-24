<?php
include 'header.php';
include 'config.php';



// Insert Data
if (isset($_POST['add'])) {
    $id_karyawan = $_POST['id_karyawan'];
    $nama = $_POST['nama'];
    $jabatan = $_POST['jabatan'];
    $email = $_POST['email'];
    $no_hp = $_POST['no_hp'];

    $query = "INSERT INTO karyawan (id_karyawan, nama, jabatan, email, no_hp) VALUES ('$id_karyawan','$nama', '$jabatan', '$email', '$no_hp')";
    if ($conn->query($query) === TRUE) {
        header('Location: crud.php');
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

// Update Data
if (isset($_POST['edit'])) {
    $id = $_POST['id_karyawan'];
    $nama = $_POST['nama'];
    $jabatan = $_POST['jabatan'];
    $email = $_POST['email'];
    $no_hp = $_POST['no_hp'];

    $query = "UPDATE karyawan SET nama='$nama', jabatan='$jabatan', email='$email', no_hp='$no_hp' WHERE id_karyawan='$id'";
    if ($conn->query($query) === TRUE) {
        header('Location: crud.php');
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

// Delete Data  
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $query = "DELETE FROM karyawan WHERE id_karyawan='$id'";
    if ($conn->query($query) === TRUE) {
        header('Location: crud.php');
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>CRUD Karyawan</title>
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
            visibility: hidden;
            opacity: 0;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            transition: 0.3s;
            z-index: 9999;
        }
        .overlay:target {
            visibility: visible;
            opacity: 1;
        }
        .popup {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            width: 400px;
            color: #000;
            position: relative;
        }
        .popup input {
            width: 90%;
            margin: 10px 0;
            padding: 10px;
            display: block;
        }
        .popup button {
    margin-top: 10px;
    width: 95%;
    color: black; /* warna teksnya hitam */
}

        .close {
            position: absolute;
            top: 10px; right: 15px;
            font-size: 24px;
            color: #000;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <h2>Data Karyawan</h2>
    <a href="#overlay-add"><button>Tambah Data</button></a>

    <div class="container">
        <table>
            <thead>
                <tr>
                    <th>Id karyawan</th>
                    <th>Nama</th>
                    <th>Jabatan</th>
                    <th>Email</th>
                    <th>No HP</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $DataKaryawan = readDataKaryawan($conn);
                $no = 1;
                while ($row = $DataKaryawan->fetch_assoc()):
                ?>
                <tr>
                    <td><?= htmlspecialchars($row['id_karyawan']); ?></td>
                    <td><?= htmlspecialchars($row['nama']); ?></td>
                    <td><?= htmlspecialchars($row['jabatan']); ?></td>
                    <td><?= htmlspecialchars($row['email']); ?></td>
                    <td><?= htmlspecialchars($row['no_hp']); ?></td>
                    <td>
                        <a href="#overlay-edit<?= $row['id_karyawan']; ?>">Edit</a> | 
                        <a href="crud.php?delete=<?= $row['id_karyawan']; ?>" onclick="return confirm('Yakin mau hapus?')">Hapus</a>
                    </td>
                </tr>

                <!-- Popup Edit untuk masing-masing data -->
                <div id="overlay-edit<?= $row['id_karyawan']; ?>" class="overlay">
                    <div class="popup">
                        <a href="#" class="close">&times;</a>
                        <h2>Edit Karyawan</h2>
                        <form action="crud.php" method="POST">
                            <input type="text" name="id_karyawan" value="<?= htmlspecialchars($row['id_karyawan']); ?>" required>
                            <input type="text" name="nama" value="<?= htmlspecialchars($row['nama']); ?>" required>
                            <input type="text" name="jabatan" value="<?= htmlspecialchars($row['jabatan']); ?>" required>
                            <input type="email" name="email" value="<?= htmlspecialchars($row['email']); ?>" required>
                            <input type="text" name="no_hp" value="<?= htmlspecialchars($row['no_hp']); ?>" required>
                            <button type="submit" name="edit">Update</button>
                        </form>
                    </div>
                </div>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Popup Tambah -->
    <div id="overlay-add" class="overlay">
        <div class="popup">
            <a href="#" class="close">&times;</a>
            <h2>Tambah Karyawan</h2>
            <form action="crud.php" method="POST">
                <input type="text" name="id_karyawan" placeholder="id_karyawan" required>
                <input type="text" name="nama" placeholder="Nama" required>
                <input type="text" name="jabatan" placeholder="Jabatan" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="text" name="no_hp" placeholder="No HP" required>
                <button type="submit" name="add">Simpan</button>
            </form>
        </div>
    </div>

</body>
</html>

<?php include 'footer.php'; ?>
