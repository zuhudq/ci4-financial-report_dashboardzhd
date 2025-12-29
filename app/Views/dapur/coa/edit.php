<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
Edit Akun
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<div class="row justify-content-center">
    <div class="col-md-12 col-lg-10">
        <div class="card shadow-sm">
            <div class="card-header" style="background-color: #b71c1c; color: white;">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-edit mr-2"></i> Edit Akun: <?= esc($account['nama_akun']) ?>
                </h3>
            </div>

            <form action="<?= base_url('dapur/coa/update/' . $account['id_akun']) ?>" method="post">
                <?= csrf_field() ?>

                <div class="card-body">
                    <?php if (session()->has('errors')) : ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <ul class="mb-0 pl-3">
                                <?php foreach (session('errors') as $error) : ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach ?>
                            </ul>
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    <?php endif ?>

                    <?php if (session()->has('error')) : ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            <strong>Perhatian!</strong> <?= session('error') ?>
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    <?php endif ?>

                    <div class="form-group row">
                        <label for="kode_akun" class="col-sm-3 col-form-label text-right font-weight-bold">Kode Akun</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="kode_akun" name="kode_akun" value="<?= old('kode_akun', $account['kode_akun']) ?>" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="nama_akun" class="col-sm-3 col-form-label text-right font-weight-bold">Nama Akun</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="nama_akun" name="nama_akun" value="<?= old('nama_akun', $account['nama_akun']) ?>" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="kategori" class="col-sm-3 col-form-label text-right font-weight-bold">Kategori Akun</label>
                        <div class="col-sm-9">
                            <select class="form-control" id="kategori" name="kategori">
                                <?php
                                $kategoriList = ['Aset', 'Liabilitas', 'Ekuitas', 'Pendapatan', 'Beban'];
                                foreach ($kategoriList as $kat) :
                                ?>
                                    <option value="<?= $kat ?>" <?= (old('kategori', $account['kategori']) == $kat) ? 'selected' : '' ?>>
                                        <?= $kat ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="saldo_awal" class="col-sm-3 col-form-label text-right font-weight-bold">Saldo Awal (Rp)</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text font-weight-bold">Rp</span>
                                </div>
                                <input type="text" class="form-control uang" id="saldo_awal" name="saldo_awal" value="<?= old('saldo_awal', number_format($account['saldo_awal'], 0, ',', '.')) ?>">
                            </div>
                            <small class="form-text text-muted mt-2">
                                <i class="fas fa-info-circle text-info mr-1"></i> Saldo awal per 1 Januari.
                            </small>
                        </div>
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-between align-items-center" style="background-color: #f8f9fa;">

                    <button type="button" class="btn btn-outline-danger" onclick="konfirmasiHapus(<?= $account['id_akun'] ?>)">
                        <i class="fas fa-trash-alt mr-1"></i> Hapus Akun
                    </button>

                    <div>
                        <a href="<?= base_url('dapur/coa') ?>" class="btn btn-secondary mr-2">
                            <i class="fas fa-arrow-left mr-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary px-4" style="background-color: #0277bd; border-color: #0277bd;">
                            <i class="fas fa-save mr-1"></i> Simpan Perubahan
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // 1. Masking Rupiah (Jalankan saat dokumen siap)
    $(document).ready(function() {
        $('.uang').mask('000.000.000.000.000', {
            reverse: true
        });
    });

    // 2. Fungsi Hapus (Dipanggil langsung oleh onclick tombol)
    function konfirmasiHapus(idAkun) {
        // Debugging: Cek apakah fungsi terpanggil
        console.log("Tombol hapus diklik untuk ID: " + idAkun);

        Swal.fire({
            title: 'Yakin Hapus Akun ini?',
            text: "Akun akan dihapus, dan akan memengaruhi data dan laporan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33', // Merah
            cancelButtonColor: '#3085d6', // Biru
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect ke URL Hapus yang Benar
                // Pastikan route ini ada di Routes.php
                window.location.href = "<?= base_url('dapur/coa/delete/') ?>/" + idAkun;
            }
        });
    }
</script>

<?= $this->endSection() ?>