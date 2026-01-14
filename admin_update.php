<?php
include 'koneksi.php';

// Cek apakah tombol update_status diklik
if (isset($_POST['update_status'])) {
    // Mengamankan input agar terhindar dari SQL Injection
    $id_pesanan = mysqli_real_escape_string($conn, $_POST['id_pesanan']);
    $status_baru = mysqli_real_escape_string($conn, $_POST['status_baru']); 
    $nama_pelanggan = mysqli_real_escape_string($conn, $_POST['nama_pelanggan']);

    // 1. Update status di tabel PESANAN
    $query_update = "UPDATE pesanan SET status_pesanan = '$status_baru' WHERE id = '$id_pesanan'";
    mysqli_query($conn, $query_update);

    // 2. Buat pesan notifikasi otomatis berdasarkan status
    // Pesan default jika tidak masuk ke kondisi if
    $judul = "Update Status Pesanan";
    $pesan = "Halo $nama_pelanggan, status pesanan Anda sekarang adalah: $status_baru.";

    if ($status_baru == 'Diproses') {
        $judul = "Cucian Sedang Diproses 🧺";
        $pesan = "Halo $nama_pelanggan, pakaian Anda sedang dalam tahap pencucian atau setrika.";
    } elseif ($status_baru == 'Selesai') {
        $judul = "Cucian Selesai! 🎉";
        $pesan = "Kabar gembira $nama_pelanggan, cucian Anda sudah rapi dan siap diambil di outlet.";
    } elseif ($status_baru == 'Diantar') {
        $judul = "Pesanan Sedang Diantar 🛵";
        $pesan = "Kurir kami sedang menuju lokasi Anda. Harap pantau handphone Anda.";
    } elseif ($status_baru == 'Dibatalkan') {
        $judul = "Pesanan Dibatalkan ❌";
        $pesan = "Mohon maaf $nama_pelanggan, pesanan Anda dibatalkan. Hubungi admin untuk informasi lebih lanjut.";
    }

    // 3. Masukkan ke tabel NOTIFIKASI
    // Pastikan kolom id_pelanggan di tabel notifikasi adalah ID Pesanan tersebut
    $query_notif = "INSERT INTO notifikasi (id_pelanggan, judul, pesan, status_pesanan, waktu) 
                    VALUES ('$id_pesanan', '$judul', '$pesan', '$status_baru', NOW())";
    
    if (mysqli_query($conn, $query_notif)) {
        // Alihkan kembali ke halaman Admin
        header("Location: Admin.php?status=sukses");
        exit();
    } else {
        // Tampilkan error jika gagal
        echo "Gagal memperbarui notifikasi: " . mysqli_error($conn);
    }
} else {
    // Jika mencoba akses langsung tanpa POST
    header("Location: Admin.php");
    exit();
}
?>