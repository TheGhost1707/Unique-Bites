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

    // Tanggal hari ini
    $today = date('Y-m-d');

    // Total penjualan hari ini
    $stmt = $pdo->prepare("SELECT SUM(subtotal) as total_hari_ini FROM detail_transaksi WHERE DATE(tanggal) = ?");
    $stmt->execute([$today]);
    $totalHariIni = $stmt->fetch(PDO::FETCH_ASSOC)['total_hari_ini'] ?? 0;

    // Jumlah transaksi hari ini (dihitung dari id_transaksi unik)
    $stmt = $pdo->prepare("SELECT COUNT(DISTINCT id_transaksi) as jumlah_transaksi FROM detail_transaksi WHERE DATE(tanggal) = ?");
    $stmt->execute([$today]);
    $jumlahTransaksi = $stmt->fetch(PDO::FETCH_ASSOC)['jumlah_transaksi'] ?? 0;

    // Total produk terjual hari ini
    $stmt = $pdo->prepare("SELECT SUM(qty) as produk_terjual FROM detail_transaksi WHERE DATE(tanggal) = ?");
    $stmt->execute([$today]);
    $produkTerjual = $stmt->fetch(PDO::FETCH_ASSOC)['produk_terjual'] ?? 0;

    // Ambil daftar transaksi terbaru (group by id_transaksi)
    $stmt = $pdo->prepare("SELECT id_transaksi, tanggal, kasir, SUM(subtotal) as total 
        FROM detail_transaksi 
        GROUP BY id_transaksi 
        ORDER BY tanggal DESC 
        LIMIT 5");
    $stmt->execute();
    $daftarTransaksi = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kasir - Olsera Style</title>
    <link rel="stylesheet" href="../assets/style/css/style.css">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600&display=swap" rel="stylesheet">
</head>

<body>
    <div class="sidebar">
        <div class="logo-dashboard">
            <img src="../../img/logo/loading_uniquebites.png" alt="Logo">
            <h2>Uniquebites Kasir</h2>
        </div>
        <ul class="menu">
            <li class="active"><a href="dashboard.php">Dashboard</a></li>
            <li><a href="penjualan.php">Penjualan</a></li>
            <li><a href="produk.php">Produk</a></li>
            <li><a href="laporan.php">Laporan</a></li>
            <li><a href="#">Pengaturan</a></li>
        </ul>
    </div>
    <div class="main">
        <div class="topbar">
            <h1>Dashboard</h1>
            <div class="user-info">
                <span>Halo, <?= htmlspecialchars($_SESSION["nama"]) ?></span>
            </div>
        </div>
        <div class="content">
            <div class="cards">
                <div class="card">
                    <h3>Penjualan Hari Ini</h3>
                    <p>Rp <?= number_format($totalHariIni, 0, ',', '.') ?></p>
                </div>
                <div class="card">
                    <h3>Transaksi</h3>
                    <p><?= $jumlahTransaksi ?></p>
                </div>
                <div class="card">
                    <h3>Produk Terjual</h3>
                    <p><?= $produkTerjual ?></p>
                </div>
            </div>
            <div class="table-section">
                <h2>Daftar Transaksi Terbaru</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tanggal</th>
                            <th>Kasir</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($daftarTransaksi)) : ?>
                            <?php foreach ($daftarTransaksi as $t) : ?>
                                <tr>
                                    <td>#<?= htmlspecialchars($t['id_transaksi']) ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($t['tanggal'])) ?></td>
                                    <td><?= htmlspecialchars($t['kasir']) ?></td>
                                    <td>Rp <?= number_format($t['total'], 0, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="5" style="text-align:center; color:#777;">Belum ada transaksi</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>