<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
Executive Dashboard
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
<?= $this->endSection() ?>

<?= $this->section('page_styles') ?>
<style>
    /* --- STYLE KHUSUS DASHBOARD --- */
    /* 1. Hero Section */
    .hero-section {
        position: relative;
        background-color: #b71c1c;
        background-image: url('<?= base_url('assets/dist/img/mayora-building.jpg') ?>');
        background-size: cover;
        background-position: center center;
        min-height: 280px;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        margin-bottom: 20px;
    }

    .hero-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, rgba(183, 28, 28, 0.95) 0%, rgba(211, 47, 47, 0.8) 60%, rgba(229, 57, 53, 0.4) 100%);
        z-index: 1;
    }

    .hero-content {
        position: relative;
        z-index: 2;
        padding: 30px;
        color: white;
        height: 100%;
        display: flex;
        align-items: center;
    }

    .corp-stat-box {
        background-color: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(5px);
        border-radius: 8px;
        padding: 15px;
        text-align: center;
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: transform 0.3s ease;
    }

    .corp-stat-box:hover {
        transform: translateY(-5px);
        background-color: rgba(255, 255, 255, 0.25);
    }

    .corp-stat-icon {
        font-size: 1.8rem;
        margin-bottom: 5px;
        color: #ffcdd2;
    }

    .corp-stat-value {
        font-size: 1.4rem;
        font-weight: 800;
        display: block;
    }

    .corp-stat-label {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        opacity: 0.9;
    }

    /* 2. Filter Bar */
    .filter-bar {
        background: white;
        padding: 15px 20px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        margin-bottom: 25px;
        border-left: 5px solid #b71c1c;
    }

    /* 3. KPI Cards */
    .small-box {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border: none;
        background: white;
    }

    .small-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
    }

    .small-box .inner {
        padding: 20px;
    }

    .small-box .icon {
        top: 20px;
        right: 20px;
        font-size: 70px;
        opacity: 0.15;
        color: #333 !important;
    }

    .border-left-primary {
        border-left: 6px solid #007bff !important;
    }

    .border-left-success {
        border-left: 6px solid #28a745 !important;
    }

    .border-left-warning {
        border-left: 6px solid #ffc107 !important;
    }

    .btn-quick-action {
        border: 2px dashed #d32f2f;
        color: #d32f2f;
        background: rgba(211, 47, 47, 0.05);
        transition: all 0.3s;
    }

    .btn-quick-action:hover {
        background: #d32f2f;
        color: white;
        border-color: #d32f2f;
    }

    /* --- STYLE MODAL MEWAH --- */
    .modal-header-mayora {
        background: #b71c1c;
        color: white;
    }

    /* 1. Membuat Modal Raksasa */
    @media (min-width: 992px) {
        .modal-xl-custom {
            max-width: 95% !important;
            /* Lebar 95% layar */
            margin: 1.75rem auto;
        }

        .modal-xl-custom .modal-content {
            min-height: 85vh;
            /* Tinggi minimal 85% layar */
            border-radius: 15px;
            overflow: hidden;
        }

        .modal-xl-custom .modal-body {
            overflow-y: auto;
            max-height: calc(85vh - 130px);
        }
    }

    /* 2. Styling Tab Navigasi agar lebih berkelas */
    .nav-tabs-luxury .nav-link {
        border: none;
        color: #6c757d;
        font-weight: 600;
        padding: 15px 25px;
        transition: all 0.3s;
        border-bottom: 4px solid transparent;
    }

    .nav-tabs-luxury .nav-link:hover {
        color: #b71c1c;
        background: rgba(183, 28, 28, 0.05);
    }

    .nav-tabs-luxury .nav-link.active {
        color: #b71c1c !important;
        font-weight: bold;
        border-top: none;
        border-bottom: 4px solid #b71c1c !important;
        background: transparent;
    }

    /* 3. Card Produk yang Elegan */
    .product-card {
        transition: transform 0.3s, box-shadow 0.3s;
        border-radius: 12px !important;
        overflow: hidden;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(183, 28, 28, 0.15) !important;
        border-color: #b71c1c !important;
    }

    .product-img-container {
        height: 150px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8f9fa;
        padding: 15px;
    }

    .product-img {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
        filter: drop-shadow(0px 4px 4px rgba(0, 0, 0, 0.1));
    }

    /* 4. Card Manajemen */
    .management-card {
        border-radius: 15px;
        border-top: 5px solid #b71c1c;
        transition: all 0.3s;
    }

    .management-card:hover {
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
    }

    .management-photo {
        width: 130px;
        height: 130px;
        object-fit: cover;
        border: 4px solid white;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="hero-section">
    <div class="hero-overlay"></div>
    <div class="container-fluid hero-content">
        <div class="row align-items-center w-100">
            <div class="col-lg-7 col-md-12 mb-4 mb-lg-0 pl-md-5">
                <img src="<?= base_url('assets/dist/img/mayora-logo-white.jpg') ?>" alt="Mayora Logo" class="mb-3 mt-3 mt-md-0" style="height: 80px; width: auto; opacity: 0.95; filter: drop-shadow(0px 2px 2px rgba(0,0,0,0.1));">
                <h5 class="text-uppercase font-weight-bold text-white-50 mb-1" style="letter-spacing: 2px; font-size: 0.9rem;">PT Mayora Indah Tbk & Entitas Anak</h5>
                <h1 class="font-weight-bolder text-white display-4" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.3); line-height: 1.1;">Financial Executive Dashboard</h1>
                <p class="lead mb-0 mt-2 text-white" style="opacity: 0.9; max-width: 600px;">Sistem pelaporan keuangan terintegrasi untuk pengambilan keputusan strategis yang cepat dan akurat.</p>

                <button type="button" class="btn btn-outline-light btn-sm mt-4 px-4 py-2 font-weight-bold shadow-sm rounded-pill" data-toggle="modal" data-target="#modalAboutUs" style="border-width: 2px;">
                    <i class="fas fa-info-circle mr-2"></i> Informasi Perusahaan
                </button>
            </div>
            <div class="col-lg-5 col-md-12 pt-4 pt-lg-0">
                <div class="row justify-content-center">
                    <div class="col-4 col-md-3">
                        <div class="corp-stat-box"><i class="fas fa-history corp-stat-icon"></i><span class="corp-stat-value">1977</span><span class="corp-stat-label">Didirikan</span></div>
                    </div>
                    <div class="col-4 col-md-3">
                        <div class="corp-stat-box"><i class="fas fa-globe-asia corp-stat-icon"></i><span class="corp-stat-value">100+</span><span class="corp-stat-label">Negara Ekspor</span></div>
                    </div>
                    <div class="col-4 col-md-3">
                        <div class="corp-stat-box"><i class="fas fa-tags corp-stat-icon"></i><span class="corp-stat-value">8</span><span class="corp-stat-label">Kategori Brand</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="filter-bar d-flex align-items-center justify-content-between flex-wrap">
    <div class="d-flex align-items-center mb-2 mb-md-0">
        <span class="badge badge-danger p-2 mr-3" style="font-size: 0.9rem;"><i class="fas fa-calendar-alt mr-1"></i> Periode Aktif</span>
        <h5 class="mb-0 font-weight-bold text-dark">
            <?= date('d M Y', strtotime($startDate ?? date('Y-01-01'))) ?> s/d <?= date('d M Y', strtotime($endDate ?? date('Y-12-31'))) ?>
        </h5>
    </div>
    <div class="d-flex align-items-center">
        <form action="" method="get" class="form-inline mr-3">
            <div class="input-group input-group-sm">
                <input type="date" name="start_date" class="form-control" value="<?= $startDate ?? '' ?>">
                <div class="input-group-prepend input-group-append"><span class="input-group-text">-</span></div>
                <input type="date" name="end_date" class="form-control" value="<?= $endDate ?? '' ?>">
                <div class="input-group-append"><button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-sync-alt"></i> Update</button></div>
            </div>
        </form>
        <span class="badge badge-warning p-2 text-dark" style="font-size: 0.9rem; font-weight: 600;"><i class="fas fa-check-circle mr-1"></i> Status: Audited Data (Simulasi)</span>
    </div>
