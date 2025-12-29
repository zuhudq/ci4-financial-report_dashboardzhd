<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
Harga Pokok Produksi
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Laporan Pabrik (Manufacturing Cost)
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card shadow-sm mb-4">
    <div class="card-body py-3">
        <form action="" method="get" class="form-inline justify-content-center">
            <label class="mr-2 font-weight-bold">Periode Produksi:</label>
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
            <h4 class="font-weight-bold text-dark">LAPORAN HARGA POKOK PRODUKSI</h4>
            <p class="text-muted mb-0">Periode: <?= date('d M Y', strtotime($startDate)) ?> s/d <?= date('d M Y', strtotime($endDate)) ?></p>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th style="width: 70%" class="text-uppercase pl-4">Keterangan</th>
                            <th class="text-right pr-4 text-uppercase">Jumlah (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="pl-4 font-weight-bold">Pemakaian Bahan Baku</td>
                            <td class="text-right pr-4"><?= number_format($biayaBahanBaku, 2, ',', '.') ?></td>
                        </tr>

                        <tr>
                            <td class="pl-4 font-weight-bold">Biaya Tenaga Kerja Langsung</td>
                            <td class="text-right pr-4"><?= number_format($biayaTenagaKerja, 2, ',', '.') ?></td>
                        </tr>

                        <tr>
                            <td class="pl-4 font-weight-bold">Biaya Overhead Pabrik</td>
                            <td class="text-right pr-4"><?= number_format($biayaOverhead, 2, ',', '.') ?></td>
                        </tr>

                        <tr style="background-color: #e3f2fd;">
                            <th class="pl-4 text-primary">TOTAL BIAYA PRODUKSI</th>
                            <th class="text-right pr-4 text-primary"><?= number_format($totalBiayaProduksi, 2, ',', '.') ?></th>
                        </tr>

                        <tr>
                            <td colspan="2" style="height: 15px;"></td>
                        </tr>

                        <tr>
                            <td class="pl-4">Ditambah: Persediaan Barang Dalam Proses Awal</td>
                            <td class="text-right pr-4"><?= number_format($wipAwal, 2, ',', '.') ?></td>
                        </tr>
                        <tr>
                            <td class="pl-4">Dikurangi: Persediaan Barang Dalam Proses Akhir</td>
                            <td class="text-right pr-4">(<?= number_format($wipAkhir, 2, ',', '.') ?>)</td>
                        </tr>

                        <tr class="bg-danger text-white mt-3">
                            <th class="pl-4 py-3 h5 text-uppercase">HARGA POKOK PRODUKSI (COGM)</th>
                            <th class="text-right pr-4 py-3 h4"><?= number_format($cogm, 2, ',', '.') ?></th>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="alert alert-info mt-4">
                <i class="fas fa-info-circle mr-2"></i>
                <strong>Catatan:</strong> Angka <strong>Harga Pokok Produksi</strong> ini nantinya akan mengalir ke Laporan Laba Rugi sebagai komponen utama Harga Pokok Penjualan (HPP).
            </div>
        </div>
    </div>
<?php endif; ?>
<?= $this->endSection() ?>