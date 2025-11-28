<?php
include '../koneksi.php';

$id = $_GET['id'];

// Hapus dulu semua pakaian yang terhubung dengan transaksi ini
mysqli_query($koneksi, "DELETE FROM pakaian WHERE pakaian_transaksi='$id'");

// Baru hapus transaksi induknya
mysqli_query($koneksi, "DELETE FROM transaksi WHERE transaksi_id='$id'");

echo "<script>alert('Data Akan Dihapus?'); window.location.href='transaksi.php'</script";

header("location:transaksi.php");
?>