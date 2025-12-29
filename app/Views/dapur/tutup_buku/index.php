<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
Tutup Buku
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Proses Tutup Buku (Closing Entries)
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-lg border-top-danger">
            <div class="card-header bg-white text-center py-4">
                <i class="fas fa-lock fa-4x text-danger mb-3"></i>
                <h3 class="font-weight-bold">Tutup Buku Bulanan</h3>
                <p class="text-muted">Fitur ini akan memindahkan Laba/Rugi periode berjalan ke Saldo Laba dan me-reset akun Pendapatan & Beban menjadi nol.</p>
            </div>
            <div class="card-body">
                <form action="/dapur/tutup-buku/proses" method="post">
                    <div class="form-group">
                        <label class="font-weight-bold">Pilih Bulan:</label>
                        <select name="bulan" class="form-control" required>
                            <?php
                            $bulanIndo = ['01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'];
                            foreach ($bulanIndo as $k => $v): ?>
                                <option value="<?= $k ?>" <?= (date('m') == $k) ? 'selected' : '' ?>><?= $v ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Pilih Tahun:</label>
                        <select name="tahun" class="form-control" required>
                            <?php for ($i = date('Y'); $i >= 2023; $i--): ?>
                                <option value="<?= $i ?>"><?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="alert alert-warning mt-4">
                        <i class="fas fa-exclamation-triangle mr-2"></i> <strong>Peringatan:</strong> Proses ini tidak dapat dibatalkan. Pastikan semua transaksi bulan tersebut sudah diinput.
                    </div>

                    <button type="submit" class="btn btn-danger btn-block btn-lg font-weight-bold shadow">
                        <i class="fas fa-gavel mr-2"></i> PROSES TUTUP BUKU
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>