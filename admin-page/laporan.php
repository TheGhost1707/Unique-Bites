<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan - KasirKu</title>
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style/css/style.css">
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
            <li><a href="produk.php">Produk</a></li>
            <li class="active"><a href="laporan.php">Laporan</a></li>
            <li><a href="#">Pengaturan</a></li>
        </ul>
    </div>
    <div class="main">
        <div class="topbar">
            <h1>Laporan Penjualan</h1>
            <div class="user-info">
                <span>Halo, Master</span>
                <img src="assets/images/user.png" alt="User">
            </div>
        </div>

        <div class="content">
            <div class="filter-section">
                <label for="periode">Periode:</label>
                <select id="periode">
                    <option>Hari</option>
                    <option>Minggu</option>
                    <option>Bulan</option>
                </select>
                <label for="kategori">Kategori:</label>
                <select id="kategori">
                    <option>Semua</option>
                    <option>Cemilan</option>
                    <option>Minuman Kopi</option>
                    <option>Minuman Non-Kopi</option>
                </select>
                <button class="btn-export">Export PDF</button>
            </div>

            <div class="summary-cards">
                <div class="summary-card">
                    <h4>Total Transaksi</h4>
                    <p>50</p>
                </div>
                <div class="summary-card">
                    <h4>Total Omset</h4>
                    <p>Rp 5.000.000</p>
                </div>
                <div class="summary-card">
                    <h4>Laba Bersih</h4>
                    <p>Rp 3.200.000</p>
                </div>
                <div class="summary-card">
                    <h4>Produk Terlaris</h4>
                    <p>Donat Coklat</p>
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
                            <th>Laba</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>12-07-2025</td>
                            <td>Rp 1.200.000</td>
                            <td>Rp 500.000</td>
                            <td>Rp 700.000</td>
                        </tr>
                        <tr>
                            <td>11-07-2025</td>
                            <td>Rp 900.000</td>
                            <td>Rp 300.000</td>
                            <td>Rp 600.000</td>
                        </tr>
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
                            <th>Total</th>
                            <th>Metode</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#0012</td>
                            <td>12-07-2025 14:20</td>
                            <td>Master</td>
                            <td>Rp 100.000</td>
                            <td>Tunai</td>
                            <td>Selesai</td>
                        </tr>
                        <tr>
                            <td>#0011</td>
                            <td>12-07-2025 13:10</td>
                            <td>Master</td>
                            <td>Rp 50.000</td>
                            <td>QRIS</td>
                            <td>Selesai</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
                datasets: [{
                        label: 'Omset',
                        data: [1200000, 900000, 1000000, 800000, 1500000, 1300000, 1100000],
                        backgroundColor: '#3c8dbc'
                    },
                    {
                        label: 'Pengeluaran',
                        data: [500000, 300000, 400000, 350000, 600000, 500000, 450000],
                        backgroundColor: '#f39c12'
                    },
                    {
                        label: 'Laba',
                        data: [700000, 600000, 600000, 450000, 900000, 800000, 650000],
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
    </script>
</body>

</html>