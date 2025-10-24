<?php 
include 'header_pimpinan.php';

// Daftar gambar menu
$menu_images = [
    ["src" => "images/logo.jpg", "title" => "Mie Jawa Special", "desc" => "Lezat dan penuh cita rasa khas"],
    ["src" => "images/mie_godog.jpg", "title" => "Mie Godog", "desc" => "Mie rebus khas Jawa yang menggoda"],
    ["src" => "images/nasi_goreng_jawa.jpg", "title" => "Nasi Goreng Jawa", "desc" => "Nasi goreng dengan rempah khas"]
];

// Menentukan indeks gambar yang akan ditampilkan
if (!isset($_SESSION['slide_index'])) {
    $_SESSION['slide_index'] = 0;
} else {
    $_SESSION['slide_index'] = ($_SESSION['slide_index'] + 1) % count($menu_images);
}

// Ambil data gambar saat ini
$current_image = $menu_images[$_SESSION['slide_index']];

?>

<!-- Auto-refresh halaman setiap 3 detik -->
<meta http-equiv="refresh" content="3">

<style>
    .slider-container {
        width: 100%;
        max-width: 600px;
        margin: 20px auto;
        text-align: center;
        border-radius: 10px;
        padding: 15px;
        background: #FAE3C6;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    .slider-image {
        width: 100%;
        height: auto;
        border-radius: 10px;
    }

    h3 {
        font-size: 20px;
        margin-top: 10px;
        color: #8B4513;
    }

    p {
        font-size: 14px;
        color: #6A3400;
    }
</style>

<div class="content">
    <h2>Selamat Datang di Sistem Informasi Resto Mie Jawa Anglo</h2>
    <p>Selamat datang di halaman utama Sistem Informasi Resto Mie Jawa Anglo.</p>

    <!-- SLIDER IMAGE TANPA JAVASCRIPT -->
    <div class="slider-container">
        <img src="<?php echo $current_image['src']; ?>" alt="<?php echo $current_image['title']; ?>" class="slider-image">
        <h3><?php echo $current_image['title']; ?></h3>
        <p><?php echo $current_image['desc']; ?></p>
    </div>
</div>

<?php include 'footer_pimpinan.php'; ?>
