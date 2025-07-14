<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kasir - Olsera Style</title>
    <link rel="stylesheet" href="assets/style/css/style.css">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600&display=swap" rel="stylesheet">
</head>

<body>
    <div class="sidebar">
        <div class="logo-dashboard">
            <img src="../img/logo/loading_uniquebites.png" alt="Logo">
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
                <span>Halo, Master</span>
            </div>
        </div>
        <div class="content">
            <div class="cards">
                <div class="card">
                    <h3>Penjualan Hari Ini</h3>
                    <p>Rp 2.500.000</p>
                </div>
                <div class="card">
                    <h3>Transaksi</h3>
                    <p>35</p>
                </div>
                <div class="card">
                    <h3>Produk Terjual</h3>
                    <p>120</p>
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
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#TX001</td>
                            <td>13/07/2025</td>
                            <td>Master</td>
                            <td>Rp 100.000</td>
                            <td>Sukses</td>
                        </tr>
                        <tr>
                            <td>#TX002</td>
                            <td>13/07/2025</td>
                            <td>Master</td>
                            <td>Rp 250.000</td>
                            <td>Sukses</td>
                        </tr>
                        <tr>
                            <td>#TX003</td>
                            <td>13/07/2025</td>
                            <td>Master</td>
                            <td>Rp 500.000</td>
                            <td>Sukses</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>