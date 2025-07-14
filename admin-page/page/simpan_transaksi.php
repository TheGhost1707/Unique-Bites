<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION["nama"])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
if (!$data || empty($data['cart'])) {
    echo json_encode(['success' => false, 'message' => 'No data']);
    exit;
}

$host = "localhost";
$dbname = "uniquebites_kasir";
$user = "root";
$pass = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $id_transaksi = uniqid('TRX'); // ID unik
    $tanggal = date('Y-m-d H:i:s');
    $kasir = $_SESSION["nama"];

    $stmt = $pdo->prepare("INSERT INTO detail_transaksi 
    (id_transaksi, tanggal, nama_produk, harga, qty, subtotal, kasir)
    VALUES (:id_transaksi, :tanggal, :nama_produk, :harga, :qty, :subtotal, :kasir)");

    foreach ($data['cart'] as $item) {
        $subtotal = $item['harga'] * $item['qty'];
        $stmt->execute([
            ':id_transaksi' => $id_transaksi,
            ':tanggal' => $tanggal,
            ':nama_produk' => $item['nama'],
            ':harga' => $item['harga'],
            ':qty' => $item['qty'],
            ':subtotal' => $subtotal,
            ':kasir' => $kasir
        ]);
    }

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
