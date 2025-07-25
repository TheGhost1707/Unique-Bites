<?php
session_start();

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
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user["password"])) {
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["nama"] = $user["nama"];
        header("Location: page/dashboard.php");
        exit;
    } else {
        echo "<script>alert('Username atau password salah!'); window.location.href='index.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Kasir Unique Bites</title>
    <link rel="stylesheet" href="assets/style/css/auth.css">
</head>

<body>
    <div class="auth-container">
        <form action="" method="POST" class="auth-form">
            <div class="logo-container">
                <img src="../img/logo/loading_uniquebites.png" alt="Logo" class="logo">
            </div>
            <h2>Kasir Unique Bites</h2>
            <div class="input-group">
                <input type="text" name="username" required placeholder="Username">
            </div>
            <div class="input-group">
                <input type="password" name="password" required placeholder="Password">
            </div>
            <button type="submit">Login</button>
            <p class="auth-link">Belum punya akun? <a href="register.php">Daftar</a></p>
        </form>
    </div>
</body>

</html>