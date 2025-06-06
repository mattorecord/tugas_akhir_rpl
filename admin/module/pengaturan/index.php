<?php
// Ambil data toko untuk ditampilkan di form
$toko = $lihat->toko();

// ==========================================================
// BAGIAN 1: Kode PHP untuk memeriksa peran pengguna
// ==========================================================
$role = $_SESSION['admin']['role'];
$disabled_attr = ''; // Siapkan variabel untuk atribut disabled

// Jika peran adalah 'pemilik', isi variabel dengan kata 'disabled'
if ($role == 'pemilik') {
	$disabled_attr = 'disabled';
}
?>
<h4>Pengaturan Toko</h4>
<br>
<?php if (isset($_GET['success'])) { ?>
<div class="alert alert-success">
    <p>Ubah Data Berhasil !</p>
</div>
<?php } ?>

<div class="card">
    <div class="card-body">

        <?php
		// ==========================================================
		// BAGIAN 2: Pesan peringatan khusus untuk 'pemilik'
		// ==========================================================
		if ($role == 'pemilik'):
			?>
        <div class="alert alert-warning">
            <strong>Mode Hanya Lihat:</strong> Anda login sebagai Pemilik, maka Anda tidak dapat mengubah pengaturan
            toko.
        </div>
        <?php endif; ?>

        <form method="post" action="fungsi/edit/edit.php?pengaturan=ubah">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Nama Toko</label>
                        <input class="form-control" name="namatoko" value="<?php echo $toko['nama_toko']; ?>"
                            placeholder="Nama Toko" <?php echo $disabled_attr; // BAGIAN 3: Tambahkan ini ?>>
                    </div>
                    <div class="form-group">
                        <label for="">Alamat Toko</label>
                        <input class="form-control" name="alamat" value="<?php echo $toko['alamat_toko']; ?>"
                            placeholder="Alamat Toko" <?php echo $disabled_attr; // BAGIAN 3: Tambahkan ini ?>>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Kontak (Hp)</label>
                        <input class="form-control" name="kontak" value="<?php echo $toko['tlp']; ?>"
                            placeholder="Kontak (Hp)" <?php echo $disabled_attr; // BAGIAN 3: Tambahkan ini ?>>
                    </div>
                    <div class="form-group">
                        <label for="">Nama Pemilik Toko</label>
                        <input class="form-control" name="pemilik" value="<?php echo $toko['nama_pemilik']; ?>"
                            placeholder="Nama Pemilik Toko" <?php echo $disabled_attr; // BAGIAN 3: Tambahkan ini ?>>
                    </div>
                </div>
            </div>
            <button id="tombol-simpan" class="btn btn-primary" <?php echo $disabled_attr; // BAGIAN 3: Tambahkan ini ?>>
                <i class="fas fa-edit"></i> Update Data
            </button>
        </form>
    </div>
</div>