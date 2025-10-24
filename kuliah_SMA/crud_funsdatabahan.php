<?php
function createData($conn, $ayam_pecel, $ayam_suwir, $lele, $mie, $udang, $bumbu_basah, $bumbu_kering, $pisang, $tempe, $kecap, $minyak) {
    $query = $conn->prepare("INSERT INTO stok_bahan (ayam_pecel, ayam_suwir, lele, mie, udang, bumbu_basah, bumbu_kering, pisang, tempe, kecap, minyak) 
                             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $query->bind_param("ddddddddddd", $ayam_pecel, $ayam_suwir, $lele, $mie, $udang, $bumbu_basah, $bumbu_kering, $pisang, $tempe, $kecap, $minyak);
    return $query->execute();
}

function readData($conn) {
    $query = $conn->prepare("SELECT * FROM stok_bahan");
    $query->execute();
    return $query->get_result();
}

function getDataById($conn, $id) {
    $query = $conn->prepare("SELECT * FROM stok_bahan WHERE id_bahan = ?");
    $query->bind_param("i", $id);
    $query->execute();
    return $query->get_result()->fetch_assoc();
}

function updateData($conn, $id, $ayam_pecel, $ayam_suwir, $lele, $mie, $udang, $bumbu_basah, $bumbu_kering, $pisang, $tempe, $kecap, $minyak) {
    $query = $conn->prepare("UPDATE stok_bahan 
                             SET ayam_pecel = ?, ayam_suwir = ?, lele = ?, mie = ?, udang = ?, bumbu_basah = ?, bumbu_kering = ?, pisang = ?, tempe = ?, kecap = ?, minyak = ? 
                             WHERE id_bahan = ?");
    $query->bind_param("dddddddddddi", $ayam_pecel, $ayam_suwir, $lele, $mie, $udang, $bumbu_basah, $bumbu_kering, $pisang, $tempe, $kecap, $minyak, $id);
    return $query->execute();
}

function deleteData($conn, $id) {
    $query = $conn->prepare("DELETE FROM stok_bahan WHERE id_bahan = ?");
    $query->bind_param("i", $id);
    return $query->execute();
}
?>
