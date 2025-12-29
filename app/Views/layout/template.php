<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PT Mayora Indah Tbk | Financial Report</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/fontawesome-free/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/dist/css/adminlte.min.css') ?>">

    <?= $this->renderSection('page_styles') ?>

    <style>
        /* UPGRADE TAMPILAN TABEL GLOBAL (Mayora Style) */

        /* 1. Header Tabel yang Elegan */
        .table thead th {
            background-color: #b71c1c;
            /* Merah Tua Mayora */
            color: #ffffff;
            border-bottom: 2px solid #880e4f;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.9rem;
            vertical-align: middle !important;
        }

        /* 2. Baris Tabel (Body) */
        .table tbody tr {
            transition: all 0.2s ease-in-out;
        }

        /* Efek Hover yang Halus */
        .table-hover tbody tr:hover {
            background-color: #ffebee !important;
            /* Merah sangat muda saat hover */
            transform: scale(1.002);
            /* Sedikit efek zoom agar interaktif */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            z-index: 10;
            position: relative;
        }

        /* Zebra Striping yang Lebih Lembut */
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.02);
            /* Abu-abu sangat tipis */
        }

        /* 3. Sel Tabel */
        .table td {
            vertical-align: middle !important;
            font-size: 0.95rem;
            border-color: #eee;
            /* Border antar sel lebih halus */
            padding: 0.75rem;
        }

        /* 4. Kolom Angka (Penting untuk Akuntansi!) */
        .table td.text-right {
            font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
            /* Font khusus angka */
            font-weight: 500;
            color: #333;
        }

        /* 5. Badge & Label di dalam tabel */
        .badge {
            font-weight: 500;
            padding: 0.4em 0.8em;
            letter-spacing: 0.5px;
        }

        /* 6. Pagination DataTables yang Lebih Rapi */
        .page-item.active .page-link {
            background-color: #d32f2f;
            /* Merah */
            border-color: #d32f2f;
        }

        .page-link {
            color: #d32f2f;
        }

        .page-link:hover {
            color: #b71c1c;
        }
    </style>
</head>


