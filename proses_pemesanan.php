<?php
session_start();
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nama     = htmlspecialchars($_POST['nama_pelanggan']);
    $telepon  = htmlspecialchars($_POST['nomor_telepon']);
    $alamat   = htmlspecialchars($_POST['alamat']);
    $layanan  = htmlspecialchars($_POST['jenis_layanan']);
    $jumlah   = htmlspecialchars($_POST['jumlah']);
    $catatan  = htmlspecialchars($_POST['catatan']);

    // --- LOGIKA HARGA DISESUAIKAN DENGAN NAMA DI DATABASE KAMU ---
    $harga_satuan = 0;

    // Nama-nama di bawah ini harus SAMA PERSIS dengan atribut 'value' di HTML kamu
    if ($layanan == 'reguler_lipat') { $harga_satuan = 7000; }
    elseif ($layanan == 'reguler_setrika') { $harga_satuan = 9000; }
    elseif ($layanan == 'setrika_saja') { $harga_satuan = 6000; }
    elseif ($layanan == 'ekspres_lipat') { $harga_satuan = 12000; }
    elseif ($layanan == 'ekspres_setrika') { $harga_satuan = 15000; }
    elseif ($layanan == 'satuan_bedcover') { $harga_satuan = 25000; }
    elseif ($layanan == 'satuan_selimut') { $harga_satuan = 20000; }
    elseif ($layanan == 'satuan_boneka') { $harga_satuan = 30000; }
    elseif ($layanan == 'satuan_jas') { $harga_satuan = 35000; }

    // Hitung total harga
    $total_harga = $jumlah * $harga_satuan;

    // 1. Simpan ke Tabel `pesanan`
    // Urutan kolom: nama, telepon, alamat, layanan, jumlah, catatan, status, harga
    $stmt = $conn->prepare("INSERT INTO pesanan (nama_pelanggan, nomor_telepon, alamat, jenis_layanan, jumlah, catatan, status_pesanan, harga) VALUES (?, ?, ?, ?, ?, ?, 'Pending', ?)");
    $stmt->bind_param("ssssssd", $nama, $telepon, $alamat, $layanan, $jumlah, $catatan, $total_harga);

    if ($stmt->execute()) {
        $id_pesanan_baru = $stmt->insert_id;

        // 2. Simpan ke Tabel `notifikasi`
        $judul = "Pesanan Berhasil!";
        $pesan = "Halo $nama, pesanan $layanan Anda sedang kami proses.";
        
        $stmt_notif = $conn->prepare("INSERT INTO notifikasi (id_pelanggan, judul, pesan, status_pesanan) VALUES (?, ?, ?, 'Pending')");
        $stmt_notif->bind_param("iss", $id_pesanan_baru, $judul, $pesan);
        $stmt_notif->execute();

        header("Location: notifikasi.php");
        exit();
    } else {
        echo "Gagal: " . $stmt->error;
    }
}
?>