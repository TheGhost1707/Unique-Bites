<?php
session_start();
if (!isset($_SESSION["nama"])) {
    header("Location: ../index.php");
    exit;
}

$host = "localhost";
$dbname = "uniquebites_kasir";
$user = "root";
$pass = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_GET['id'])) {
        $id_transaksi = $_GET['id'];

        // Hapus data berdasarkan id_transaksi
        $stmt = $pdo->prepare("DELETE FROM detail_transaksi WHERE id_transaksi = ?");
        $stmt->execute([$id_transaksi]);

        echo "<script>alert('Transaksi berhasil dihapus!'); window.location.href='dashboard.php';</script>";
    } else {
        header("Location: dashboard.php");
        exit;
    }
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
