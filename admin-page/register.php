<?php
$host = "localhost";
$dbname = "uniquebites_kasir";
$user = "root";
$pass = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = trim($_POST["nama"]);
    $username = trim($_POST["username"]);
    $password = $_POST["password"];
    $hashed = password_hash($password, PASSWORD_DEFAULT);

    // Cek apakah username sudah ada
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
        echo "<script>alert('Username sudah terdaftar!'); window.location.href='register.php';</script>";
    } else {
        $insert = $pdo->prepare("INSERT INTO users (nama, username, password) VALUES (?, ?, ?)");
        if ($insert->execute([$nama, $username, $hashed])) {
            echo "<script>alert('Pendaftaran berhasil! Silakan login.'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Terjadi kesalahan!'); window.location.href='register.php';</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Kasir Unique Bites</title>
    <link rel="stylesheet" href="assets/style/css/auth.css">
</head>

<body>
    <div class="auth-container">
        <form action="" method="POST" class="auth-form">
            <div class="logo-container">
                <img src="../img/logo/loading_uniquebites.png" alt="Logo" class="logo">
            </div>
            <h2>Daftar Unique Bites</h2>
            <div class="input-group">
                <input type="text" name="nama" required placeholder="Nama Lengkap">
            </div>
            <div class="input-group">
                <input type="text" name="username" required placeholder="Username">
            </div>
            <div class="input-group">
                <input type="password" name="password" required placeholder="Password">
            </div>
            <button type="submit">Register</button>
            <p class="auth-link">Sudah punya akun? <a href="index.php">Login</a></p>
        </form>
    </div>
</body>

</html>