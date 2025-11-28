<?php
// Menghubungkan dengan koneksi database
include '../koneksi.php';

// Menangkap data yang dikirim dari form
$id          = $_POST['id'];
$pelanggan   = $_POST['pelanggan'];
$berat       = $_POST['berat'];
$tgl_selesai = $_POST['tanggal_selesai'];
$status      = $_POST['status'];

// Mengambil data harga per kilo dari tabel harga (ambil satu baris pertama)
$h = mysqli_query($koneksi, "SELECT harga_per_kilo FROM harga LIMIT 1");
$harga_row = mysqli_fetch_assoc($h);

// Pastikan harga_per_kilo ada datanya
if ($harga_row && isset($harga_row['harga_per_kilo'])) {

    // Konversi agar benar-benar angka (hilangkan koma, titik, atau 'Rp')
    $harga_per_kilo = preg_replace('/[^0-9.]/', '', $harga_row['harga_per_kilo']);
    $harga_per_kilo = (float)$harga_per_kilo; // ubah ke tipe angka

    // Hitung total harga laundry
    $harga = $berat * $harga_per_kilo;

} else {
    // Kalau tidak ada data harga di tabel harga
    $harga = 0;
}

// Update data transaksi ke database
mysqli_query($koneksi, "
    UPDATE transaksi 
    SET 
        transaksi_pelanggan   = '$pelanggan',
        transaksi_harga       = '$harga',
        transaksi_berat       = '$berat',
        transaksi_tgl_selesai = '$tgl_selesai',
        transaksi_status      = '$status'
    WHERE transaksi_id = '$id'
");

// Menangkap data form input array (jenis pakaian dan jumlah pakaian)
$jenis_pakaian  = $_POST['jenis_pakaian'];
$jumlah_pakaian = $_POST['jumlah_pakaian'];

// Hapus semua data pakaian yang lama dari transaksi ini
mysqli_query($koneksi, "DELETE FROM pakaian WHERE pakaian_transaksi = '$id'");

// Input ulang data pakaian berdasarkan array
for ($x = 0; $x < count($jenis_pakaian); $x++) {
    $jenis  = mysqli_real_escape_string($koneksi, $jenis_pakaian[$x]);
    $jumlah = mysqli_real_escape_string($koneksi, $jumlah_pakaian[$x]);
    
    if (!empty($jenis)) {
        mysqli_query($koneksi, "INSERT INTO pakaian (pakaian_id, pakaian_transaksi, pakaian_jenis, pakaian_jumlah) VALUES ('', '$id', '$jenis', '$jumlah')");

        echo "<script>alert('Data Sudah Diubah'); window.location.href='transaksi.php'</script";
    }
}

// Kembali ke halaman transaksi
header("Location: transaksi.php");
exit;
?>