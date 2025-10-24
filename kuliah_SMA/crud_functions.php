<?php
// Fungsi untuk tabel karyawan
function createDataKaryawan($conn, $id_karyawan, $nama, $jabatan, $email, $no_hp) {
    $query = $conn->prepare("INSERT INTO karyawan (id_karyawan, nama, jabatan, email, no_hp) VALUES (?, ?, ?, ?, ?)");
    $query->bind_param("sssss", $id_karyawan, $nama, $jabatan, $email, $no_hp);
    return $query->execute();
}

function readDataKaryawan($conn) {
    $query = $conn->prepare("SELECT * FROM karyawan");
    $query->execute();
    return $query->get_result();
}

function getDataKaryawanById($conn, $id) {
    $sql = "SELECT * FROM karyawan WHERE id_karyawan = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function updateDataKaryawan($conn, $id, $nama, $jabatan, $email, $no_hp) {
    $sql = "UPDATE karyawan SET nama = ?, jabatan = ?, email = ?, no_hp = ? WHERE id_karyawan = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $nama, $jabatan, $email, $no_hp, $id);
    return $stmt->execute();
}

function deleteDataKaryawan($conn, $id_karyawan) {
    $query = $conn->prepare("DELETE FROM karyawan WHERE id_karyawan = ?");
    $query->bind_param("s", $id_karyawan);
    return $query->execute();
}

// Fungsi untuk tabel stok_bahan
function createDataStokBahan($conn, $kode, $nama_jenis) {
    $query = $conn->prepare("INSERT INTO stok_bahan (kode, nama_jenis) VALUES (?, ?)");
    $query->bind_param("ss", $kode, $nama_jenis);
    return $query->execute();
}

function readDataStokBahan($conn) {
    $query = $conn->prepare("SELECT * FROM stok_bahan");
    $query->execute();
    return $query->get_result();
}

function getDataStokBahanById($conn, $kode) {
    $query = "SELECT * FROM stok_bahan WHERE kode = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $kode);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}


function updateDataStokBahan($conn, $kode, $nama_jenis) {
    $sql = "UPDATE stok_bahan SET nama_jenis = ? WHERE kode = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $nama_jenis, $kode);
    return $stmt->execute();
}

function deleteDataStokBahan($conn, $kode) {
    $query = $conn->prepare("DELETE FROM stok_bahan WHERE kode = ?");
    $query->bind_param("s", $kode);
    return $query->execute();
}

// Fungsi untuk tabel data_makanan
function createDataMakanan($conn, $No, $tanggal, $ayam_pecel, $ayam_suwir, $lele, $mie, $udang, $bumbu_basah, $bumbu_kering, $pisang, $tempe, $kecap, $minyak) {
    // Query SQL untuk menyisipkan data ke tabel makanan
    $query = $conn->prepare("
        INSERT INTO makanan (No, tanggal, ayam_pecel, ayam_suwir, lele, mie, udang, bumbu_basah, bumbu_kering, pisang, tempe, kecap, minyak) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    // Bind parameter sesuai dengan tipe data (s = string, i = integer, dll.)
    $query->bind_param(
        "ssiiiiiiiiiii", 
        $No,            
        $tanggal,       
        $ayam_pecel,    
        $ayam_suwir,    
        $lele,          
        $mie,           
        $udang,         
        $bumbu_basah,   
        $bumbu_kering,  
        $pisang,        
        $tempe,         
        $kecap,         
        $minyak         
    );

    // Eksekusi query dan kembalikan hasil
    return $query->execute();
}


function readDataMakanan($conn) {
    $query = $conn->prepare("SELECT * FROM makanan");
    $query->execute();
    return $query->get_result();
}

function getDataMakananById($conn, $No) {
    $sql = "SELECT * FROM makanan WHERE No = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $No);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function updateDataMakanan($conn, $No, $tanggal, $ayam_pecel, $ayam_suwir, $lele, $mie, $udang, $bumbu_basah, $bumbu_kering, $pisang, $tempe, $kecap, $minyak) {
    $sql = "UPDATE makanan SET tanggal = ?, ayam_pecel = ?, ayam_suwir = ?, lele = ?, mie = ?, udang = ?, bumbu_basah = ?, bumbu_kering = ?, pisang = ?, tempe = ?, kecap = ?, minyak = ? WHERE no = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siiiiiiiiiiii", $tanggal, $ayam_pecel, $ayam_suwir, $lele, $mie, $udang, $bumbu_basah, $bumbu_kering, $pisang, $tempe, $kecap, $minyak, $no);
    return $stmt->execute();
}

function deleteDataMakanan($conn, $No) {
    $query = $conn->prepare("DELETE FROM makanan WHERE No = ?");
    $query->bind_param("i", $No);
    return $query->execute();
}
?>
