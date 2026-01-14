<?php
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    

    $query = "DELETE FROM pesanan WHERE id = '$id'";
    $hapus = mysqli_query($conn, $query);

    if ($hapus) {
        header("Location: DataPesananAdmin.php");
        exit;
    } else {
        echo "Gagal menghapus: " . mysqli_error($conn);
    }
} else {
    echo "ID tidak ditemukan.";
}
?>