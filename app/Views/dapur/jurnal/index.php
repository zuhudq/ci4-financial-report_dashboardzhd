<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="row mb-3">
    <div class="col-12 text-right">
        <a href="/dapur/jurnal/new" class="btn btn-primary shadow-sm font-weight-bold">
            <i class="fas fa-plus-circle mr-2"></i> Input Jurnal Baru
        </a>
    </div>
</div>

<div class="card shadow border-top-danger">
    <div class="card-header bg-white">
        <h3 class="card-title font-weight-bold text-dark"><i class="fas fa-book mr-2"></i> Daftar Transaksi (Jurnal Umum)</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="tabelJurnal" class="table table-bordered table-striped table-hover">
                <thead class="bg-danger text-white text-center">
                    <tr>
                        <th style="width: 15%">TANGGAL</th>
                        <th style="width: 45%">KETERANGAN / AKUN</th>
                        <th style="width: 15%">DEBIT (Rp)</th>
                        <th style="width: 15%">KREDIT (Rp)</th>
                        <th style="width: 10%">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $currentId = 0;
                    foreach ($jurnal as $row) :
                        $isNewHeader = ($row['id_jurnal'] != $currentId);
                        $currentId = $row['id_jurnal'];
                    ?>
                        <tr class="<?= $isNewHeader ? 'border-top-thick' : '' ?>">
                            <td class="text-center align-middle bg-white">
                                <?php if ($isNewHeader): ?>
                                    <span class="font-weight-bold text-dark"><?= date('d/m/Y', strtotime($row['tanggal_jurnal'])) ?></span>
                                <?php endif; ?>
                            </td>

                            <td class="align-middle">
                                <?php if ($isNewHeader): ?>
                                    <div class="font-weight-bold text-uppercase text-danger mb-1" style="font-size: 0.9rem;">
                                        <?= esc($row['deskripsi']) ?>
                                    </div>
                                <?php endif; ?>

                                <div style="<?= ($row['kredit'] > 0) ? 'padding-left: 30px;' : '' ?>">
                                    <span class="badge badge-light border"><?= $row['kode_akun'] ?></span>
                                    <?= esc($row['nama_akun']) ?>
                                </div>
                            </td>

                            <td class="text-right align-middle">
                                <?= ($row['debit'] > 0) ? number_format($row['debit'], 0, ',', '.') : '-' ?>
                            </td>

                            <td class="text-right align-middle">
                                <?= ($row['kredit'] > 0) ? number_format($row['kredit'], 0, ',', '.') : '-' ?>
                            </td>

                            <td class="text-center align-middle">
                                <?php if ($isNewHeader): ?>
                                    <a href="/jurnal/delete/<?= $row['id_jurnal'] ?>" class="btn btn-xs btn-outline-danger btn-hapus-transaksi" title="Hapus Transaksi">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if (empty($jurnal)): ?>
            <div class="text-center mt-4 mb-4">
                <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                <p class="text-muted">Belum ada transaksi yang dicatat.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('page_styles') ?>
<style>
    .border-top-thick {
        border-top: 2px solid #dee2e6 !important;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('page_scripts') ?>
<script>
    $(document).ready(function() {
        $('#tabelJurnal').DataTable({
            "ordering": false,
            "pageLength": 25,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            }
        });

        // SWEETALERT HAPUS TRANSAKSI
        // Kita pakai delegation (on document) karena datatable bisa mempaging (halaman 2, dst)
        $(document).on('click', '.btn-hapus-transaksi', function(e) {
            e.preventDefault();
            const href = $(this).attr('href');

            Swal.fire({
                title: 'Hapus Transaksi?',
                text: "Transaksi yang dihapus tidak dapat dikembalikan, dan saldo akun akan berubah!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.location.href = href;
                }
            })
        });
    });
</script>
<?= $this->endSection() ?>