</div>

<div class="row">
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="small-box bg-white border-left-primary">
            <div class="inner">
                <p class="text-muted text-uppercase font-weight-bold mb-1" style="font-size: 0.8rem;">Total Aset Bersih</p>
                <h4 class="font-weight-bold text-dark">Rp <?= number_format(($totalAset ?? 0) / 1000000000, 2, ',', '.') ?> M</h4>
                <small class="text-primary font-weight-bold"><i class="fas fa-arrow-up"></i> Posisi Keuangan Kuat</small>
            </div>
            <div class="icon"><i class="fas fa-building"></i></div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3">
        <div class="small-box bg-white border-left-success">
            <div class="inner">
                <p class="text-muted text-uppercase font-weight-bold mb-1" style="font-size: 0.8rem;">Total Pendapatan</p>
                <h4 class="font-weight-bold text-dark">Rp <?= number_format(($totalPendapatan ?? 0) / 1000000000, 2, ',', '.') ?> M</h4>
                <small class="text-success font-weight-bold">
                    <i class="fas fa-arrow-up"></i>
                    Pertumbuhan: Positif
                </small>
            </div>
            <div class="icon"><i class="fas fa-chart-line"></i></div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3">
        <div class="small-box bg-white border-left-warning">
            <div class="inner">
                <p class="text-muted text-uppercase font-weight-bold mb-1" style="font-size: 0.8rem;">Laba Bersih (Net Profit)</p>
                <h4 class="font-weight-bold text-dark">Rp <?= number_format(($labaBersih ?? 0) / 1000000000, 2, ',', '.') ?> M</h4>

                <?php
                $pendapatan = $totalPendapatan ?? 0;
                $laba = $labaBersih ?? 0;
                $margin = ($pendapatan != 0) ? ($laba / $pendapatan) * 100 : 0;
                ?>
                <small class="<?= $margin >= 0 ? 'text-warning' : 'text-danger' ?> font-weight-bold">
                    Net Margin: <strong><?= number_format($margin, 1) ?>%</strong>
                </small>
            </div>
            <div class="icon"><i class="fas fa-coins"></i></div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3">
        <a href="/dapur/jurnal" class="btn btn-block btn-quick-action shadow-sm h-100 d-flex flex-column justify-content-center align-items-center" style="border-radius: 12px; min-height: 120px;">
            <i class="fas fa-plus-circle fa-3x mb-2"></i>
            <span class="font-weight-bold text-uppercase" style="letter-spacing: 1px;">Input Transaksi Baru</span>
        </a>
    </div>
