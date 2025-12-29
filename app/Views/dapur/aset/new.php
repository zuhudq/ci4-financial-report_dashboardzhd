<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="card shadow border-top-primary">
    <div class="card-header bg-white">
        <h4 class="font-weight-bold text-primary">Form Registrasi Aset Tetap</h4>
    </div>
    <div class="card-body">
        <form action="/dapur/aset/create" method="post">
            <div class="row">
                <div class="col-md-6 border-right">
                    <h5 class="mb-3 text-secondary">Data Fisik Aset</h5>
                    <div class="form-group"><label>Nama Aset</label><input type="text" name="nama_aset" class="form-control" required placeholder="Contoh: Mesin Packing Rotary A-1"></div>
                    <div class="form-group"><label>Tanggal Pembelian</label><input type="date" name="tanggal_beli" class="form-control" required></div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group"><label>Harga Perolehan (Rp)</label><input type="text" name="harga_perolehan" class="form-control uang" required placeholder="0"></div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group"><label>Nilai Sisa / Residu (Rp)</label><input type="text" name="nilai_sisa" class="form-control uang" value="0"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Umur Ekonomis (Bulan)</label>
                        <input type="number" name="umur_ekonomis" class="form-control" required placeholder="Contoh: 48 (untuk 4 Tahun)">
                        <small class="text-muted">Metode Penyusutan: Garis Lurus (Straight Line)</small>
                    </div>
                </div>

                <div class="col-md-6 bg-light p-3 rounded">
                    <h5 class="mb-3 text-danger"><i class="fas fa-link mr-1"></i> Mapping Akun (COA)</h5>
                    <p class="small text-muted mb-3">Tentukan akun mana yang akan dipengaruhi oleh aset ini dalam jurnal otomatis.</p>

                    <div class="form-group">
                        <label>1. Akun Aset (Harta)</label>
                        <select name="akun_aset_id" class="form-control select2" required>
                            <option value="">-- Pilih Akun Aset Tetap --</option>
                            <?php foreach ($accounts as $acc): if (strpos($acc['kode_akun'], '1-2') === 0): ?>
                                    <option value="<?= $acc['id_akun'] ?>"><?= $acc['kode_akun'] . ' - ' . $acc['nama_akun'] ?></option>
                            <?php endif;
                            endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>2. Akun Akumulasi Penyusutan (Kredit)</label>
                        <select name="akun_akumulasi_id" class="form-control select2" required>
                            <option value="">-- Pilih Akun Akumulasi --</option>
                            <?php foreach ($accounts as $acc): if (stripos($acc['nama_akun'], 'Akumulasi') !== false): ?>
                                    <option value="<?= $acc['id_akun'] ?>"><?= $acc['kode_akun'] . ' - ' . $acc['nama_akun'] ?></option>
                            <?php endif;
                            endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>3. Akun Beban Penyusutan (Debit)</label>
                        <select name="akun_beban_id" class="form-control select2" required>
                            <option value="">-- Pilih Akun Beban --</option>
                            <?php foreach ($accounts as $acc): if (strpos($acc['kode_akun'], '6-') === 0): ?>
                                    <option value="<?= $acc['id_akun'] ?>"><?= $acc['kode_akun'] . ' - ' . $acc['nama_akun'] ?></option>
                            <?php endif;
                            endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            <hr>
            <button type="submit" class="btn btn-success btn-lg btn-block shadow"><i class="fas fa-save mr-2"></i> Simpan Aset Tetap</button>
        </form>
    </div>
</div>

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
<?= $this->endSection() ?>