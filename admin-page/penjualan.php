<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Penjualan - KasirKu</title>
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
      <li class="active"><a href="#">Penjualan</a></li>
      <li><a href="produk.php">Produk</a></li>
      <li><a href="laporan.php">Laporan</a></li>
      <li><a href="#">Pengaturan</a></li>
    </ul>
  </div>

  <div class="main">
    <div class="topbar">
      <h1>Penjualan</h1>
      <div class="user-info">
        <span>Halo, Master</span>
      </div>
    </div>
    <div class="penjualan-container">
      <div class="product-category-section">
        <h2 class="product-category">Cemilan</h2>
        <div class="product-grid">
          <div class="product-card">
            <img src="assets/images/snack1.png" alt="Keripik">
            <h3>Keripik Kentang</h3>
            <p>Rp 10.000</p>
          </div>
          <div class="product-card">
            <img src="assets/images/snack2.png" alt="Donat">
            <h3>Donat Coklat</h3>
            <p>Rp 8.000</p>
          </div>
        </div>

        <div class="product-category-section">
          <h2 class="product-category">Minuman Non-Kopi</h2>
          <div class="product-grid">
            <div class="product-card">
              <img src="assets/images/drink1.png" alt="Teh Manis">
              <h3>Teh Manis</h3>
              <p>Rp 5.000</p>
            </div>
            <div class="product-card">
              <img src="assets/images/drink2.png" alt="Jus Jeruk">
              <h3>Jus Jeruk</h3>
              <p>Rp 12.000</p>
            </div>
          </div>
        </div>

        <div class="product-category-section">
          <h2 class="product-category">Minuman Kopi</h2>
          <div class="product-grid">
            <div class="product-card">
              <img src="assets/images/coffee1.png" alt="Espresso">
              <h3>Espresso</h3>
              <p>Rp 15.000</p>
            </div>
            <div class="product-card">
              <img src="assets/images/coffee2.png" alt="Cappuccino">
              <h3>Cappuccino</h3>
              <p>Rp 18.000</p>
            </div>
          </div>
        </div>
      </div>
      <div class="cart">
        <h2>Keranjang</h2>
        <ul>
          <li>Teh Manis x2 - Rp 10.000</li>
          <li>Donat Coklat x1 - Rp 8.000</li>
        </ul>
        <div class="total">
          Total: <strong>Rp 18.000</strong>
        </div>
        <button class="btn-pay">Bayar</button>
      </div>
    </div>
  </div>
</body>

</html>