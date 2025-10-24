<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $username = $_POST['reg_username'] ?? '';
    $password = $_POST['reg_password'] ?? '';
    $confirm_password = $_POST['reg_confirm_password'] ?? '';
    $role = $_POST['reg_role'] ?? '';  // Pastikan menangani jika kosong

    if (empty($role)) {
        $error_register = "Silakan pilih peran (role)!";
    } elseif ($password !== $confirm_password) {
        $error_register = "Password dan konfirmasi password tidak cocok!";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $query = $conn->prepare("INSERT INTO tb_users (username, password, role) VALUES (?, ?, ?)");
        $query->bind_param("sss", $username, $hashed_password, $role);

        if ($query->execute()) {
            $success_register = "Registrasi berhasil! Silakan <a href='index.php'>login</a>.";
        } else {
            $error_register = "Gagal registrasi, coba lagi!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Resto Mie Jawa Anglo</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
    /* Menggunakan warna khas Minang dan Jawa */
:root {
    --background-dark: #5C3B1E;
    --background-light: #F4E1C4;
    --primary-color: #5C3B1E;
    --secondary-color: #FFD700;
    --text-light: #FFFFFF;
    --text-dark: #2F2F2F;
}

body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, var(--background-dark), var(--background-light));
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    color: var(--text-light);
}

.container {
    display: flex;
    width: 900px;
    max-width: 100%;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(163, 41, 41, 0.5);
    background: var(--background-light);
}

/* FORM CONTAINER */
.form-container {
    width: 50%;
    padding: 40px;
    background: var(--primary-color);
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.form-container h1 {
    font-size: 28px;
    margin-bottom: 20px;
    color: var(--secondary-color);
}

.form-container label {
    font-size: 14px;
    margin-bottom: 5px;
    color: var(--text-light);
    align-self: flex-start;
}

.form-container input, select {
    width: 100%;
    padding: 12px;
    margin-bottom: 20px;
    border: 2px solid var(--secondary-color);
    border-radius: 5px;
    font-size: 14px;
    background: var(--background-dark);
    color: var(--text-light);
    transition: 0.3s;
}

.form-container input:focus, select:focus {
    border-color: #D4AF37;
    outline: none;
}

.form-container button {
    padding: 12px;
    width: 100%;
    background: var(--secondary-color);
    border: none;
    border-radius: 5px;
    color: var(--text-dark);
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: 0.3s;
}

.form-container button:hover {
    background: #D4AF37;
}

.form-container p {
    font-size: 14px;
    color: var(--text-light);
}

.form-container a {
    color: var(--secondary-color);
    text-decoration: none;
    font-weight: bold;
}

/* IMAGE CONTAINER */
.image-container {
    width: 50%;
    background: var(--background-dark);
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 40px;
    text-align: center;
}

.image-container h2 {
    font-size: 26px;
    color: var(--secondary-color);
    margin-bottom: 10px;
}

.image-container img {
    max-width: 100%;
    height: auto;
    border-radius: 10px;
}

/* Styling untuk select */
select {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border: 2px solid var(--secondary-color);
    border-radius: 8px;
    font-size: 16px;
    font-family: 'Poppins', sans-serif;
    background-color: var(--background-dark);
    color: var(--text-light);
    cursor: pointer;
    appearance: none; /* Menghilangkan gaya default browser */
    -webkit-appearance: none; /* Untuk Safari & Chrome */
    -moz-appearance: none; /* Untuk Firefox */
    transition: all 0.3s ease-in-out;
}

/* Hover effect */
select:hover {
    background-color: var(--primary-color);
    border-color: #D4AF37;
}

/* Fokus pada select */
select:focus {
    border-color: #FFD700;
    outline: none;
    box-shadow: 0px 0px 10px rgba(255, 215, 0, 0.7);
}

/* Styling untuk opsi dalam select */
option {
    font-size: 16px;
    background: var(--background-light);
    color: var(--text-dark);
    padding: 10px;
}



/* RESPONSIVE DESIGN */
@media (max-width: 768px) {
    .container {
        flex-direction: column;
        width: 90%;
    }
    
    .form-container, .image-container {
        width: 100%;
        padding: 20px;
    }
    
    .form-container h1 {
        font-size: 24px;
    }

    .image-container h2 {
        font-size: 22px;
    }
}
</style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h1>Register</h1>

            <?php if (isset($error_register)) : ?>
                <p class="error"><?php echo $error_register; ?></p>
            <?php endif; ?>
            
            <?php if (isset($success_register)) : ?>
                <p class="success"><?php echo $success_register; ?></p>
            <?php endif; ?>

            <form method="POST" action="">
    <label>Username:</label>
    <input type="text" name="reg_username" required>

    <label>Password:</label>
    <input type="password" name="reg_password" required>

    <label>Konfirmasi Password:</label>
    <input type="password" name="reg_confirm_password" required>

    <label>Role:</label>
    <select name="reg_role" required>
        <option value="">Pilih Role</option>
        <option value="admin">Admin</option>
        <option value="pimpinan">Pimpinan</option>
    </select>

    <button type="submit" name="register">Register</button>
</form>


            <p>Sudah punya akun? <a href="index.php">Login di sini</a></p>
        </div>

        <div class="image-container">
            <h2>Resto Mie Jawa Anglo</h2>
            <p>Memberikan layanan terbaik untuk pelanggan</p>
            <img src="assets/logo.png" alt="Resto Mie Jawa Anglo">
        </div>
    </div>
</body>
</html>
