<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
Chart of Accounts
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Daftar Akun (Chart of Accounts)
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card shadow border-top-danger">
    <div class="card-header" style="background-color: #b71c1c; color: white;">
        <h3 class="card-title font-weight-bold">
            <i class="fas fa-list mr-2"></i> Data Seluruh Akun
        </h3>
        <div class="card-tools">
            <a href="/dapur/coa/new" class="btn btn-light btn-sm font-weight-bold text-danger shadow">
                <i class="fas fa-plus-circle mr-1"></i> Tambah Akun Baru
            </a>
        </div>
    </div>

    <div class="card-body">
        <table id="data-table" class="table table-bordered table-striped table-hover">
            <thead class="bg-light text-center">
                <tr>
                    <th style="width: 10%;">KODE</th>
                    <th>NAMA AKUN</th>
                    <th style="width: 15%;">KATEGORI</th>
                    <th style="width: 20%;">SALDO AWAL (2024)</th>
                    <th style="width: 15%;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($accounts as $account) : ?>
                    <tr>
                        <td class="text-center align-middle">
                            <span class="badge badge-light border font-weight-bold" style="font-size: 0.95rem; font-family: monospace;">
                                <?= esc($account['kode_akun']) ?>
                            </span>
                        </td>
                        <td class="align-middle font-weight-bold text-dark">
                            <?= esc($account['nama_akun']) ?>
                        </td>
                        <td class="text-center align-middle">
                            <?php
                            // Logika Warna Badge berdasarkan Kategori
                            // PENTING: Kita pakai $account['kategori'] sesuai database baru
                            $badgeColor = 'secondary';
                            switch ($account['kategori']) {
                                case 'Aset':
                                    $badgeColor = 'success'; // Hijau
                                    break;
                                case 'Liabilitas':
                                    $badgeColor = 'warning'; // Kuning
                                    break;
                                case 'Ekuitas':
                                    $badgeColor = 'primary'; // Biru
                                    break;
                                case 'Pendapatan':
                                    $badgeColor = 'info'; // Biru Muda
                                    break;
                                case 'Beban':
                                    $badgeColor = 'danger'; // Merah
                                    break;
                            }
                            ?>
                            <span class="badge badge-<?= $badgeColor ?> px-3 py-2"><?= esc($account['kategori']) ?></span>
                        </td>

                        <td class="text-right align-middle">
                            <span style="font-family: monospace; font-size: 1rem;">
                                Rp <?= number_format($account['saldo_awal'], 0, ',', '.') ?>
                            </span>
                        </td>

                        <td class="text-center align-middle">
                            <div class="btn-group">
                                <a href="/dapur/coa/edit/<?= $account['id_akun'] ?>" class="btn btn-sm btn-warning" title="Edit Akun">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <a href="<?= base_url('laporan/buku-besar?akun_id=' . $account['id_akun']) ?>" class="btn btn-sm btn-info shadow-sm" title="Lihat Buku Besar">
                                    <i class="fas fa-book-open mr-1"></i> Rincian
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('page_scripts') ?>
<script>
    $(function() {
        // Inisialisasi DataTables
        $("#data-table").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "ordering": true,
            "pageLength": 25, // Default tampilkan 25 baris agar lebih enak dilihat
            "language": {
                "search": "Cari Akun:",
                "lengthMenu": "Tampilkan _MENU_ akun",
                "zeroRecords": "Data tidak ditemukan",
                "info": "Hal _PAGE_ dari _PAGES_",
                "infoEmpty": "Kosong",
                "paginate": {
                    "first": "Awal",
                    "last": "Akhir",
                    "next": ">",
                    "previous": "<"
                }
            }
        });
    });
</script>
<?= $this->endSection() ?>