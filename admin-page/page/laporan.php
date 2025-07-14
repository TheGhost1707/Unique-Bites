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

    // Filter
    $periode = $_GET['periode'] ?? 'Bulan'; // default Bulan
    $kategori = $_GET['kategori'] ?? '';

    // Hitung range tanggal
    $today = date('Y-m-d');
    if ($periode == 'Hari') {
        $awal = $today;
        $akhir = $today;
    } elseif ($periode == 'Minggu') {
        $awal = date('Y-m-d', strtotime('monday this week'));
        $akhir = date('Y-m-d', strtotime('sunday this week'));
    } else { // Bulan
        $awal = date('Y-m-01');
        $akhir = date('Y-m-t');
    }

    // Query total transaksi
    $where = "WHERE DATE(tanggal) BETWEEN ? AND ?";
    $params = [$awal, $akhir];
    if ($kategori && $kategori != 'Semua') {
        $where .= " AND kategori = ?";
        $params[] = $kategori;
    }

    $stmt = $pdo->prepare("SELECT SUM(qty) as total_transaksi FROM detail_transaksi $where");
    $stmt->execute($params);
    $total_transaksi = $stmt->fetchColumn() ?: 0;


    // Total omset
    $stmt = $pdo->prepare("SELECT SUM(subtotal) as omset FROM detail_transaksi $where");
    $stmt->execute($params);
    $total_omset = $stmt->fetchColumn() ?: 0;

    // Laba Bersih = omset - total pengeluaran
    $stmt = $pdo->prepare("SELECT SUM(total) FROM pengeluaran WHERE tanggal BETWEEN ? AND ?");
    $stmt->execute([$awal, $akhir]);
    $total_pengeluaran = $stmt->fetchColumn() ?: 0;
    $laba_bersih = $total_omset - $total_pengeluaran;

    // Produk terlaris
    $stmt = $pdo->prepare("SELECT nama_produk, SUM(qty) as total_qty FROM detail_transaksi $where GROUP BY nama_produk ORDER BY total_qty DESC LIMIT 1");
    $stmt->execute($params);
    $produk_terlaris = $stmt->fetch();
    $nama_terlaris = $produk_terlaris['nama_produk'] ?? '-';

    $stmt = $pdo->prepare("
    SELECT * FROM detail_transaksi
    WHERE DATE(tanggal) BETWEEN ? AND ?
    ORDER BY tanggal DESC
    LIMIT 10
");
    $stmt->execute([$awal, $akhir]);
    $transaksi_terbaru = $stmt->fetchAll(PDO::FETCH_ASSOC);


    // Ringkasan laporan per hari
    $stmt = $pdo->prepare("SELECT DATE(tanggal) as tgl, 
        SUM(subtotal) as omset,
        (SELECT SUM(total) FROM pengeluaran WHERE tanggal=DATE(tanggal)) as pengeluaran,
        (SUM(subtotal)-(SELECT IFNULL(SUM(total),0) FROM pengeluaran WHERE tanggal=DATE(tanggal))) as laba
        FROM detail_transaksi $where
        GROUP BY DATE(tanggal) ORDER BY tgl DESC");
    $stmt->execute($params);
    $ringkasan = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan - KasirKu</title>
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style/css/style.css">
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
            <li><a href="produk.php">Produk</a></li>
            <li class="active"><a href="laporan.php">Laporan</a></li>
            <li><a href="#">Pengaturan</a></li>
        </ul>
    </div>
    <div class="main">
        <div class="topbar">
            <h1>Laporan Penjualan</h1>
            <div class="user-info">
                <span>Halo, <?= htmlspecialchars($_SESSION["nama"]) ?></span>
            </div>
        </div>

        <div class="content">
            <div class="filter-section">
                <label for="periode">Periode:</label>
                <select id="periode" name="periode">
                    <option <?= $periode == 'Hari' ? 'selected' : '' ?>>Hari</option>
                    <option <?= $periode == 'Minggu' ? 'selected' : '' ?>>Minggu</option>
                    <option <?= $periode == 'Bulan' ? 'selected' : '' ?>>Bulan</option>
                </select>
                <label for="kategori">Kategori:</label>
                <select id="kategori" name="kategori">
                    <option <?= $kategori == 'Semua' ? 'selected' : '' ?>>Semua</option>
                    <option <?= $kategori == 'Cemilan' ? 'selected' : '' ?>>Cemilan</option>
                    <option <?= $kategori == 'Minuman Kopi' ? 'selected' : '' ?>>Minuman Kopi</option>
                    <option <?= $kategori == 'Minuman Non-Kopi' ? 'selected' : '' ?>>Minuman Non-Kopi</option>
                </select>

                <button class="btn-export">Export PDF</button>
            </div>

            <div class="summary-cards">
                <div class="summary-card">
                    <h4>Total Penjualan</h4>
                    <p><?= number_format($total_transaksi, 0, ',', '.') ?></p>
                </div>
                <div class="summary-card">
                    <h4>Total Omset</h4>
                    <p>Rp <?= number_format($total_omset, 0, ',', '.') ?></p>
                </div>
                <div class="summary-card">
                    <h4>Laba Bersih</h4>
                    <p>Rp <?= number_format($laba_bersih, 0, ',', '.') ?></p>
                </div>
                <div class="summary-card">
                    <h4>Produk Terlaris</h4>
                    <p><?= htmlspecialchars($nama_terlaris) ?></p>
                </div>
            </div>

            <div class="chart-section">
                <canvas id="salesChart"></canvas>
            </div>

            <div class="table-section">
                <h2>Ringkasan Laporan</h2>
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Omset</th>
                            <th>Pengeluaran</th>
                            <th>Laba Bersih</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ringkasan as $r) : ?>
                            <tr>
                                <td><?= date('d-m-Y', strtotime($r['tgl'])) ?></td>
                                <td>Rp <?= number_format($r['omset'], 0, ',', '.') ?></td>
                                <td>Rp <?= number_format($r['pengeluaran'] ?: 0, 0, ',', '.') ?></td>
                                <td>Rp <?= number_format($r['laba'], 0, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="table-section">
                <h2>Detail Transaksi</h2>
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>Nota</th>
                            <th>Tanggal</th>
                            <th>Kasir</th>
                            <th>Nama produk</th>
                            <th>Total Pembelian</th>
                            <th>Harga Satuan</th>
                            <th>Total harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transaksi_terbaru as $t) : ?>
                            <tr>
                                <td>#<?= htmlspecialchars($t['id_transaksi']) ?></td>
                                <td><?= date('d-m-Y H:i', strtotime($t['tanggal'])) ?></td>
                                <td><?= htmlspecialchars($t['kasir']) ?></td>
                                <td><?= htmlspecialchars($t['nama_produk']) ?></td>
                                <td><?= number_format($t['qty'], 0, ',', '.') ?></td>
                                <td>Rp <?= number_format($t['harga'], 0, ',', '.') ?></td>
                                <td><?= number_format($t['subtotal'], 0, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>


            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                const labels = <?= json_encode(array_reverse(array_column($ringkasan, 'tgl'))) ?>;
                const omsetData = <?= json_encode(array_reverse(array_map('intval', array_column($ringkasan, 'omset')))) ?>;
                const pengeluaranData = <?= json_encode(array_reverse(array_map('intval', array_column($ringkasan, 'pengeluaran')))) ?>;
                const labaData = <?= json_encode(array_reverse(array_map('intval', array_column($ringkasan, 'laba')))) ?>;

                const ctx = document.getElementById('salesChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels.map(dateStr => {
                            const d = new Date(dateStr);
                            return d.toLocaleDateString('id-ID', {
                                weekday: 'short',
                                day: 'numeric'
                            });
                        }),
                        datasets: [{
                                label: 'Omset',
                                data: omsetData,
                                backgroundColor: '#3c8dbc'
                            },
                            {
                                label: 'Pengeluaran',
                                data: pengeluaranData,
                                backgroundColor: '#f39c12'
                            },
                            {
                                label: 'Laba',
                                data: labaData,
                                backgroundColor: '#00a65a'
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });

                document.getElementById('periode').addEventListener('change', updateFilter);
                document.getElementById('kategori').addEventListener('change', updateFilter);

                function updateFilter() {
                    const periode = document.getElementById('periode').value;
                    const kategori = document.getElementById('kategori').value;
                    window.location.href = `?periode=${periode}&kategori=${kategori}`;
                }
            </script>
</body>

</html>