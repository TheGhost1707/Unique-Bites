<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk - KasirKu</title>
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
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="penjualan.php">Penjualan</a></li>
            <li class="active"><a href="produk.php">Produk</a></li>
            <li><a href="laporan.php">Laporan</a></li>
            <li><a href="#">Pengaturan</a></li>
        </ul>
    </div>

    <div class="main">
        <div class="topbar">
            <h1>Data Produk</h1>
            <div class="user-info">
                <span>Halo, Master</span>
                <img src="assets/images/user.png" alt="User">
            </div>
        </div>

        <div class="content">
            <div class="produk-actions">
                <input type="text" placeholder="Cari produk..." class="search-input">
                <button class="btn-add">+ Tambah Produk</button>
            </div>

            <div class="produk-table-container">
                <table class="produk-table">
                    <thead>
                        <tr>
                            <th>Gambar</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><img src="assets/images/snack1.png" alt="Keripik" class="produk-img"></td>
                            <td>Keripik Kentang</td>
                            <td>Cemilan</td>
                            <td>Rp 10.000</td>
                            <td>
                                <button class="btn-edit">Edit</button>
                                <button class="btn-delete">Hapus</button>
                                <button class="btn-status active">Aktif</button>
                            </td>
                        </tr>
                        <tr>
                            <td><img src="assets/images/coffee1.png" alt="Espresso" class="produk-img"></td>
                            <td>Espresso</td>
                            <td>Minuman Kopi</td>
                            <td>Rp 15.000</td>
                            <td>
                                <button class="btn-edit">Edit</button>
                                <button class="btn-delete">Hapus</button>
                                <button class="btn-status nonaktif">Non-aktif</button>
                            </td>
                        </tr>
                        <!-- Tambahkan produk lain -->
                    </tbody>
                </table>
            </div>
        </div>
</body>

</html>