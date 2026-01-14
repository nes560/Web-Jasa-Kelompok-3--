<?php
session_start(); // Memulai session untuk menampung pesan status
include 'koneksi.php';

$id = "";
$nama = "";
$telp = "";
$alamat = "";
$layanan = "";
$jumlah = "";
$harga = "";
$status = "Pending"; 
$catatan = "";
$is_edit = false; 

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $is_edit = true; 
    
    $query = "SELECT * FROM pesanan WHERE id = '$id'";
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($result);

    if ($data) {
        $nama = $data['nama_pelanggan'];
        $telp = $data['nomor_telepon'];
        $alamat = $data['alamat'];
        $layanan = $data['jenis_layanan'];
        $jumlah = $data['jumlah'];
        $harga = $data['harga'];
        $status = $data['status_pesanan'];
        $catatan = $data['catatan'];
    }
}

if (isset($_POST['simpan'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_pelanggan']);
    $telp = mysqli_real_escape_string($conn, $_POST['nomor_telepon']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $layanan = mysqli_real_escape_string($conn, $_POST['jenis_layanan']);
    $jumlah = $_POST['jumlah'];
    $harga = $_POST['harga'];
    $status = $_POST['status_pesanan'];
    $catatan = mysqli_real_escape_string($conn, $_POST['catatan']);

    if ($is_edit) {
        $query_sql = "UPDATE pesanan SET 
            nama_pelanggan = '$nama',
            nomor_telepon = '$telp',
            alamat = '$alamat',
            jenis_layanan = '$layanan',
            jumlah = '$jumlah',
            harga = '$harga',
            status_pesanan = '$status',
            catatan = '$catatan'
            WHERE id = '$id'";
        $pesan = "Data Berhasil Diupdate!";
    } else {
        $query_sql = "INSERT INTO pesanan (nama_pelanggan, nomor_telepon, alamat, jenis_layanan, jumlah, harga, status_pesanan, catatan) 
            VALUES ('$nama', '$telp', '$alamat', '$layanan', '$jumlah', '$harga', '$status', '$catatan')";
        $pesan = "Pesanan Baru Berhasil Ditambahkan!";
    }

    if (mysqli_query($conn, $query_sql)) {
        // Simpan pesan ke session dan alihkan menggunakan PHP Header
        $_SESSION['status_info'] = $pesan;
        header("Location: DataPesananAdmin.php");
        exit(); // Sangat penting agar kode di bawah tidak dieksekusi
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?php echo $is_edit ? 'Edit Pesanan' : 'Tambah Pesanan'; ?> - Cling Laundry</title>
    <link rel="stylesheet" href="Style.css">
</head>
<body>

<div class="container-edit">
    <h2><?php echo $is_edit ? 'Edit Data Pesanan' : 'Tambah Pesanan Baru'; ?></h2>
    
    <form method="POST">
        <div class="form-group">
            <label>Nama Pelanggan</label>
            <input type="text" name="nama_pelanggan" value="<?php echo $nama; ?>" placeholder="Masukkan nama lengkap pelanggan" required>
        </div>

        <div class="form-group">
            <label>Nomor Telepon / WhatsApp</label>
            <input type="text" name="nomor_telepon" value="<?php echo $telp; ?>" placeholder="Contoh: 08123456789" required>
        </div>

        <div class="form-group">
            <label>Alamat Lengkap</label>
            <textarea name="alamat" rows="4" placeholder="Masukkan alamat detail penjemputan/pengantaran" required><?php echo $alamat; ?></textarea>
        </div>

        <div class="form-group">
            <label>Jenis Layanan</label>
            <input type="text" name="jenis_layanan" value="<?php echo $layanan; ?>" placeholder="Contoh: Cuci Kering Setrika" required>
        </div>

        <div class="form-group">
            <label>Jumlah (Kg / Pcs)</label>
            <input type="number" step="0.01" name="jumlah" value="<?php echo $jumlah; ?>" placeholder="0.00" required>
        </div>

        <div class="form-group">
            <label>Total Harga (Rp)</label>
            <input type="number" name="harga" value="<?php echo $harga; ?>" placeholder="Masukkan nominal harga" required>
        </div>

        <div class="form-group">
            <label>Status Pesanan</label>
            <select name="status_pesanan">
                <option value="Pending" <?php if($status == 'Pending') echo 'selected'; ?>>Pending </option>
                <option value="Diproses" <?php if($status == 'Diproses') echo 'selected'; ?>>Diproses </option>
                <option value="Selesai" <?php if($status == 'Selesai') echo 'selected'; ?>>Selesai </option>
                <option value="Diantar" <?php if($status == 'Diantar') echo 'selected'; ?>>Diantar </option>
                <option value="Dibatalkan" <?php if($status == 'Dibatalkan') echo 'selected'; ?>>Dibatalkan </option>
            </select>
        </div>

        <div class="form-group">
            <label>Catatan</label>
            <textarea name="catatan" rows="3" placeholder="Tuliskan catatan tambahan jika ada"><?php echo $catatan; ?></textarea>
        </div>

        <button type="submit" name="simpan" class="btn-simpan">
            <?php echo $is_edit ? 'Simpan Perubahan' : 'Buat Pesanan'; ?>
        </button>
        <a href="DataPesananAdmin.php" class="btn-batal"> Batalkan</a>
    </form>
</div>

</body>
</html>