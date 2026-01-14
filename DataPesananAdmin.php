<?php
include 'koneksi.php';

$where_query = "";
if (isset($_GET['cari']) && !empty($_GET['cari'])) {
    $keyword = mysqli_real_escape_string($conn, $_GET['cari']);
    $where_query = "WHERE nama_pelanggan LIKE '%$keyword%' OR id LIKE '%$keyword%'";
}

$query = "SELECT * FROM pesanan $where_query ORDER BY tanggal_pemesanan DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pesanan - Cling Laundry</title>
    <link rel="stylesheet" href="Style.css">
</head>
<body>

    <div class="navbar">
        <div class="wadah-navbar">
            <div class="logo"><h2>Cling<span>.</span></h2></div>
            <ul class="menu-atas">
                <li><a href="Admin.php"> Dashboard</a></li>
                <li><a href="DataPesananAdmin.php" class="aktif"> Data Pesanan</a></li>
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
                <h1>Data Pesanan</h1>
                <p>Daftar transaksi laundry.</p>
            </div>
            <a href="EditPesananAdmin.php" class="tombol-tambah">+ Pesanan Baru</a>
        </div>

        <div class="area-tabel" style="margin-bottom: 20px; padding: 15px;">
            <form action="" method="GET" class="baris-filter">
                <input type="text" name="cari" class="input-cari" placeholder="Cari ID atau Nama..." style="width: 300px;" value="<?php echo isset($_GET['cari']) ? $_GET['cari'] : ''; ?>">
                <button type="submit" class="btn-cari">Cari</button>
            </form>
        </div>

        <div class="daftar-orderan">
            <?php
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $status = $row['status_pesanan'];
                    $ikon = "📝"; 
                    if ($status == 'Pending') $ikon = "⚠️";
                    if ($status == 'Diproses') $ikon = "⏳";
                    if ($status == 'Selesai') $ikon = "✅";
                    if ($status == 'Diantar') $ikon = "🛵";
                    if ($status == 'Dibatalkan') $ikon = "❌";
                    $tanggal = date('d M Y, H:i', strtotime($row['tanggal_pemesanan']));
            ?>
            <table class="tabel-satuan">
                <tr>
                    <th colspan="2" class="header-order">
                        <b>Order #<?php echo $row['id']; ?></b> - <?php echo $row['nama_pelanggan']; ?>
                        <span class="tanggal"> <?php echo $tanggal; ?></span>
                    </th>
                </tr>
                <tr><td class="label">Nomor Telepon</td><td><?php echo $row['nomor_telepon']; ?></td></tr>
                <tr><td class="label">Alamat</td><td><?php echo $row['alamat']; ?></td></tr>
                <tr><td class="label">Jenis Layanan</td><td><?php echo $row['jenis_layanan']; ?></td></tr>
                <tr><td class="label">Jumlah</td><td><b><?php echo number_format($row['jumlah'], 2); ?></b> (Kg/Pcs)</td></tr>
                <tr><td class="label">Total Harga</td><td><span class="harga">Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></span></td></tr>
                <tr><td class="label">Catatan</td><td><i><?php echo (!empty($row['catatan'])) ? $row['catatan'] : "-"; ?></i></td></tr>
                <tr>
                    <td class="label">Status Pesanan</td>
                    <td><span class="status <?php echo $status; ?>"><?php echo $ikon . " " . $status; ?></span></td>
                </tr>
                <tr>
                    <td colspan="2" class="aksi-footer">
                        <a href="EditPesananAdmin.php?id=<?php echo $row['id']; ?>" class="btn-aksi">Edit</a>
                        <a href="hapus_pesanan.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Yakin hapus Order #<?php echo $row['id']; ?>?')" class="btn-aksi" style="color: black; text-decoration: none;">Hapus</a>
                    </td>
                </tr>
            </table>
            <?php 
                } 
            } else {
                echo "<p style='text-align:center; padding: 20px; background:white;'>Belum ada data pesanan.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>