</div>

<div class="row">
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-white">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-chart-area mr-2"></i> Tren Pendapatan vs Beban</h6>
            </div>
            <div class="card-body">
                <div class="chart-area" style="height: 350px;">
                    <canvas id="trendLabaRugiChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-white">
                <h6 class="m-0 font-weight-bold text-danger"><i class="fas fa-chart-pie mr-2"></i> Top 5 Beban</h6>
            </div>
            <div class="card-body">
                <div class="chart-pie pt-4 pb-2" style="height: 250px;">
                    <canvas id="expensePieChart"></canvas>
                </div>
                <div class="mt-4 text-center small text-muted font-italic">Beban operasional terbesar</div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-white">
                <h6 class="m-0 font-weight-bold text-success"><i class="fas fa-wallet mr-2"></i> Tren Saldo Kas</h6>
            </div>
            <div class="card-body">
                <div class="chart-area" style="height: 300px;">
                    <canvas id="cashTrendChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-white">
                <h6 class="m-0 font-weight-bold text-success"><i class="fas fa-hand-holding-usd mr-2"></i> Sumber Pendapatan</h6>
            </div>
            <div class="card-body">
                <div class="chart-pie pt-4 pb-2" style="height: 250px;">
                    <canvas id="incomePieChart"></canvas>
                </div>
                <div class="mt-4 text-center small text-muted font-italic">Kontribusi Pendapatan</div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAboutUs" tabindex="-1" role="dialog" aria-labelledby="modalAboutUsLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl-custom modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="background: #fbfbfb;">
            <div class="modal-header modal-header-mayora py-3 px-4" style="background: linear-gradient(45deg, #b71c1c, #d32f2f);">
                <h4 class="modal-title font-weight-bold text-white" id="modalAboutUsLabel">
                    <i class="fas fa-building mr-3" style="opacity: 0.8;"></i>
                    Tentang PT Mayora Indah Tbk
                </h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 1; font-size: 1.5rem;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="row no-gutters h-100">
                    <div class="col-12">
                        <ul class="nav nav-tabs nav-tabs-luxury bg-white px-4 pt-3" id="mayoraTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab"><i class="fas fa-info-circle mr-2"></i> Sekilas Perusahaan</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="products-tab" data-toggle="tab" href="#products" role="tab"><i class="fas fa-box-open mr-2"></i> Produk Unggulan</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="management-tab" data-toggle="tab" href="#management" role="tab"><i class="fas fa-users-cog mr-2"></i> Manajemen</a>
                            </li>
                        </ul>

                        <div class="tab-content p-4 p-md-5" id="mayoraTabContent">
                            <div class="tab-pane fade show active" id="profile" role="tabpanel">
                                <div class="row align-items-center">
                                    <div class="col-lg-5 mb-4 mb-lg-0 text-center">
                                        <img src="<?= base_url('assets/dist/img/mayora-building.jpg') ?>" alt="Mayora Building" class="img-fluid rounded-lg shadow-sm" style="max-height: 350px; width: auto;">
                                    </div>
                                    <div class="col-lg-7 pl-lg-5">
                                        <h2 class="font-weight-bold text-dark mb-3">Satu Lagi dari Mayora.</h2>
                                        <p class="lead text-muted mb-4" style="line-height: 1.8;">
                                            <strong>PT Mayora Indah Tbk</strong> telah berkembang menjadi salah satu produsen makanan dan minuman terkemuka di Indonesia dan dunia. Dedikasi kami pada kualitas telah membawa merek-merek kami ke hati jutaan konsumen di lebih dari 100 negara.
                                        </p>

                                        <div class="p-4 rounded-lg" style="background: rgba(183, 28, 28, 0.08); border-left: 5px solid #b71c1c;">
                                            <h5 class="font-weight-bold text-danger mb-2"><i class="fas fa-bullseye mr-2"></i> Visi Perusahaan</h5>
                                            <p class="mb-0 text-dark font-italic">"Menjadi produsen makanan dan minuman yang berkualitas dan terpercaya di mata konsumen domestik maupun internasional dan menguasai pangsa pasar terbesar dalam kategori produk sejenis."</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="products" role="tabpanel">
                                <div class="text-center mb-5">
                                    <h3 class="font-weight-bold">Brand Global Kami</h3>
                                    <p class="text-muted">Inovasi produk yang tersebar di 5 benua di dunia.</p>
                                </div>
                                <div class="row">
                                    <div class="col-6 col-md-4 col-lg-3 mb-4">
                                        <div class="card product-card h-100 bg-white border shadow-sm">
                                            <div class="product-img-container">
                                                <img src="<?= base_url('assets/dist/img/prod-kopiko.jpg') ?>" class="product-img" alt="Kopiko">
                                            </div>
                                            <div class="card-body text-center py-3">
                                                <h5 class="card-title mb-0 font-weight-bold text-dark">Kopiko</h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-4 col-lg-3 mb-4">
                                        <div class="card product-card h-100 bg-white border shadow-sm">
                                            <div class="product-img-container">
                                                <img src="<?= base_url('assets/dist/img/prod-torabika.jpg') ?>" class="product-img" alt="Torabika">
                                            </div>
                                            <div class="card-body text-center py-3">
                                                <h5 class="card-title mb-0 font-weight-bold text-dark">Torabika</h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-4 col-lg-3 mb-4">
                                        <div class="card product-card h-100 bg-white border shadow-sm">
                                            <div class="product-img-container">
                                                <img src="<?= base_url('assets/dist/img/prod-bengbeng.jpg') ?>" class="product-img" alt="Beng Beng">
                                            </div>
                                            <div class="card-body text-center py-3">
                                                <h5 class="card-title mb-0 font-weight-bold text-dark">Beng Beng</h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-4 col-lg-3 mb-4">
                                        <div class="card product-card h-100 bg-white border shadow-sm">
                                            <div class="product-img-container">
                                                <img src="<?= base_url('assets/dist/img/prod-energen.jpg') ?>" class="product-img" alt="Energen">
                                            </div>
                                            <div class="card-body text-center py-3">
                                                <h5 class="card-title mb-0 font-weight-bold text-dark">Energen</h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-4 col-lg-3 mb-4">
                                        <div class="card product-card h-100 bg-white border shadow-sm">
                                            <div class="product-img-container">
                                                <img src="<?= base_url('assets/dist/img/prod-romamalkist.jpg') ?>" class="product-img" alt="Roma">
                                            </div>
                                            <div class="card-body text-center py-3">
                                                <h5 class="card-title mb-0 font-weight-bold text-dark">Roma Malkist</h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-4 col-lg-3 mb-4">
                                        <div class="card product-card h-100 bg-white border shadow-sm">
                                            <div class="product-img-container">
                                                <img src="<?= base_url('assets/dist/img/prod-astor.jpg') ?>" class="product-img" alt="Astor">
                                            </div>
                                            <div class="card-body text-center py-3">
                                                <h5 class="card-title mb-0 font-weight-bold text-dark">Astor</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="management" role="tabpanel">
                                <div class="text-center mb-5">
                                    <h3 class="font-weight-bold">Dewan Komisaris & Direksi</h3>
                                    <p class="text-muted">Kepemimpinan visioner di balik kesuksesan Mayora.</p>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-md-6 col-lg-4 mb-4">
                                        <div class="card management-card h-100 bg-white border-0 shadow-sm text-center p-4">
                                            <div class="mb-4 d-flex justify-content-center">
                                                <img src="<?= base_url('assets/dist/img/bos-jogi.jpg') ?>" alt="Jogi Hendra Atmadja" class="rounded-circle management-photo img-fluid">
                                            </div>
                                            <h5 class="font-weight-bold text-dark mb-1">Jogi Hendra Atmadja</h5>
                                            <span class="badge badge-danger px-3 py-2 mb-3">Komisaris Utama</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4 mb-4">
                                        <div class="card management-card h-100 bg-white border-0 shadow-sm text-center p-4">
                                            <div class="mb-4 d-flex justify-content-center">
                                                <img src="<?= base_url('assets/dist/img/bos-andre.jpg') ?>" alt="Andre Sukendra Atmadja" class="rounded-circle management-photo img-fluid">
                                            </div>
                                            <h5 class="font-weight-bold text-dark mb-1">Andre Sukendra Atmadja</h5>
                                            <span class="badge badge-primary px-3 py-2 mb-3" style="background-color: #0056b3;">Direktur Utama</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4 mb-4">
                                        <div class="card management-card h-100 bg-white border-0 shadow-sm text-center p-4">
                                            <div class="mb-4 d-flex justify-content-center">
                                                <img src="<?= base_url('assets/dist/img/bos-hendarta.jpg') ?>" alt="Hendarta Atmadja" class="rounded-circle management-photo img-fluid">
                                            </div>
                                            <h5 class="font-weight-bold text-dark mb-1">Hendarta Atmadja</h5>
                                            <span class="badge badge-success px-3 py-2 mb-3" style="background-color: #28a745;">Direktur Keuangan</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-white py-3">
                <p class="small text-muted mr-auto my-auto"><i class="far fa-copyright"></i> 2025 PT Mayora Indah Tbk. Financial Reporting System v2.0</p>
                <button type="button" class="btn btn-danger font-weight-bold px-4" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('page_scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        // Config Default untuk Chart.js v2
        Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
        Chart.defaults.global.defaultFontColor = '#858796';

        // --- TERIMA DATA DARI CONTROLLER ---
        var lineLabels = <?= $chartLabels ?? '[]' ?>;
        var lineIncome = <?= $dataPendapatan ?? '[]' ?>;
        var lineExpense = <?= $dataBeban ?? '[]' ?>;

        var expLabels = <?= $bebanLabels ?? '[]' ?>;
        var expData = <?= $bebanValues ?? '[]' ?>;

        var incLabels = <?= $incomeLabels ?? '[]' ?>;
        var incData = <?= $incomeData ?? '[]' ?>;

        var cashLabels = <?= $cashTrendLabels ?? '[]' ?>;
        var cashData = <?= $cashTrendData ?? '[]' ?>;

        // JIKA DATA LINE CHART KOSONG, ISI DUMMY
        if (lineLabels.length === 0) {
            lineLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            lineIncome = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            lineExpense = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        }

        // Helper Format Rupiah
        function fmtRupiah(val) {
            if (val >= 1000000000) return 'Rp ' + (val / 1000000000).toFixed(1) + ' M';
            if (val >= 1000000) return 'Rp ' + (val / 1000000).toFixed(0) + ' Jt';
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(val);
        }

        // --- 1. LINE CHART: PENDAPATAN VS BEBAN ---
        var ctxLine = document.getElementById("trendLabaRugiChart");
        if (ctxLine) {
            new Chart(ctxLine, {
                type: 'line',
                data: {
                    labels: lineLabels,
                    datasets: [{
                            label: "Pendapatan",
                            lineTension: 0.3,
                            backgroundColor: "rgba(28, 200, 138, 0.05)",
                            borderColor: "rgba(28, 200, 138, 1)",
                            pointRadius: 3,
                            pointBackgroundColor: "rgba(28, 200, 138, 1)",
                            pointBorderColor: "rgba(28, 200, 138, 1)",
                            data: lineIncome
                        },
                        {
                            label: "Beban",
                            lineTension: 0.3,
                            backgroundColor: "rgba(231, 74, 59, 0.05)",
                            borderColor: "rgba(231, 74, 59, 1)",
                            pointRadius: 3,
                            pointBackgroundColor: "rgba(231, 74, 59, 1)",
                            pointBorderColor: "rgba(231, 74, 59, 1)",
                            data: lineExpense
                        }
                    ]
                },
                options: {
                    maintainAspectRatio: false,
                    scales: {
                        xAxes: [{
                            gridLines: {
                                display: false,
                                drawBorder: false
                            },
                            ticks: {
                                maxTicksLimit: 7
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                callback: function(value) {
                                    return fmtRupiah(value);
                                }
                            },
                            gridLines: {
                                color: "rgb(234, 236, 244)",
                                zeroLineColor: "rgb(234, 236, 244)",
                                drawBorder: false,
                                borderDash: [2],
                                zeroLineBorderDash: [2]
                            }
                        }]
                    },
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, chart) {
                                var label = chart.datasets[tooltipItem.datasetIndex].label || '';
                                return label + ': ' + fmtRupiah(tooltipItem.yLabel);
                            }
                        }
                    }
                }
            });
        }

        // --- 2. PIE CHART BEBAN ---
        var ctxPie = document.getElementById("expensePieChart");
        if (ctxPie) {
            if (expData.length === 0 || expData.every(v => v == 0)) {
                var pCtx = ctxPie.getContext('2d');
                pCtx.font = "14px Arial";
                pCtx.textAlign = "center";
                pCtx.fillText("Belum ada data beban", ctxPie.width / 2, ctxPie.height / 2);
            } else {
                new Chart(ctxPie, {
                    type: 'doughnut',
                    data: {
                        labels: expLabels,
                        datasets: [{
                            data: expData,
                            backgroundColor: ['#e74a3b', '#f6c23e', '#36b9cc', '#1cc88a', '#4e73df'],
                            hoverBorderColor: "rgba(234, 236, 244, 1)",
                        }],
                    },
                    options: {
                        maintainAspectRatio: false,
                        legend: {
                            display: false
                        },
                        cutoutPercentage: 70,
                        tooltips: {
                            callbacks: {
                                label: function(tooltipItem, data) {
                                    var val = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                                    return data.labels[tooltipItem.index] + ': ' + fmtRupiah(val);
                                }
                            }
                        },
                    },
                });
            }
        }

        // --- 3. PIE CHART PENDAPATAN ---
        var ctxPieInc = document.getElementById("incomePieChart");
        if (ctxPieInc) {
            if (incData.length === 0 || incData.every(v => v == 0)) {
                var pCtxInc = ctxPieInc.getContext('2d');
                pCtxInc.font = "14px Arial";
                pCtxInc.textAlign = "center";
                pCtxInc.fillText("Belum ada data pendapatan", ctxPieInc.width / 2, ctxPieInc.height / 2);
            } else {
                new Chart(ctxPieInc, {
                    type: 'doughnut',
                    data: {
                        labels: incLabels,
                        datasets: [{
                            data: incData,
                            backgroundColor: ['#1cc88a', '#36b9cc', '#4e73df', '#f6c23e', '#e74a3b'],
                            hoverBorderColor: "rgba(234, 236, 244, 1)",
                        }],
                    },
                    options: {
                        maintainAspectRatio: false,
                        legend: {
                            display: false
                        },
                        cutoutPercentage: 70,
                        tooltips: {
                            callbacks: {
                                label: function(tooltipItem, data) {
                                    var val = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                                    return data.labels[tooltipItem.index] + ': ' + fmtRupiah(val);
                                }
                            }
                        },
                    },
                });
            }
        }

        // --- 4. LINE CHART KAS ---
        var ctxCash = document.getElementById("cashTrendChart");
        if (ctxCash) {
            if (cashData.length === 0) {
                cashLabels = ['-'];
                cashData = [0];
            }
            new Chart(ctxCash, {
                type: 'line',
                data: {
                    labels: cashLabels,
                    datasets: [{
                        label: "Saldo Kas",
                        lineTension: 0.3,
                        backgroundColor: "rgba(78, 115, 223, 0.05)",
                        borderColor: "rgba(78, 115, 223, 1)",
                        pointRadius: 3,
                        pointBackgroundColor: "rgba(78, 115, 223, 1)",
                        pointBorderColor: "rgba(78, 115, 223, 1)",
                        data: cashData,
                    }],
                },
                options: {
                    maintainAspectRatio: false,
                    scales: {
                        xAxes: [{
                            gridLines: {
                                display: false,
                                drawBorder: false
                            },
                            ticks: {
                                maxTicksLimit: 7
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                callback: function(value) {
                                    return fmtRupiah(value);
                                }
                            },
                            gridLines: {
                                color: "rgb(234, 236, 244)",
                                zeroLineColor: "rgb(234, 236, 244)",
                                drawBorder: false,
                                borderDash: [2],
                                zeroLineBorderDash: [2]
                            }
                        }]
                    },
                    legend: {
                        display: false
                    },
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, chart) {
                                return 'Saldo: ' + fmtRupiah(tooltipItem.yLabel);
                            }
                        }
                    }
                }
            });
        }
    });
</script>
<?= $this->endSection() ?>