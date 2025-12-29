<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
Perubahan Ekuitas
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Laporan Perubahan Ekuitas
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card shadow-sm mb-4">
    <div class="card-body py-3">
        <form action="" method="get" class="form-inline justify-content-center">
            <label class="mr-2 font-weight-bold">Periode:</label>
            <input type="date" name="start_date" class="form-control mr-2" value="<?= esc($startDate) ?>">
            <span class="mr-2">s/d</span>
            <input type="date" name="end_date" class="form-control mr-2" value="<?= esc($endDate) ?>">
            <button type="submit" class="btn btn-primary" style="background-color: #b71c1c;">Tampilkan</button>
        </form>
    </div>
</div>

<?php if ($isFiltered) : ?>
    <div class="card shadow">
        <div class="card-header bg-white text-center py-4 border-0">
            <h3 class="font-weight-bold text-uppercase text-danger" style="letter-spacing: 1px;">PT MAYORA INDAH TBK</h3>
            <h4 class="font-weight-bold text-dark">LAPORAN PERUBAHAN EKUITAS</h4>
            <p class="text-muted mb-0">Periode: <?= date('d M Y', strtotime($startDate)) ?> s/d <?= date('d M Y', strtotime($endDate)) ?></p>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="bg-light">
                        <tr>
                            <th style="width: 70%">KETERANGAN</th>
                            <th class="text-right">JUMLAH (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="font-weight-bold">Saldo Awal Ekuitas</td>
                            <td class="text-right font-weight-bold"><?= number_format($modalAwal, 2, ',', '.') ?></td>
                        </tr>

                        <tr>
                            <td class="pl-4 text-success">Ditambah: Laba Bersih Periode Berjalan</td>
                            <td class="text-right text-success"><?= number_format($labaBersih, 2, ',', '.') ?></td>
                        </tr>

                        <?php foreach ($mutasiEkuitas as $m): ?>
                            <tr>
                                <td class="pl-4"><?= $m['nominal'] < 0 ? 'Dikurangi: ' : 'Ditambah: ' ?> <?= esc($m['nama_akun']) ?></td>
                                <td class="text-right"><?= number_format($m['nominal'], 2, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>

                    </tbody>
                    <tfoot style="background-color: #ffebee; border-top: 2px solid #b71c1c;">
                        <tr>
                            <th class="text-uppercase text-danger">SALDO AKHIR EKUITAS</th>
                            <th class="text-right text-danger" style="font-size: 1.2rem;"><?= number_format($modalAkhir, 2, ',', '.') ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>
<?= $this->endSection() ?>