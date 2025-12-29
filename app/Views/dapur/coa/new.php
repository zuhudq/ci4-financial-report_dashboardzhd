<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
Tambah Akun
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Tambah Akun Baru
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-top-primary">
            <div class="card-header bg-white">
                <h3 class="card-title font-weight-bold text-primary">
                    <i class="fas fa-plus-circle mr-2"></i> Form Akun Baru
                </h3>
            </div>
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

                <form action="/dapur/coa/create" method="post">
                    <?= csrf_field() ?>

                    <div class="form-group">
                        <label class="font-weight-bold">Kode Akun <span class="text-danger">*</span></label>
                        <input type="text" name="kode_akun" class="form-control" placeholder="Contoh: 1-1101" value="<?= old('kode_akun') ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Nama Akun <span class="text-danger">*</span></label>
                        <input type="text" name="nama_akun" class="form-control" placeholder="Contoh: Kas Kecil" value="<?= old('nama_akun') ?>" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Kategori <span class="text-danger">*</span></label>
                                <select name="kategori" class="form-control select2" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <option value="Aset" <?= old('kategori') == 'Aset' ? 'selected' : '' ?>>Aset</option>
                                    <option value="Liabilitas" <?= old('kategori') == 'Liabilitas' ? 'selected' : '' ?>>Liabilitas</option>
                                    <option value="Ekuitas" <?= old('kategori') == 'Ekuitas' ? 'selected' : '' ?>>Ekuitas</option>
                                    <option value="Pendapatan" <?= old('kategori') == 'Pendapatan' ? 'selected' : '' ?>>Pendapatan</option>
                                    <option value="Beban" <?= old('kategori') == 'Beban' ? 'selected' : '' ?>>Beban</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Saldo Awal (Rp)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="text" name="saldo_awal" class="form-control uang" value="<?= old('saldo_awal', 0) ?>">
                                </div>
                                <small class="text-muted">Isi 0 jika tidak ada saldo awal.</small>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <div class="d-flex justify-content-between">
                        <a href="/dapur/coa" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary font-weight-bold shadow">
                            <i class="fas fa-save mr-1"></i> Simpan Akun
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('page_scripts') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script>
    $(document).ready(function() {
        $('.uang').mask('000.000.000.000.000', {
            reverse: true
        });
    });
</script>
<?= $this->endSection() ?>