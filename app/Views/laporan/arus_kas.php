<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
Arus Kas
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Laporan Arus Kas (Indirect Method)
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
            <h4 class="font-weight-bold text-dark">LAPORAN ARUS KAS KONSOLIDASIAN</h4>
            <p class="text-muted mb-0">Untuk Periode yang Berakhir pada: <?= date('d F Y', strtotime($endDate)) ?></p>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-sm">

                    <thead class="bg-secondary text-white">
                        <tr>
                            <th colspan="2">ARUS KAS DARI AKTIVITAS OPERASI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="pl-3 font-weight-bold">Laba (Rugi) Bersih</td>
                            <td class="text-right font-weight-bold"><?= number_format($labaBersih, 2, ',', '.') ?></td>
                        </tr>
                        <tr>
                            <td class="pl-4 text-muted"><i>Penyesuaian untuk:</i></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="pl-5">Penyusutan Aset Tetap</td>
                            <td class="text-right"><?= number_format($depresiasi, 2, ',', '.') ?></td>
                        </tr>
                        <tr>
                            <td class="pl-4 text-muted"><i>Perubahan Modal Kerja:</i></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="pl-5">Penurunan (Kenaikan) Piutang Usaha</td>
                            <td class="text-right"><?= number_format($changePiutang, 2, ',', '.') ?></td>
                        </tr>
                        <tr>
                            <td class="pl-5">Penurunan (Kenaikan) Persediaan</td>
                            <td class="text-right"><?= number_format($changePersediaan, 2, ',', '.') ?></td>
                        </tr>
                        <tr>
                            <td class="pl-5">Kenaikan (Penurunan) Utang Usaha</td>
                            <td class="text-right"><?= number_format($changeUtangUsaha, 2, ',', '.') ?></td>
                        </tr>
                        <tr style="background-color: #f1f8e9;">
                            <th class="pl-3 text-success">Kas Bersih Diperoleh dari Aktivitas Operasi</th>
                            <th class="text-right text-success"><?= number_format($arusKasOperasi, 2, ',', '.') ?></th>
                        </tr>
                    </tbody>

                    <thead class="bg-secondary text-white">
                        <tr>
                            <th colspan="2">ARUS KAS DARI AKTIVITAS INVESTASI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="pl-3">Perolehan Aset Tetap (Capex)</td>
                            <td class="text-right"><?= number_format($arusKasInvestasi, 2, ',', '.') ?></td>
                        </tr>
                        <tr style="background-color: #f1f8e9;">
                            <th class="pl-3 text-success">Kas Bersih Digunakan untuk Aktivitas Investasi</th>
                            <th class="text-right text-success"><?= number_format($arusKasInvestasi, 2, ',', '.') ?></th>
                        </tr>
                    </tbody>

                    <thead class="bg-secondary text-white">
                        <tr>
                            <th colspan="2">ARUS KAS DARI AKTIVITAS PENDANAAN</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="pl-3">Penerimaan (Pembayaran) Utang Jangka Panjang</td>
                            <td class="text-right"><?= number_format($changeUtangPanjang, 2, ',', '.') ?></td>
                        </tr>
                        <tr>
                            <td class="pl-3">Setoran Modal / Dividen</td>
                            <td class="text-right"><?= number_format($changeEkuitas, 2, ',', '.') ?></td>
                        </tr>
                        <tr style="background-color: #f1f8e9;">
                            <th class="pl-3 text-success">Kas Bersih Diperoleh dari Aktivitas Pendanaan</th>
                            <th class="text-right text-success"><?= number_format($arusKasPendanaan, 2, ',', '.') ?></th>
                        </tr>
                    </tbody>

                    <tfoot style="border-top: 3px solid #333;">
                        <tr>
                            <th class="text-uppercase pl-3">Kenaikan (Penurunan) Bersih Kas dan Setara Kas</th>
                            <th class="text-right"><?= number_format($kenaikanKasBersih, 2, ',', '.') ?></th>
                        </tr>
                        <tr>
                            <td class="pl-3">Kas dan Setara Kas Awal Periode</td>
                            <td class="text-right"><?= number_format($saldoAwalKas, 2, ',', '.') ?></td>
                        </tr>
                        <tr style="background-color: #ffebee; font-size: 1.1rem;">
                            <th class="text-uppercase pl-3 text-danger">KAS DAN SETARA KAS AKHIR PERIODE</th>
                            <th class="text-right text-danger"><?= number_format($saldoAkhirKas, 2, ',', '.') ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>
<?= $this->endSection() ?>