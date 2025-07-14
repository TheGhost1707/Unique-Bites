<?php
session_start();

$host = "localhost";
$dbname = "uniquebites_kasir"; // ganti nama database sesuai punya Master
$user = "root"; // ganti user db kalau perlu
$pass = ""; // ganti password db kalau perlu

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user["password"])) {
        // Login sukses
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $user["username"];
        header("Location: dashboard.php"); // arahkan ke dashboard admin
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
    <title>Login - Admin Panel</title>
    <link rel="stylesheet" href="assets/style/css/style.css">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600&display=swap" rel="stylesheet">
</head>

<body>
    <div class="login-container">
        <form action="" method="POST" class="login-form">
            <div class="logo-container">
                <img src="../img/logo/loading_uniquebites.png" alt="Logo Admin" class="logo">
            </div>
            <h2>Kasir<br>Unique Bites</h2>
            <div class="input-group">
                <input type="text" name="username" required placeholder="Username">
            </div>
            <div class="input-group">
                <input type="password" name="password" required placeholder="Password">
            </div>
            <button type="submit">Login</button>
        </form>
    </div>
</body>

</html>