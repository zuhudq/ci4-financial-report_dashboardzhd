<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
Laporan Neraca Saldo
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Neraca Saldo (Trial Balance)
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card shadow mb-4">
    <div class="card-header py-3 bg-white d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-balance-scale mr-2"></i> Periode: Awal Tahun 2024
        </h6>
        <div>
            <button onclick="window.print()" class="btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-print mr-1"></i> Cetak
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" width="100%" cellspacing="0">
                <thead class="bg-primary text-white text-center">
                    <tr>
                        <th style="width: 15%">Kode Akun</th>
                        <th>Nama Akun</th>
                        <th style="width: 20%">Debit (Rp)</th>
                        <th style="width: 20%">Kredit (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($neraca)) : ?>
                        <tr>
                            <td colspan="4" class="text-center py-4">Belum ada data akun. Silakan jalankan Seeder.</td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ($neraca as $row) : ?>
                            <?php if ($row['debit'] > 0 || $row['kredit'] > 0) : ?>
                                <tr>
                                    <td class="text-center font-weight-bold"><?= $row['kode_akun'] ?></td>
                                    <td><?= esc($row['nama_akun']) ?></td>

                                    <td class="text-right">
                                        <?php if ($row['debit'] > 0): ?>
                                            <?= number_format($row['debit'], 0, ',', '.') ?>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-right">
                                        <?php if ($row['kredit'] > 0): ?>
                                            <?= number_format($row['kredit'], 0, ',', '.') ?>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
                <tfoot class="font-weight-bold text-white">
                    <tr class="<?= $isBalanced ? 'bg-success' : 'bg-danger' ?>">
                        <td colspan="2" class="text-right text-uppercase">Total Neraca Saldo</td>
                        <td class="text-right">
                            Rp <?= number_format($totalDebit ?? 0, 0, ',', '.') ?>
                        </td>
                        <td class="text-right">
                            Rp <?= number_format($totalKredit ?? 0, 0, ',', '.') ?>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="4" class="text-center py-2 font-weight-bold <?= $isBalanced ? 'text-success' : 'text-danger' ?>" style="background-color: #f8f9fc;">
                            STATUS:
                            <?php if ($isBalanced): ?>
                                <i class="fas fa-check-circle"></i> SEIMBANG (BALANCE)
                            <?php else: ?>
                                <i class="fas fa-times-circle"></i> TIDAK SEIMBANG (Selisih: Rp <?= number_format(abs(($totalDebit ?? 0) - ($totalKredit ?? 0)), 0, ',', '.') ?>)
                            <?php endif; ?>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>