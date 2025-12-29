<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
Manajemen Aset Tetap
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Daftar Aset Tetap
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <?php if (session()->getFlashdata('success')) : ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle mr-2"></i>
                <?= session()->getFlashdata('success'); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')) : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <?= session()->getFlashdata('error'); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('warning')) : ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="fas fa-info-circle mr-2"></i>
                <?= session()->getFlashdata('warning'); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow border-top-primary">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h3 class="card-title font-weight-bold text-primary">
                    <i class="fas fa-boxes mr-2"></i> Data Aset Tetap (2024)
                </h3>
                <div>
                    <button type="button" class="btn btn-info font-weight-bold shadow-sm mr-2" data-toggle="modal" data-target="#modalRiwayat">
                        <i class="fas fa-history mr-1"></i> Riwayat & Reset
                    </button>

                    <button type="button" class="btn btn-warning font-weight-bold shadow-sm mr-2" data-toggle="modal" data-target="#modalGenerate">
                        <i class="fas fa-calculator mr-1"></i> Hitung Penyusutan
                    </button>

                    <a href="/dapur/aset/new" class="btn btn-primary font-weight-bold shadow-sm">
                        <i class="fas fa-plus-circle mr-1"></i> Tambah Aset
                    </a>
                </div>
            </div>
            <div class="card-body table-responsive">
                <table id="tabel-aset" class="table table-hover table-striped table-bordered">
                    <thead class="bg-light text-center">
                        <tr>
                            <th style="width: 5%">No</th>
                            <th>Nama Aset</th>
                            <th>Tgl Beli</th>
                            <th>Harga Perolehan</th>
                            <th>Nilai Sisa</th>
                            <th>Umur (Bln)</th>
                            <th>Penyusutan/Bln</th>
                            <th style="width: 10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($assets)) : ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">Belum ada data aset tetap.</td>
                            </tr>
                        <?php else : ?>
                            <?php $i = 1;
                            foreach ($assets as $a) : ?>
                                <?php
                                // Logika Pengaman Division by Zero
                                if ($a['umur_ekonomis'] > 0) {
                                    $penyusutanPerBulan = ($a['harga_perolehan'] - $a['nilai_sisa']) / $a['umur_ekonomis'];
                                } else {
                                    $penyusutanPerBulan = 0;
                                }
                                ?>
                                <tr>
                                    <td class="text-center align-middle"><?= $i++ ?></td>
                                    <td class="align-middle">
                                        <span class="font-weight-bold"><?= esc($a['nama_aset']) ?></span><br>
                                        <small class="text-muted">Metode: <?= esc($a['metode']) ?></small><br>
                                        <small class="text-info"><i class="fas fa-tag"></i> <?= $a['nama_aset_akun'] ?? '-' ?></small>
                                    </td>
                                    <td class="text-center align-middle"><?= date('d/m/Y', strtotime($a['tanggal_beli'])) ?></td>
                                    <td class="text-right align-middle">Rp <?= number_format($a['harga_perolehan'], 0, ',', '.') ?></td>
                                    <td class="text-right align-middle">Rp <?= number_format($a['nilai_sisa'], 0, ',', '.') ?></td>
                                    <td class="text-center align-middle">
                                        <?php if ($a['umur_ekonomis'] > 0): ?>
                                            <span class="badge badge-info"><?= $a['umur_ekonomis'] ?> Bln</span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary">∞</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-right align-middle font-weight-bold text-danger">
                                        Rp <?= number_format($penyusutanPerBulan, 0, ',', '.') ?>
                                    </td>
                                    <td class="text-center align-middle">
                                        <a href="/dapur/aset/edit/<?= $a['id_aset'] ?>" class="btn btn-sm btn-warning shadow-sm" title="Edit / Detail">
                                            <i class="fas fa-pencil-alt mr-1"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalGenerate" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title font-weight-bold text-dark"><i class="fas fa-calculator mr-2"></i>Generate Jurnal Penyusutan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="/dapur/aset/generate" method="post">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-1"></i> Sistem akan menghitung penyusutan <b>untuk periode yang dipilih</b> berdasarkan data aset yang ada.
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Pilih Periode (Bulan/Tahun):</label>
                        <input type="month" name="periode" class="form-control" value="<?= date('Y-m') ?>" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Proses Sekarang</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalRiwayat" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-history mr-2"></i>Riwayat Jurnal Penyusutan</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>No. Jurnal</th>
                                <th>Tanggal</th>
                                <th>Deskripsi</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($riwayat)): ?>
                                <tr>
                                    <td colspan="4" class="text-center py-3 text-muted">Belum ada penyusutan yang di-generate.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($riwayat as $row): ?>
                                    <tr>
                                        <td class="align-middle font-weight-bold text-primary pl-3">#<?= $row['id_jurnal'] ?></td>
                                        <td class="align-middle"><?= date('d M Y', strtotime($row['tanggal_jurnal'])) ?></td>
                                        <td class="align-middle"><?= esc($row['deskripsi']) ?></td>
                                        <td class="text-right pr-3">
                                            <button type="button" onclick="hapusPenyusutan('<?= $row['id_jurnal'] ?>')" class="btn btn-sm btn-outline-danger" title="Hapus/Reset Periode Ini">
                                                <i class="fas fa-trash-alt mr-1"></i> Hapus
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('page_scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $('#tabel-aset').DataTable({
            "responsive": true,
            "autoWidth": false,
            "language": {
                "emptyTable": "Tidak ada data aset"
            }
        });
    });

    // FUNGSI GLOBAL: Hapus Riwayat Penyusutan
    // Ditaruh di luar $(document).ready agar bisa dipanggil oleh onclick
    function hapusPenyusutan(idJurnal) {
        // Debugging di Console Browser
        console.log("Hapus ID Jurnal:", idJurnal);

        Swal.fire({
            title: 'Reset Penyusutan?',
            text: "Anda akan menghapus Jurnal Penyusutan ID: " + idJurnal + ". Data penyusutan bulan tersebut akan hilang dari laporan dan bisa di-generate ulang.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus Jurnal!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect ke URL Reset
                window.location.href = "<?= base_url('dapur/aset/reset-penyusutan/') ?>" + idJurnal;
            }
        })
    }
</script>
<?= $this->endSection() ?>