<?php
include 'header.php';
include '../koneksi.php';

// cek apakah id dikirim lewat url
if(!isset($_GET['id'])){
    header("Location: transaksi.php");
    exit;
}
$id = mysqli_real_escape_string($koneksi, $_GET['id']);
?>

<div class="container">
    <div class="panel">
        <div class="panel-heading">
            <h4>Edit Transaksi Laundry</h4>
        </div>
        <div class="panel-body">

            <div class="col-md-8 col-md-offset-2">
                <a href="transaksi.php" class="btn btn-sm btn-info pull-right">Kembali</a>
                <br/><br/>

                <?php
                $transaksi = mysqli_query($koneksi, "SELECT * FROM transaksi WHERE transaksi_id='$id'");
                while($t = mysqli_fetch_array($transaksi)){
                ?>
                <form method="post" action="transaksi_update.php">
                    <input type="hidden" name="id" value="<?php echo $t['transaksi_id']; ?>">

                    <div class="form-group">
                        <label>Pelanggan</label>
                        <select class="form-control" name="pelanggan" required>
                            <option value="">- Pilih Pelanggan</option>
                            <?php
                            $data = mysqli_query($koneksi, "SELECT * FROM pelanggan");
                            while($d = mysqli_fetch_array($data)){
                            ?>
                                <option <?php if($d['pelanggan_id'] == $t['transaksi_pelanggan']) echo "selected"; ?>
                                    value="<?php echo $d['pelanggan_id']; ?>">
                                    <?php echo $d['pelanggan_nama']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Berat</label>
                        <input type="number" class="form-control" name="berat" required
                               placeholder="Masukkan berat cucian .."
                               value="<?php echo $t['transaksi_berat']; ?>">
                    </div>

                    <div class="form-group">
                        <label>Tgl. Selesai</label>
                        <input type="date" class="form-control" name="tanggal_selesai" required
                               value="<?php echo $t['transaksi_tgl_selesai']; ?>">
                    </div>

                    <br/>

                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>Jenis Pakaian</th>
                            <th width="20%">Jumlah</th>
                        </tr>

                        <?php
                        $id_transaksi = $t['transaksi_id'];
                        $pakaian = mysqli_query($koneksi,"SELECT * FROM pakaian WHERE pakaian_transaksi='$id_transaksi'");
                        while($p = mysqli_fetch_array($pakaian)){
                        ?>
                        <tr>
                            <td><input type="text" class="form-control" name="jenis_pakaian[]" value="<?php echo $p['pakaian_jenis']; ?>"></td>
                            <td><input type="number" class="form-control" name="jumlah_pakaian[]" value="<?php echo $p['pakaian_jumlah']; ?>"></td>
                        </tr>
                        <?php } ?>

                        <!-- Tambahan baris kosong -->
                        <?php for($i=0; $i<4; $i++){ ?>
                        <tr>
                            <td><input type="text" class="form-control" name="jenis_pakaian[]"></td>
                            <td><input type="number" class="form-control" name="jumlah_pakaian[]"></td>
                        </tr>
                        <?php } ?>
                    </table>

                    <div class="form-group alert alert-info">
                        <label>Status</label>
                        <select class="form-control" name="status" required>
                            <option value="0" <?php if($t['transaksi_status']=="0") echo "selected"; ?>>PROSES</option>
                            <option value="1" <?php if($t['transaksi_status']=="1") echo "selected"; ?>>DI CUCI</option>
                            <option value="2" <?php if($t['transaksi_status']=="2") echo "selected"; ?>>SELESAI</option>
                        </select>
                    </div>

                    <input type="submit" class="btn btn-primary" value="Simpan">
                </form>
                <?php } ?>
            </div>
        </div>
    </div>
</div>