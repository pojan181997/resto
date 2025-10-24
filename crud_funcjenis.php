<?php
function createData($conn, $kode, $nama_jenis) {
    $query = $conn->prepare("INSERT INTO stok_bahan (kode, nama_jenis) VALUES (?, ?)");
    $query->bind_param("ss", $kode, $nama_jenis);
    return $query->execute();
}

function readData($conn) {
    $query = $conn->prepare("SELECT * FROM stok_bahan");
    $query->execute();
    return $query->get_result();
}

function getDataById($conn, $kode) {
    $query = $conn->prepare("SELECT * FROM stok_bahan WHERE kode = ?");
    $query->bind_param("s", $kode);
    $query->execute();
    return $query->get_result()->fetch_assoc();
}

function updateData($conn, $kode, $nama_jenis) {
    $query = $conn->prepare("UPDATE stok_bahan SET nama_jenis = ? WHERE kode = ?");
    $query->bind_param("ss", $nama_jenis, $kode);
    return $query->execute();
}

function deleteData($conn, $kode) {
    $query = $conn->prepare("DELETE FROM stok_bahan WHERE kode = ?");
    $query->bind_param("s", $kode);
    return $query->execute();
}
?>
