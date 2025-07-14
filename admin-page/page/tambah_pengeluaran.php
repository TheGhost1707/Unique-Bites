<?php
session_start();
if (!isset($_SESSION["nama"])) {
  header("Location: ../index.php");
  exit;
}

try {
  $pdo = new PDO("mysql:host=localhost;dbname=uniquebites_kasir", "root", "");
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // Validasi sederhana
  if (!empty($_POST['nama_pengeluaran']) && !empty($_POST['total']) && !empty($_POST['tanggal'])) {
    $nama = $_POST['nama_pengeluaran'];
    $total = $_POST['total'];
    $tanggal = $_POST['tanggal'];

    $stmt = $pdo->prepare("INSERT INTO pengeluaran (nama_pengeluaran, total, tanggal) VALUES (?, ?, ?)");
    $stmt->execute([$nama, $total, $tanggal]);
  }

  header("Location: penjualan.php?pesan=sukses");
  exit;
} catch (PDOException $e) {
  die("Koneksi gagal: " . $e->getMessage());
}