<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <nav class="main-header navbar navbar-expand navbar-white navbar-light" style="background-color: #ffffff; border-bottom: 3px solid #d32f2f;">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars text-danger"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <span class="nav-link font-weight-bold text-danger" style="font-size: 1.2rem; letter-spacing: 1px;">
                        <i class="fas fa-chart-line mr-2"></i> PT MAYORA INDAH TBK - FINANCIAL REPORTING SYSTEM
                    </span>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <span class="nav-link text-secondary">
                        <i class="far fa-calendar-alt mr-1"></i> Tahun Buku: 2024 - 2025
                    </span>
                </li>
            </ul>
        </nav>

        <aside class="main-sidebar elevation-4" style="background-color: #b71c1c; color: white;">
            <a href="/" class="brand-link" style="border-bottom: 1px solid #e53935; display: flex; align-items: center; padding: 0.8125rem 0.5rem;">
                <span class="brand-image img-circle elevation-3" style="opacity: .9; background: white; width: 33px; height: 33px; text-align: center; line-height: 33px; color: #d32f2f; font-weight: 900; font-family: sans-serif; margin-left: 0.8rem; margin-right: 0.5rem;">M</span>
                <span class="brand-text font-weight-bold text-white" style="letter-spacing: 1px; font-size: 1.1rem;">MAYORA GROUP</span>
            </a>

            <div class="sidebar">
                <nav class="mt-4">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                        <li class="nav-item">
                            <a href="/" class="nav-link text-white">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard Utama</p>
                            </a>
                        </li>

                        <li class="nav-header text-white-50" style="margin-top: 10px; border-top: 1px solid #e53935;">DATA ENTRY & MASTER</li>

                        <li class="nav-item">
                            <a href="/dapur/coa" class="nav-link text-warning">
                                <i class="fas fa-list nav-icon"></i>
                                <p>Kelola Akun (COA)</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="/dapur/jurnal" class="nav-link text-warning">
                                <i class="fas fa-edit nav-icon"></i>
                                <p>Input Jurnal Umum</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="/dapur/aset" class="nav-link text-warning">
                                <i class="fas fa-truck-loading nav-icon"></i>
                                <p>Manajemen Aset</p>
                            </a>
                        </li>

                        <li class="nav-header text-white-50" style="margin-top: 10px; border-top: 1px solid #e53935;">SIKLUS AKUNTANSI</li>

                        <li class="nav-item">
                            <a href="/dapur/tutup-buku" class="nav-link text-warning">
                                <i class="fas fa-lock nav-icon"></i>
                                <p>Tutup Buku (Closing)</p>
                            </a>
                        </li>

                        <li class="nav-header text-white-50" style="margin-top: 10px; border-top: 1px solid #e53935;">LAPORAN & ANALISIS</li>

                        <li class="nav-item">
                            <a href="/laporan/buku-besar" class="nav-link text-white">
                                <i class="nav-icon fas fa-book-open"></i>
                                <p>Buku Besar</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="/laporan/neraca-saldo" class="nav-link text-white">
                                <i class="fas fa-balance-scale-right nav-icon"></i>
                                <p>Neraca Saldo</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="/laporan/laba-rugi" class="nav-link text-white">
                                <i class="nav-icon fas fa-chart-line"></i>
                                <p>Laba Rugi (P/L)</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="/laporan/neraca" class="nav-link text-white">
                                <i class="nav-icon fas fa-balance-scale"></i>
                                <p>Posisi Keuangan</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="/laporan/perubahan-ekuitas" class="nav-link text-white">
                                <i class="nav-icon fas fa-layer-group"></i>
                                <p>Perubahan Ekuitas</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="/laporan/arus-kas" class="nav-link text-white">
                                <i class="nav-icon fas fa-money-bill-wave"></i>
                                <p>Arus Kas (Cash Flow)</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="/laporan/harga-pokok-produksi" class="nav-link text-white">
                                <i class="nav-icon fas fa-industry"></i>
                                <p>Harga Pokok Produksi</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="/laporan/analisis-rasio" class="nav-link text-white">
                                <i class="nav-icon fas fa-chart-pie"></i>
                                <p>Analisis Rasio</p>
                            </a>
                        </li>

                    </ul>
                </nav>
            </div>
        </aside>

        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark font-weight-bold"><?= $this->renderSection('page_title') ?></h1>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="container-fluid">
                    <?= $this->renderSection('content') ?>
                </div>
            </section>
        </div>

        <footer class="main-footer text-sm">
            <div class="float-right d-none d-sm-inline">
                <b>Version</b> 2.0 (Corporate Edition)
            </div>
            <strong>Copyright &copy; 2024 <a href="https://www.mayoraindah.co.id/" class="text-danger">PT Mayora Indah Tbk</a>.</strong> All rights reserved.
        </footer>
    </div>
    <script src="<?= base_url('assets/plugins/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="<?= base_url('assets/dist/js/adminlte.min.js') ?>"></script>

    <?= $this->renderSection('page_scripts') ?>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(function() {
            // Cek apakah ada pesan SUKSES dari controller?
            <?php if (session()->has('success')) : ?>
                Swal.fire({
                    title: 'Berhasil!',
                    text: '<?= session('success') ?>',
                    icon: 'success',
                    confirmButtonColor: '#2e7d32', // Hijau Mayora (mirip tombol simpan)
                    confirmButtonText: 'OK',
                    timer: 3000,
                    timerProgressBar: true
                });
            <?php endif; ?>

            // Cek apakah ada pesan ERROR dari controller?
            <?php if (session()->has('error')) : ?>
                Swal.fire({
                    title: 'Gagal!',
                    text: '<?= session('error') ?>',
                    icon: 'error',
                    confirmButtonColor: '#d33', // Merah
                    confirmButtonText: 'Tutup'
                });
            <?php endif; ?>
        });
    </script>
</body>

</html>