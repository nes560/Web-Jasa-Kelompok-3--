<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);


$servername = "sql110.infinityfree.com"; 
$username   = "if0_40823136";           
$password   = "5tjdBYy0l0Lh8S";         
$database   = "if0_40823136_cling_laundry";   


$conn = mysqli_connect($servername, $username, $password, $database);
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Query mengambil data lengkap termasuk kolom harga dari tabel pesanan
$sql = "SELECT n.*, p.status_pesanan AS status_terbaru, p.jenis_layanan, p.jumlah, p.harga
        FROM notifikasi n 
        LEFT JOIN pesanan p ON n.id_pelanggan = p.id 
        ORDER BY n.waktu DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi - Cling Laundry</title>
    <link rel="stylesheet" href="Style.css">
    <style>
        /* Tambahan style agar tampilan badge konsisten */
        .badge-status { padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; color: white; display: inline-block; }
        .status-pending { background-color: #f1c40f; }
        .status-diproses { background-color: #3498db; }
        .status-selesai { background-color: #2ecc71; }
        .status-diantar { background-color: #9b59b6; }
        .status-dibatalkan { background-color: #e74c3c; }
    </style>
</head>
<body>

<header>
    <div class="header-content">
        <div class="logo"><h2>Cling<span>.</span></h2></div>
        <nav>
            <ul>
                <li><a href="index.php">Beranda</a></li>
                <li><a href="layanan.html">Layanan</a></li>
                <li><a href="Galeri Hasil Laundry.html">Galeri</a></li>
                <li><a href="FAQ.html">FAQ</a></li>
                <li><a href="TentangKami.html">Tentang Kami</a></li>
                <li><a href="pemesanan.html">Pesan</a></li>
                <li><a href="notifikasi.php" class="active">Notifikasi</a></li> 
                <li><a href="LokasiSaya.html">Lokasi</a></li>
                <li><a href="KontakKami.html">Kontak</a></li>
            </ul>
        </nav>
    </div>
</header>

<main>
    <section>
        <div>
            <h2 style="color: #00208A; text-align: center;">Notifikasi & Rincian Biaya</h2>
            <p style="text-align: center; color: #666;">Pantau status dan total biaya laundry Anda secara otomatis.</p>
            
            <div class="table-container">
                <table>
                    <thead class="header-reguler">
                        <tr>
                            <th>Keterangan & Rincian</th>
                            <th style="text-align: center;">Status</th>
                            <th style="text-align: right;">Total Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(mysqli_num_rows($result) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($result)): ?>
                                <?php 
                                    // 1. Logika Status
                                    $status_raw = ($row['status_terbaru']) ? $row['status_terbaru'] : $row['status_pesanan'];
                                    $status_final = trim($status_raw);
                                    if (empty($status_final)) { $status_final = "Pending"; }

                                    // 2. Logika Harga (Mengutamakan data di kolom 'harga' database)
                                    $total_harga = $row['harga'];

                                    // Logika Cadangan: Jika kolom harga di database kosong (0), hitung manual berdasarkan slug layanan
                                    if ($total_harga <= 0) {
                                        $layanan_slug = $row['jenis_layanan'];
                                        $qty = $row['jumlah'];
                                        $harga_satuan = 0;

                                        if ($layanan_slug == 'reguler_lipat') { $harga_satuan = 7000; }
                                        elseif ($layanan_slug == 'reguler_setrika') { $harga_satuan = 9000; }
                                        elseif ($layanan_slug == 'setrika_saja') { $harga_satuan = 6000; }
                                        elseif ($layanan_slug == 'ekspres_lipat') { $harga_satuan = 12000; }
                                        elseif ($layanan_slug == 'ekspres_setrika') { $harga_satuan = 15000; }
                                        elseif ($layanan_slug == 'satuan_bedcover') { $harga_satuan = 25000; }
                                        elseif ($layanan_slug == 'satuan_selimut') { $harga_satuan = 20000; }
                                        elseif ($layanan_slug == 'satuan_boneka') { $harga_satuan = 30000; }
                                        elseif ($layanan_slug == 'satuan_jas') { $harga_satuan = 35000; }
                                        
                                        $total_harga = $qty * $harga_satuan;
                                    }

                                    // 3. Logika Judul Dinamis
                                    $judul_tampil = "Pesanan Berhasil!";
                                    if ($status_final == 'Diproses') { $judul_tampil = "Sedang Diproses 🧺"; }
                                    elseif ($status_final == 'Selesai') { $judul_tampil = "Siap Diambil! 🎉"; }
                                    elseif ($status_final == 'Diantar') { $judul_tampil = "Kurir dalam Perjalanan! 🛵"; }
                                    elseif ($status_final == 'Dibatalkan') { $judul_tampil = "Pesanan Batal ❌"; }

                                    $class_css = strtolower(str_replace(' ', '', $status_final));
                                ?>
                                <tr>
                                    <td>
                                        <strong style="color: #333;"><?php echo $judul_tampil; ?></strong>
                                        <p style="font-size: 0.85em; color: #777; margin-top: 5px;">
                                            Layanan: <?php echo str_replace('_', ' ', $row['jenis_layanan']); ?> (<?php echo $row['jumlah']; ?> unit)
                                        </p>
                                        <small style="color: #999;"><?php echo date('d M Y, H:i', strtotime($row['waktu'])); ?></small>
                                    </td>
                                    <td style="text-align: center;">
                                        <span class="badge-status status-<?php echo $class_css; ?>">
                                            <?php echo $status_final; ?>
                                        </span>
                                    </td>
                                    <td style="text-align: right; font-weight: bold; color: #00208A;">
                                        <?php echo ($total_harga > 0) ? "Rp " . number_format($total_harga, 0, ',', '.') : "-"; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" style="text-align: center; padding: 40px; color: #999;">Belum ada data.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</main>

<footer>
    <p>&copy; 2025 Cling Laundry. Dibuat oleh Kelompok Genap.</p>
</footer>

</body>
</html>