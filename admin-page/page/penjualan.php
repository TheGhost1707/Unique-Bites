<?php
session_start();
if (!isset($_SESSION["nama"])) {
  header("Location: ../index.php");
  exit;
}

// Koneksi ke database
try {
  $pdo = new PDO("mysql:host=localhost;dbname=uniquebites_kasir", "root", "");
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die("Koneksi gagal: " . $e->getMessage());
}

// Ambil data produk per kategori
function getProdukByKategori($pdo, $kategori)
{
  $stmt = $pdo->prepare("SELECT * FROM produk WHERE kategori = ?");
  $stmt->execute([$kategori]);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$cemilan = getProdukByKategori($pdo, 'Cemilan');
$nonkopi = getProdukByKategori($pdo, 'Minuman Non-Kopi');
$kopi = getProdukByKategori($pdo, 'Minuman Kopi');
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Penjualan - KasirKu</title>
  <link rel="stylesheet" href="../assets/style/css/style.css">
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600&display=swap" rel="stylesheet">
  <style>
    /* Modal basic style */
    .modal {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      display: none;
      align-items: center;
      justify-content: center;
      z-index: 999;
    }

    .modal-content {
      background: #fff;
      padding: 20px;
      border-radius: 8px;
      width: 300px;
      max-height: 80%;
      overflow-y: auto;
    }

    .modal-content .close {
      float: right;
      cursor: pointer;
      font-size: 20px;
    }
  </style>
</head>

<body>
  <div class="sidebar">
    <div class="logo-dashboard">
      <img src="../../img/logo/loading_uniquebites.png" alt="Logo">
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
        <span>Halo, <?= htmlspecialchars($_SESSION["nama"]) ?></span>
      </div>
    </div>
    <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'sukses') : ?>
      <div style="background:#dff0d8; color:#3c763d; padding:10px; margin:10px 0; border-radius:5px;">
        Pengeluaran berhasil ditambahkan!
      </div>
    <?php endif; ?>
    <div class="penjualan-container">
      <div class="product-category-section">

        <?php
        $kategori = [
          'Cemilan' => $cemilan,
          'Minuman Non-Kopi' => $nonkopi,
          'Minuman Kopi' => $kopi
        ];
        foreach ($kategori as $namaKategori => $produkList) : ?>
          <h2 class="product-category"><?= htmlspecialchars($namaKategori) ?></h2>
          <div class="product-grid">
            <?php if (!empty($produkList)) : ?>
              <?php foreach ($produkList as $p) : ?>
                <div class="product-card <?= $p['status'] == 'nonaktif' ? 'product-inactive' : '' ?>" onclick="addToCart(
                  <?= htmlspecialchars(json_encode($p['id'])) ?>,
                  '<?= htmlspecialchars($p['nama'], ENT_QUOTES) ?>',
                  <?= $p['harga'] ?>,
                  '<?= $p['status'] ?>'
                )">
                  <img src="../assets/images/product/<?= htmlspecialchars($p['gambar']) ?>" alt="<?= htmlspecialchars($p['nama']) ?>">
                  <h3><?= htmlspecialchars($p['nama']) ?></h3>
                  <p>Rp <?= number_format($p['harga'], 0, ',', '.') ?></p>
                  <?php if ($p['status'] == 'nonaktif') : ?>
                    <div class="not-available">Tidak tersedia</div>
                  <?php endif; ?>
                </div>
              <?php endforeach; ?>
            <?php else : ?>
              <p style="grid-column: 1 / -1; text-align:center; color:#777;">Belum ada produk di kategori ini.</p>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>

      </div>

      <div class="cart">
        <h2>Keranjang</h2>
        <ul id="cart-items"></ul>
        <div class="total">Total: <strong id="cart-total">Rp 0</strong></div>
        <button class="btn-pay">Bayar</button>
      </div>

      <div class="cart">
        <h2>Pengeluaran</h2>
        <form method="post" action="tambah_pengeluaran.php">
          <input type="text" name="nama_pengeluaran" placeholder="Nama Pengeluaran" required style="margin-bottom:5px; width: 100%; padding: 6px;">
          <input type="number" name="total" placeholder="Total (Rp)" required style="margin-bottom:5px; width: 100%; padding: 6px;">
          <input type="date" name="tanggal" value="<?= date('Y-m-d') ?>" required style="margin-bottom:5px; width: 100%; padding: 6px;">
          <button type="submit" class="btn-pay">Tambah</button>
        </form>
      </div>

      <h3></h3>

    </div>
  </div>

  <!-- Modal Bayar -->
  <div id="modalBayar" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeModalBayar()">&times;</span>
      <h2>Detail Pesanan</h2>
      <ul id="modal-cart-items"></ul>
      <div class="total">Total: <strong id="modal-cart-total">Rp 0</strong></div>
      <div style="margin-top:10px;">
        <label>Total Bayar: </label>
        <input type="number" id="input-total-bayar" placeholder="Masukkan jumlah bayar" oninput="hitungKembalian()">
      </div>
      <div style="margin-top:5px;">Kembalian: <strong id="modal-kembalian">Rp 0</strong></div>
      <button onclick="confirmPayment()" class="btn-pay" style="margin-top:10px;">Konfirmasi Bayar</button>
    </div>
  </div>


  <script>
    let cart = [];
    let modalTotalBayar = 0; // Simpan total belanja global

    function addToCart(id, nama, harga, status) {
      if (status === 'nonaktif') {
        alert('Maaf produk tidak tersedia');
        return;
      }

      let existing = cart.find(item => item.id == id);
      if (existing) {
        existing.qty += 1;
      } else {
        cart.push({
          id,
          nama,
          harga,
          qty: 1
        });
      }
      updateCartUI();
    }

    function updateCartUI() {
      const cartList = document.getElementById('cart-items');
      const cartTotal = document.getElementById('cart-total');
      cartList.innerHTML = '';
      let total = 0;

      cart.forEach((item, index) => {
        let subtotal = item.harga * item.qty;
        total += subtotal;
        let li = document.createElement('li');
        li.innerHTML = `
      ${item.nama} x${item.qty} - Rp ${formatRupiah(subtotal)}
      <button onclick="removeFromCart(${index})" style="margin-left:8px; color:red;">Hapus</button>
    `;
        cartList.appendChild(li);
      });
      cartTotal.textContent = `Rp ${formatRupiah(total)}`;
    }

    function removeFromCart(index) {
      cart.splice(index, 1);
      updateCartUI();
    }

    document.querySelector('.btn-pay').addEventListener('click', showModalBayar);

    function showModalBayar() {
      const modalItems = document.getElementById('modal-cart-items');
      const modalTotal = document.getElementById('modal-cart-total');
      const inputTotalBayar = document.getElementById('input-total-bayar');
      const kembalianEl = document.getElementById('modal-kembalian');

      modalItems.innerHTML = '';
      let total = 0;

      cart.forEach(item => {
        let subtotal = item.harga * item.qty;
        total += subtotal;
        let li = document.createElement('li');
        li.textContent = `${item.nama} x${item.qty} - Rp ${formatRupiah(subtotal)}`;
        modalItems.appendChild(li);
      });

      modalTotalBayar = total; // simpan total
      modalTotal.textContent = `Rp ${formatRupiah(total)}`;
      inputTotalBayar.value = ''; // kosongkan input
      kembalianEl.textContent = 'Rp 0';
      document.getElementById('modalBayar').style.display = 'flex';
    }

    function hitungKembalian() {
      const inputTotalBayar = document.getElementById('input-total-bayar').value;
      const kembalianEl = document.getElementById('modal-kembalian');
      let kembalian = inputTotalBayar - modalTotalBayar;

      if (!isNaN(kembalian) && inputTotalBayar !== '') {
        kembalianEl.textContent = 'Rp ' + formatRupiah(kembalian >= 0 ? kembalian : 0);
      } else {
        kembalianEl.textContent = 'Rp 0';
      }
    }

    function closeModalBayar() {
      document.getElementById('modalBayar').style.display = 'none';
    }

    function confirmPayment() {
      const inputTotalBayar = document.getElementById('input-total-bayar').value;
      if (inputTotalBayar < modalTotalBayar) {
        alert('Uang yang dibayarkan kurang!');
        return;
      }

      // Buat data yang dikirim
      const data = {
        total_bayar: inputTotalBayar,
        total: modalTotalBayar,
        cart: cart
      };

      fetch('simpan_transaksi.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(res => {
          if (res.success) {
            alert('Pembayaran berhasil!\nTotal: Rp ' + formatRupiah(modalTotalBayar) +
              '\nUang diterima: Rp ' + formatRupiah(inputTotalBayar) +
              '\nKembalian: Rp ' + formatRupiah(inputTotalBayar - modalTotalBayar));
            cart = [];
            updateCartUI();
            closeModalBayar();
          } else {
            alert('Gagal menyimpan data transaksi');
          }
        })
        .catch(err => {
          console.error(err);
          alert('Terjadi kesalahan saat menyimpan data');
        });
    }

    function formatRupiah(angka) {
      return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
  </script>
</body>

</html>