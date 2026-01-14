<?php
include 'koneksi.php';

// 1. Hitung Jumlah Pesanan Berdasarkan Status
$query_pending = mysqli_query($conn, "SELECT COUNT(*) as jumlah FROM pesanan WHERE status_pesanan = 'Pending'");
$jumlah_pending = mysqli_fetch_assoc($query_pending)['jumlah'];

$query_proses = mysqli_query($conn, "SELECT COUNT(*) as jumlah FROM pesanan WHERE status_pesanan = 'Diproses'");
$jumlah_proses = mysqli_fetch_assoc($query_proses)['jumlah'];

$query_selesai = mysqli_query($conn, "SELECT COUNT(*) as jumlah FROM pesanan WHERE status_pesanan = 'Selesai'");
$jumlah_selesai = mysqli_fetch_assoc($query_selesai)['jumlah'];

// 2. LOGIKA OTOMATIS: Hitung Pendapatan (Hanya yang TIDAK Dibatalkan)
$query_income = mysqli_query($conn, "SELECT SUM(harga) as total FROM pesanan WHERE status_pesanan != 'Dibatalkan'");
$data_income = mysqli_fetch_assoc($query_income);
$total_income = $data_income['total'] ? $data_income['total'] : 0;

// 3. Ambil 5 Pesanan Terbaru
$query_tabel = "SELECT * FROM pesanan ORDER BY tanggal_pemesanan DESC LIMIT 5";
$result_tabel = mysqli_query($conn, $query_tabel);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Cling Laundry</title>
    <link rel="stylesheet" href="Style.css">
    <style>
        /* Tambahan style untuk status dan kolom harga */
        .status { padding: 5px 10px; border-radius: 20px; font-size: 12px; font-weight: bold; display: inline-block; }
        .status.Pending { background-color: #fff3cd; color: #856404; }
        .status.Diproses { background-color: #e3f2fd; color: #0c5460; }
        .status.Selesai { background-color: #d4edda; color: #155724; }
        .status.Diantar { background-color: #d1ecf1; color: #004085; }
        .status.Dibatalkan { background-color: #f8d7da; color: #721c24; }
        .teks-harga { font-weight: bold; color: #00208A; }
    </style>
</head>
<body>

    <div class="navbar">
        <div class="wadah-navbar">
            <div class="logo"><h2>Cling<span>.</span></h2></div>
            <ul class="menu-atas">
                <li><a href="Admin.php" class="aktif"> Dashboard</a></li>
                <li><a href="DataPesananAdmin.php"> Data Pesanan</a></li>
            </ul>
            <div class="profil-kanan">
                <span class="teks-halo">Halo, <b>Admin</b></span>
                <a href="LogoutAdmin.php" class="tombol-logout">Keluar</a>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="header-halaman">
            <div class="judul-kiri">
                <h1>Dashboard Overview</h1>
                <p>Selamat datang kembali, pantau performa laundry hari ini.</p>
            </div>
        </div>

        <div class="baris-kartu">
            <div class="pembungkus-kartu">
                <div class="kartu biru">
                    <div class="info">
                        <h3><?php echo $jumlah_pending; ?></h3>
                        <p>Pesanan Baru</p>
                    </div>
                </div>
            </div>

            <div class="pembungkus-kartu">
                <div class="kartu oranye">
                    <div class="info">
                        <h3><?php echo $jumlah_proses; ?></h3>
                        <p>Sedang Dicuci</p>
                    </div>
                </div>
            </div>

            <div class="pembungkus-kartu">
                <div class="kartu hijau">
                    <div class="info">
                        <h3><?php echo $jumlah_selesai; ?></h3>
                        <p>Selesai</p>
                    </div>
                </div>
            </div>

            <div class="pembungkus-kartu">
                <div class="kartu merah">
                    <div class="info">
                        <h3>Rp <?php echo number_format($total_income, 0, ',', '.'); ?></h3>
                        <p>Total Pendapatan</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="area-tabel">
            <div class="header-tabel">
                <h3>Pesanan Terbaru</h3>
                <a href="DataPesananAdmin.php" class="tombol-tambah">Lihat Semua</a>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>ID Order</th>
                        <th>Nama Pelanggan</th>
                        <th>Layanan</th>
                        <th>Jumlah</th>
                        <th>Harga Total</th> <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result_tabel && mysqli_num_rows($result_tabel) > 0) {
                        while ($row = mysqli_fetch_assoc($result_tabel)) {
                            $status = $row['status_pesanan'];
                    ?>
                    <tr>
                        <td class="id-order">#Order-<?php echo $row['id']; ?></td>
                        <td><?php echo $row['nama_pelanggan']; ?></td>
                        <td><?php echo $row['jenis_layanan']; ?></td>
                        <td><?php echo $row['jumlah']; ?> kg/pcs</td>
                        <td class="teks-harga">Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                        <td><span class="status <?php echo $status; ?>"><?php echo $status; ?></span></td>
                    </tr>
                    <?php 
                        }
                    } else {
                        echo "<tr><td colspan='6' style='text-align:center;'>Belum ada data.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>