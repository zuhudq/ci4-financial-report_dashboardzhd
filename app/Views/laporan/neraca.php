<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
Laporan Posisi Keuangan
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Laporan Posisi Keuangan (Balance Sheet)
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row mb-3">
    <div class="col-md-12 text-right">
        <button onclick="window.print()" class="btn btn-secondary shadow-sm"><i class="fas fa-print"></i> Cetak Laporan</button>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card shadow mb-4 border-left-primary">
            <div class="card-header py-3 bg-primary text-white">
                <h6 class="m-0 font-weight-bold"><i class="fas fa-wallet mr-2"></i> ASET (ASSETS)</h6>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm table-hover mb-0" style="font-size: 0.9rem;">
                    <thead class="bg-light">
                        <tr>
                            <th colspan="2" class="pl-3 text-primary">ASET LANCAR</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($asetLancar as $row): ?>
                            <tr>
                                <td class="pl-4"><?= esc($row['nama_akun']) ?> <span class="text-muted small">(<?= $row['kode_akun'] ?>)</span></td>
                                <td class="text-right pr-3">Rp <?= number_format($row['saldo_awal'], 0, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr class="font-weight-bold bg-gray-100">
                            <td class="pl-3 text-uppercase">Total Aset Lancar</td>
                            <td class="text-right pr-3 text-primary">Rp <?= number_format($totalAsetLancar, 0, ',', '.') ?></td>
                        </tr>
                    </tbody>

                    <thead class="bg-light border-top">
                        <tr>
                            <th colspan="2" class="pl-3 text-primary">ASET TIDAK LANCAR</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($asetTidakLancar as $row): ?>
                            <tr>
                                <td class="pl-4"><?= esc($row['nama_akun']) ?> <span class="text-muted small">(<?= $row['kode_akun'] ?>)</span></td>
                                <td class="text-right pr-3">
                                    <?php if ($row['saldo_awal'] < 0): ?>
                                        <span class="text-danger">(Rp <?= number_format(abs($row['saldo_awal']), 0, ',', '.') ?>)</span>
                                    <?php else: ?>
                                        Rp <?= number_format($row['saldo_awal'], 0, ',', '.') ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <tr class="font-weight-bold bg-gray-100">
                            <td class="pl-3 text-uppercase">Total Aset Tidak Lancar</td>
                            <td class="text-right pr-3 text-primary">Rp <?= number_format($totalAsetTidakLancar, 0, ',', '.') ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white border-top-primary pt-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="font-weight-bold text-primary m-0">TOTAL ASET</h5>
                    <h5 class="font-weight-bold text-primary m-0">Rp <?= number_format($totalAset, 0, ',', '.') ?></h5>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow mb-4 border-left-danger">
            <div class="card-header py-3 bg-danger text-white">
                <h6 class="m-0 font-weight-bold"><i class="fas fa-hand-holding-usd mr-2"></i> LIABILITAS & EKUITAS</h6>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm table-hover mb-0" style="font-size: 0.9rem;">
                    <thead class="bg-light">
                        <tr>
                            <th colspan="2" class="pl-3 text-dark">LIABILITAS JANGKA PENDEK</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($liabilitasPendek as $row): ?>
                            <tr>
                                <td class="pl-4"><?= esc($row['nama_akun']) ?> <span class="text-muted small">(<?= $row['kode_akun'] ?>)</span></td>
                                <td class="text-right pr-3">Rp <?= number_format($row['saldo_awal'], 0, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr class="font-weight-bold bg-gray-100">
                            <td class="pl-3 text-uppercase">Total Liabilitas Jangka Pendek</td>
                            <td class="text-right pr-3 text-danger">Rp <?= number_format($totalLiabilitasPendek, 0, ',', '.') ?></td>
                        </tr>
                    </tbody>

                    <thead class="bg-light border-top">
                        <tr>
                            <th colspan="2" class="pl-3 text-dark">LIABILITAS JANGKA PANJANG</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($liabilitasPanjang as $row): ?>
                            <tr>
                                <td class="pl-4"><?= esc($row['nama_akun']) ?> <span class="text-muted small">(<?= $row['kode_akun'] ?>)</span></td>
                                <td class="text-right pr-3">Rp <?= number_format($row['saldo_awal'], 0, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr class="font-weight-bold bg-gray-100">
                            <td class="pl-3 text-uppercase">Total Liabilitas Jangka Panjang</td>
                            <td class="text-right pr-3 text-danger">Rp <?= number_format($totalLiabilitasPanjang, 0, ',', '.') ?></td>
                        </tr>
                    </tbody>

                    <thead class="bg-light border-top">
                        <tr>
                            <th colspan="2" class="pl-3 text-success">EKUITAS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ekuitas as $row): ?>
                            <tr class="<?= ($row['nama_akun'] == 'Saldo Laba (Retained Earnings)') ? 'bg-warning text-dark font-weight-bold' : '' ?>">
                                <td class="pl-4"><?= esc($row['nama_akun']) ?> <span class="text-muted small">(<?= $row['kode_akun'] ?>)</span></td>
                                <td class="text-right pr-3">Rp <?= number_format($row['saldo_awal'], 0, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr class="font-weight-bold bg-gray-100">
                            <td class="pl-3 text-uppercase">Total Ekuitas</td>
                            <td class="text-right pr-3 text-success">Rp <?= number_format($totalEkuitas, 0, ',', '.') ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white border-top-danger pt-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="font-weight-bold text-danger m-0">TOTAL LIABILITAS & EKUITAS</h5>
                    <h5 class="font-weight-bold text-danger m-0">Rp <?= number_format($totalPasiva, 0, ',', '.') ?></h5>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow mb-4 text-center border-bottom-<?= $isBalanced ? 'success' : 'danger' ?>">
            <div class="card-body py-4">
                <?php if ($isBalanced): ?>
                    <div class="text-success">
                        <i class="fas fa-check-circle fa-4x mb-3"></i>
                        <h2 class="font-weight-bold">NERACA SEIMBANG (BALANCE)</h2>
                        <p class="mb-0 text-muted">Posisi keuangan perusahaan dalam keadaan sehat secara administratif.</p>
                    </div>
                <?php else: ?>
                    <div class="text-danger">
                        <i class="fas fa-times-circle fa-4x mb-3"></i>
                        <h2 class="font-weight-bold">TIDAK SEIMBANG!</h2>
                        <h4 class="font-weight-bold mt-2">Selisih: Rp <?= number_format(abs($totalAset - $totalPasiva), 0, ',', '.') ?></h4>
                        <p class="mb-0 text-muted">Silakan cek ulang Jurnal Umum atau data Saldo Awal.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>