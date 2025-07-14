<?php
session_start();
if (!isset($_SESSION["nama"])) {
    header("Location: ../index.php");
    exit;
}

$pdo = new PDO("mysql:host=localhost;dbname=uniquebites_kasir", "root", "");

// Tambah produk
if (isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $kategori = $_POST['kategori'];
    $harga = $_POST['harga'];
    $status = $_POST['status'];
    $gambar = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];
    move_uploaded_file($tmp, "../assets/images/product/" . $gambar);

    $stmt = $pdo->prepare("INSERT INTO produk (nama, kategori, harga, gambar, status) VALUES (?,?,?,?,?)");
    $stmt->execute([$nama, $kategori, $harga, $gambar, $status]);
    header("Location: produk.php");
    exit;
}

// Edit produk
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $kategori = $_POST['kategori'];
    $harga = $_POST['harga'];
    $status = $_POST['status'];

    if (!empty($_FILES['gambar']['name'])) {
        $gambar = $_FILES['gambar']['name'];
        $tmp = $_FILES['gambar']['tmp_name'];
        move_uploaded_file($tmp, "../assets/images/product/" . $gambar);
        $stmt = $pdo->prepare("UPDATE produk SET nama=?, kategori=?, harga=?, gambar=?, status=? WHERE id=?");
        $stmt->execute([$nama, $kategori, $harga, $gambar, $status, $id]);
    } else {
        $stmt = $pdo->prepare("UPDATE produk SET nama=?, kategori=?, harga=?, status=? WHERE id=?");
        $stmt->execute([$nama, $kategori, $harga, $status, $id]);
    }
    header("Location: produk.php");
    exit;
}

// Hapus produk
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $stmt = $pdo->prepare("DELETE FROM produk WHERE id=?");
    $stmt->execute([$id]);
    header("Location: produk.php");
    exit;
}

if (isset($_GET['toggle'])) {
    $id = $_GET['toggle'];
    $stmt = $pdo->prepare("SELECT status FROM produk WHERE id=?");
    $stmt->execute([$id]);
    $row = $stmt->fetch();

    if ($row) {
        $newStatus = ($row['status'] == 'aktif') ? 'nonaktif' : 'aktif';
        $update = $pdo->prepare("UPDATE produk SET status=? WHERE id=?");
        $update->execute([$newStatus, $id]);
    }
    header("Location: produk.php");
    exit;
}


// Ambil semua produk
$stmt = $pdo->query("SELECT * FROM produk");
$produk = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk - KasirKu</title>
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
                <input type="text" id="searchInput" placeholder="Cari produk..." class="search-input">
                <button class="btn-add" onclick="openModal('modalTambah')">+ Tambah Produk</button>

                <!-- Modal Tambah -->
                <div class="modal" id="modalTambah">
                    <div class="modal-content">
                        <span class="close" onclick="closeModal('modalTambah')">&times;</span>
                        <h2>Tambah Produk</h2>
                        <form method="POST" enctype="multipart/form-data">
                            <input type="text" name="nama" placeholder="Nama produk" required>
                            <select name="kategori">
                                <option value="Cemilan">Cemilan</option>
                                <option value="Minuman Non-Kopi">Minuman Non-Kopi</option>
                                <option value="Minuman Kopi">Minuman Kopi</option>
                            </select>
                            <input type="number" name="harga" placeholder="Harga" required>
                            <input type="file" name="gambar" required>
                            <select name="status">
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
                            </select>
                            <button type="submit" name="tambah">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Modal Edit -->
            <div class="modal" id="modalEdit">
                <div class="modal-content">
                    <span class="close" onclick="closeModal('modalEdit')">&times;</span>
                    <h2>Edit Produk</h2>
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="edit-id">
                        <input type="text" name="nama" id="edit-nama" required placeholder="Nama produk">
                        <select name="kategori" id="edit-kategori">
                            <option value="Cemilan">Cemilan</option>
                            <option value="Minuman Non-Kopi">Minuman Non-Kopi</option>
                            <option value="Minuman Kopi">Minuman Kopi</option>
                        </select>
                        <input type="number" name="harga" id="edit-harga" required placeholder="Harga">
                        <input type="file" name="gambar">
                        <select name="status" id="edit-status">
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                        <button type="submit" name="edit">Simpan Perubahan</button>
                    </form>
                </div>
            </div>


            <div class="produk-table-container">
                <table class="produk-table">
                    <thead>
                        <tr>
                            <th>Gambar</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($produk as $p) : ?>
                            <tr>
                                <td><img src="../assets/images/product/<?= htmlspecialchars($p['gambar']) ?>" class="produk-img"></td>
                                <td><?= htmlspecialchars($p['nama']) ?></td>
                                <td><?= htmlspecialchars($p['kategori']) ?></td>
                                <td>Rp <?= number_format($p['harga'], 0, ',', '.') ?></td>
                                <td><a href="produk.php?toggle=<?= $p['id'] ?>">
                                        <button class="btn-status <?= $p['status'] == 'aktif' ? 'active' : 'nonaktif' ?>">
                                            <?= ucfirst($p['status']) ?>
                                        </button>
                                    </a>
                                </td>
                                <td>
                                    <button class="btn-edit" onclick="openEditModal(
        '<?= $p['id'] ?>',
        '<?= htmlspecialchars($p['nama'], ENT_QUOTES) ?>',
        '<?= htmlspecialchars($p['kategori'], ENT_QUOTES) ?>',
        '<?= $p['harga'] ?>',
        '<?= $p['status'] ?>'
      )">Edit</button>
                                    <button class="btn-delete" onclick="confirmDelete(<?= $p['id'] ?>)">Hapus</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <script>
            function openModal(id) {
                document.getElementById(id).style.display = 'flex';
            }

            function closeModal(id) {
                document.getElementById(id).style.display = 'none';
            }

            function openEditModal(id, nama, kategori, harga, status) {
                document.getElementById('edit-id').value = id;
                document.getElementById('edit-nama').value = nama;
                document.getElementById('edit-kategori').value = kategori;
                document.getElementById('edit-harga').value = harga;
                document.getElementById('edit-status').value = status;
                openModal('modalEdit');
            }

            function confirmDelete(id) {
                if (confirm('Yakin mau hapus produk ini?')) {
                    window.location.href = 'produk.php?hapus=' + id;
                }
            }

            document.getElementById('searchInput').addEventListener('keyup', function() {
                var value = this.value.toLowerCase();
                var rows = document.querySelectorAll('.produk-table tbody tr');
                rows.forEach(function(row) {
                    var nama = row.cells[1].innerText.toLowerCase();
                    row.style.display = nama.includes(value) ? '' : 'none';
                });
            });
        </script>

</body>

</html>