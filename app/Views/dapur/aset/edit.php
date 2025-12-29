<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
Edit Aset
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card shadow">
            <div class="card-header bg-warning">
                <h3 class="card-title font-weight-bold text-dark">
                    <i class="fas fa-edit mr-2"></i> Edit Aset: <?= esc($aset['nama_aset']) ?>
                </h3>
            </div>

            <form action="/dapur/aset/update/<?= $aset['id_aset'] ?>" method="post">
                <?= csrf_field() ?>

                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label font-weight-bold">Nama Aset</label>
                        <div class="col-sm-9">
                            <input type="text" name="nama_aset" class="form-control" value="<?= esc($aset['nama_aset']) ?>" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label font-weight-bold">Tanggal Beli</label>
                        <div class="col-sm-4">
                            <input type="date" name="tanggal_beli" class="form-control" value="<?= $aset['tanggal_beli'] ?>" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label font-weight-bold">Harga Perolehan</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                                <input type="text" name="harga_perolehan" class="form-control uang" value="<?= $aset['harga_perolehan'] ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label font-weight-bold">Nilai Sisa (Residu)</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                                <input type="text" name="nilai_sisa" class="form-control uang" value="<?= $aset['nilai_sisa'] ?>">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label font-weight-bold">Umur Ekonomis</label>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="number" name="umur_ekonomis" class="form-control" value="<?= $aset['umur_ekonomis'] ?>">
                                <div class="input-group-append"><span class="input-group-text">Bulan</span></div>
                            </div>
                            <small class="text-muted">Isi 0 jika tidak disusutkan (Tanah).</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label font-weight-bold">Metode</label>
                        <div class="col-sm-9">
                            <select name="metode" class="form-control">
                                <option value="Garis Lurus" <?= $aset['metode'] == 'Garis Lurus' ? 'selected' : '' ?>>Garis Lurus</option>
                                <option value="Tidak Disusutkan" <?= $aset['metode'] == 'Tidak Disusutkan' ? 'selected' : '' ?>>Tidak Disusutkan (Tanah)</option>
                            </select>
                        </div>
                    </div>

                    <hr>
                    <h6 class="font-weight-bold text-primary mb-3">Mapping Akun Akuntansi</h6>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Akun Aset</label>
                        <div class="col-sm-9">
                            <select name="akun_aset_id" class="form-control">
                                <?php foreach ($akunAset as $acc): ?>
                                    <option value="<?= $acc['id_akun'] ?>" <?= $aset['akun_aset_id'] == $acc['id_akun'] ? 'selected' : '' ?>>
                                        <?= $acc['kode_akun'] ?> - <?= $acc['nama_akun'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Akun Akumulasi</label>
                        <div class="col-sm-9">
                            <select name="akun_akumulasi_id" class="form-control">
                                <option value="0">-- Pilih --</option>
                                <?php foreach ($akunAkumulasi as $acc): ?>
                                    <option value="<?= $acc['id_akun'] ?>" <?= $aset['akun_akumulasi_id'] == $acc['id_akun'] ? 'selected' : '' ?>>
                                        <?= $acc['kode_akun'] ?> - <?= $acc['nama_akun'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Akun Beban</label>
                        <div class="col-sm-9">
                            <select name="akun_beban_id" class="form-control">
                                <option value="0">-- Pilih --</option>
                                <?php foreach ($akunBeban as $acc): ?>
                                    <option value="<?= $acc['id_akun'] ?>" <?= $aset['akun_beban_id'] == $acc['id_akun'] ? 'selected' : '' ?>>
                                        <?= $acc['kode_akun'] ?> - <?= $acc['nama_akun'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                </div>

                <div class="card-footer d-flex justify-content-between align-items-center bg-light">
                    <button type="button" class="btn btn-outline-danger" onclick="konfirmasiHapus(<?= $aset['id_aset'] ?>)">
                        <i class="fas fa-trash mr-1"></i> Hapus Aset Ini
                    </button>

                    <div>
                        <a href="/dapur/aset" class="btn btn-secondary mr-2">Batal</a>
                        <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save mr-1"></i> Simpan Perubahan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.uang').mask('000.000.000.000.000', {
            reverse: true
        });
    });

    function konfirmasiHapus(id) {
        Swal.fire({
            title: 'HAPUS ASET PERMANEN?',
            text: "Data aset ini akan hilang dari database!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "/dapur/aset/delete/" + id;
            }
        })
    }
</script>
<?= $this->endSection() ?>