<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
Laba Rugi (Profit & Loss)
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Laporan Laba Rugi Komprehensif
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-white border-bottom">
        <h3 class="card-title font-weight-bold text-secondary"><i class="fas fa-filter mr-1"></i> Filter Periode Laporan</h3>
    </div>
    <div class="card-body py-3">
        <form action="/laporan/laba-rugi" method="get">
            <div class="row align-items-end">
                <div class="col-md-4">
                    <div class="form-group mb-2">
                        <label class="font-weight-bold">Tanggal Mulai:</label>
                        <input type="date" name="start_date" class="form-control" value="<?= esc($startDate) ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-2">
                        <label class="font-weight-bold">Tanggal Selesai:</label>
                        <input type="date" name="end_date" class="form-control" value="<?= esc($endDate) ?>">
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-block mb-2 font-weight-bold" style="background-color: #0d47a1; border-color: #0d47a1;">
                        <i class="fas fa-search mr-1"></i> Tampilkan
                    </button>
                </div>

                <?php if ($isFiltered) : ?>
                    <div class="col-md-2">
                        <div class="btn-group btn-block mb-2">
                            <button type="button" class="btn btn-success dropdown-toggle font-weight-bold" data-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-download mr-1"></i> Export
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="/laporan/cetak-laba-rugi?start_date=<?= $startDate ?>&end_date=<?= $endDate ?>" target="_blank">
                                    <i class="fas fa-file-pdf text-danger mr-2"></i> Download PDF
                                </a>
                                <a class="dropdown-item" href="/laporan/export-laba-rugi?start_date=<?= $startDate ?>&end_date=<?= $endDate ?>">
                                    <i class="fas fa-file-excel text-success mr-2"></i> Download Excel
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<?php if ($isFiltered) : ?>
    <div class="card shadow">
        <div class="card-header bg-white text-center py-4 border-0">
            <h3 class="font-weight-bold text-uppercase text-danger" style="letter-spacing: 1px;">PT MAYORA INDAH TBK</h3>
            <h4 class="font-weight-bold text-dark">LAPORAN LABA RUGI DAN PENGHASILAN KOMPREHENSIF LAIN</h4>
            <p class="text-muted mb-0">Untuk Periode yang Berakhir pada: <?= date('d F Y', strtotime($endDate)) ?></p>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th style="width: 70%;" class="pl-4 text-uppercase">KETERANGAN</th>
                            <th class="text-right pr-4 text-uppercase">JUMLAH (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="background-color: #e8f5e9;">
                            <td colspan="2" class="font-weight-bold text-success pl-4 py-2"><i class="fas fa-arrow-down mr-2"></i> PENDAPATAN USAHA</td>
                        </tr>
                        <?php foreach ($pendapatanDetails as $item) : ?>
                            <tr>
                                <td class="pl-5"><?= esc($item['nama_akun']) ?></td>
                                <td class="text-right pr-4 font-weight-bold text-dark">
                                    <?= number_format($item['balance'], 2, ',', '.') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <?php if (empty($pendapatanDetails)): ?>
                            <tr>
                                <td colspan="2" class="text-center text-muted font-italic">Tidak ada data pendapatan.</td>
                            </tr>
                        <?php endif; ?>

                        <tr style="border-top: 2px solid #c8e6c9;">
                            <td class="pl-4 font-weight-bold text-dark">TOTAL PENDAPATAN</td>
                            <td class="text-right pr-4 font-weight-bold text-success h5 mb-0">
                                <?= number_format($totalPendapatan, 2, ',', '.') ?>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2" style="height: 20px;"></td>
                        </tr>

                        <tr style="background-color: #ffebee;">
                            <td colspan="2" class="font-weight-bold text-danger pl-4 py-2"><i class="fas fa-arrow-up mr-2"></i> BEBAN OPERASIONAL</td>
                        </tr>
                        <?php foreach ($bebanDetails as $item) : ?>
                            <tr>
                                <td class="pl-5"><?= esc($item['nama_akun']) ?></td>
                                <td class="text-right pr-4 text-dark">
                                    (<?= number_format($item['balance'], 2, ',', '.') ?>)
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <?php if (empty($bebanDetails)): ?>
                            <tr>
                                <td colspan="2" class="text-center text-muted font-italic">Tidak ada data beban.</td>
                            </tr>
                        <?php endif; ?>

                        <tr style="border-top: 2px solid #ffcdd2;">
                            <td class="pl-4 font-weight-bold text-dark">TOTAL BEBAN</td>
                            <td class="text-right pr-4 font-weight-bold text-danger h5 mb-0">
                                (<?= number_format($totalBeban, 2, ',', '.') ?>)
                            </td>
                        </tr>

                        <tr class="<?= ($labaRugi >= 0) ? 'bg-success' : 'bg-danger' ?> text-white mt-4">
                            <td class="pl-4 py-3 h4 font-weight-bold text-uppercase mb-0" style="vertical-align: middle;">
                                <?= ($labaRugi >= 0) ? 'LABA BERSIH TAHUN BERJALAN' : 'RUGI BERSIH TAHUN BERJALAN' ?>
                            </td>
                            <td class="text-right pr-4 py-3 h3 font-weight-bold mb-0">
                                <?= number_format($labaRugi, 2, ',', '.') ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="row mt-5 pt-4">
                <div class="col-md-4 text-center offset-md-8">
                    <p class="mb-5">Jakarta, <?= date('d F Y') ?><br>Direktur Keuangan</p>
                    <p class="font-weight-bold text-uppercase" style="border-bottom: 1px solid #333; display: inline-block; padding: 0 20px;">( ..................................... )</p>
                </div>
            </div>

        </div>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>