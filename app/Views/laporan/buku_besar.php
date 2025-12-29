<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
Buku Besar
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Buku Besar (General Ledger)
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-white border-bottom">
        <h3 class="card-title font-weight-bold text-secondary"><i class="fas fa-search mr-1"></i> Filter Akun & Periode</h3>
    </div>
    <div class="card-body py-3">
        <form action="" method="get">
            <div class="row align-items-end">
                <div class="col-md-5">
                    <div class="form-group mb-2">
                        <label class="font-weight-bold">Pilih Akun:</label>
                        <select name="akun_id" id="akun_id" class="form-control select2" onchange="this.form.submit()">
                            <option value="">-- Pilih Akun --</option>
                            <?php foreach ($accounts as $acc) : ?>
                                <option value="<?= $acc['id_akun'] ?>" <?= ($accountId == $acc['id_akun']) ? 'selected' : '' ?>>
                                    <?= $acc['kode_akun'] ?> - <?= $acc['nama_akun'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group mb-2">
                        <label class="font-weight-bold">Periode:</label>
                        <div class="input-group">
                            <input type="date" name="start_date" class="form-control" value="<?= $startDate ?>">
                            <div class="input-group-prepend input-group-append">
                                <span class="input-group-text font-weight-bold">s/d</span>
                            </div>
                            <input type="date" name="end_date" class="form-control" value="<?= $endDate ?>">
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-block mb-2 font-weight-bold" style="background-color: #0d47a1; border:none;">
                        <i class="fas fa-filter mr-1"></i> Tampilkan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php if ($selectedAccount) : ?>
    <div class="card shadow">
        <div class="card-header bg-white p-4 border-bottom">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h5 class="text-uppercase text-muted mb-1" style="font-size: 0.9rem; letter-spacing: 1px;">Buku Besar Akun</h5>
                    <h2 class="font-weight-bold text-dark mb-0">
                        <span class="text-primary mr-2"><?= esc($selectedAccount['kode_akun']) ?></span>
                        <?= esc($selectedAccount['nama_akun']) ?>
                    </h2>
                    <span class="badge badge-light border mt-2">Kategori: <?= esc($selectedAccount['kategori']) ?></span>
                </div>
                <div class="col-md-4 text-right mt-3 mt-md-0">
                    <div class="p-3 rounded" style="background-color: #e3f2fd;">
                        <h6 class="text-secondary mb-1 font-weight-bold text-uppercase" style="font-size: 0.8rem;">Saldo Awal Periode</h6>
                        <h3 class="font-weight-bold text-primary mb-0">Rp <?= number_format($saldoAwal, 2, ',', '.') ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="text-white" style="background-color: #37474f;">
                        <tr>
                            <th style="width: 15%;" class="text-center">Tanggal</th>
                            <th>Keterangan Transaksi</th>
                            <th class="text-right" style="width: 18%;">Debit (Rp)</th>
                            <th class="text-right" style="width: 18%;">Kredit (Rp)</th>
                            <th class="text-right" style="width: 18%;">Saldo (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-light font-italic text-muted">
                            <td class="text-center"><?= date('d/m/Y', strtotime($startDate)) ?></td>
                            <td><strong>Saldo Awal</strong></td>
                            <td class="text-right">-</td>
                            <td class="text-right">-</td>
                            <td class="text-right font-weight-bold text-dark"><?= number_format($saldoAwal, 2, ',', '.') ?></td>
                        </tr>

                        <?php
                        $saldoBerjalan = $saldoAwal;
                        $totalDebit = 0;
                        $totalKredit = 0;
                        $isNormalDebit = in_array($selectedAccount['kategori'], ['Aset', 'Beban']);

                        foreach ($transaksi as $row) :
                            $totalDebit += $row['debit'];
                            $totalKredit += $row['kredit'];

                            if ($isNormalDebit) {
                                $saldoBerjalan += ($row['debit'] - $row['kredit']);
                            } else {
                                $saldoBerjalan += ($row['kredit'] - $row['debit']);
                            }
                        ?>
                            <tr>
                                <td class="text-center"><?= date('d/m/Y', strtotime($row['tanggal_jurnal'])) ?></td>
                                <td><?= esc($row['ket_header']) ?></td>
                                <td class="text-right text-success font-weight-bold">
                                    <?= $row['debit'] > 0 ? number_format($row['debit'], 2, ',', '.') : '-' ?>
                                </td>
                                <td class="text-right text-danger font-weight-bold">
                                    <?= $row['kredit'] > 0 ? number_format($row['kredit'], 2, ',', '.') : '-' ?>
                                </td>
                                <td class="text-right font-weight-bold text-dark">
                                    <?= number_format($saldoBerjalan, 2, ',', '.') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <?php if (empty($transaksi)) : ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                    <i class="fas fa-info-circle mr-2"></i> Tidak ada transaksi pada periode ini.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                    <tfoot style="background-color: #eceff1; border-top: 2px solid #37474f;">
                        <tr>
                            <td colspan="2" class="text-right font-weight-bold text-uppercase pt-3">TOTAL MUTASI</td>
                            <td class="text-right text-success font-weight-bold pt-3" style="font-size: 1.1em;">
                                <?= number_format($totalDebit, 2, ',', '.') ?>
                            </td>
                            <td class="text-right text-danger font-weight-bold pt-3" style="font-size: 1.1em;">
                                <?= number_format($totalKredit, 2, ',', '.') ?>
                            </td>
                            <td class="text-right font-weight-bold text-dark pt-3" style="font-size: 1.2em;">
                                <?= number_format($saldoBerjalan, 2, ',', '.') ?>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="card-footer bg-white text-right">
            <a href="/laporan/cetak-buku-besar?akun_id=<?= $accountId ?>&start_date=<?= $startDate ?>&end_date=<?= $endDate ?>" target="_blank" class="btn btn-danger font-weight-bold">
                <i class="fas fa-file-pdf mr-1"></i> Cetak Laporan PDF
            </a>
        </div>
    </div>

<?php else : ?>
    <div class="card border-0 shadow-sm" style="background-color: #f8f9fa; border: 2px dashed #ccc !important;">
        <div class="card-body text-center py-5">
            <div class="text-muted mb-3">
                <i class="fas fa-book-open fa-4x" style="opacity: 0.3;"></i>
            </div>
            <h4 class="text-muted font-weight-bold">Silakan Pilih Akun Terlebih Dahulu</h4>
            <p class="text-black-50">Gunakan filter di atas untuk menampilkan rincian transaksi buku besar.</p>
        </div>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>