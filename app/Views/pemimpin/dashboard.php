<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
Executive Dashboard
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
<?= $this->endSection() ?>

<?= $this->section('page_styles') ?>
<style>
    /* Hero Section */
    .hero-section {
        position: relative;
        background-color: #b71c1c;
        background-image: url('<?= base_url('assets/dist/img/mayora-building.jpg') ?>');
        background-size: cover;
        background-position: center;
        min-height: 250px;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        margin-bottom: 25px;
    }

    .hero-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, rgba(183, 28, 28, 0.95) 0%, rgba(211, 47, 47, 0.85) 100%);
        z-index: 1;
    }

    .hero-content {
        position: relative;
        z-index: 2;
        padding: 40px;
        color: white;
        height: 100%;
        display: flex;
        align-items: center;
    }

    .corp-stat-box {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 8px;
        padding: 15px;
        text-align: center;
        color: white;
        transition: transform 0.3s;
    }

    .corp-stat-box:hover {
        transform: translateY(-3px);
        background: rgba(255, 255, 255, 0.2);
    }

    .corp-stat-icon {
        font-size: 1.5rem;
        margin-bottom: 5px;
        color: #ffcdd2;
    }

    .corp-stat-value {
        font-size: 1.25rem;
        font-weight: 800;
        display: block;
    }

    .corp-stat-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        opacity: 0.9;
    }

    /* KPI Cards */
    .small-box {
        background: white;
        border-radius: 10px;
        padding: 20px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s;
        margin-bottom: 20px;
        border-left: 5px solid #ddd;
    }

    .small-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
    }

    .small-box .inner h3 {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0 0 5px 0;
        color: #333;
    }

    .small-box .inner p {
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        color: #888;
        margin: 0;
    }

    .small-box .icon {
        position: absolute;
        top: 15px;
        right: 15px;
        font-size: 45px;
        color: rgba(0, 0, 0, 0.05);
    }

    .border-left-primary {
        border-left-color: #4e73df !important;
    }

    .border-left-success {
        border-left-color: #1cc88a !important;
    }

    .border-left-warning {
        border-left-color: #f6c23e !important;
    }

    .btn-quick {
        border: 2px dashed #e74a3b;
        color: #e74a3b;
        background: #fff5f5;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        transition: all 0.3s;
    }

    .btn-quick:hover {
        background: #e74a3b;
        color: white;
        border-color: #e74a3b;
        text-decoration: none;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="hero-section">
    <div class="hero-overlay"></div>
    <div class="container-fluid hero-content">
        <div class="row w-100 align-items-center">
            <div class="col-lg-8 mb-4 mb-lg-0">
                <h5 class="text-uppercase font-weight-bold text-white-50 mb-2">PT Mayora Indah Tbk</h5>
                <h1 class="font-weight-bold display-4 mb-2">Financial Executive Dashboard</h1>
                <p class="lead mb-0 text-white-50">Sistem pelaporan keuangan terintegrasi.</p>
            </div>
            <div class="col-lg-4">
                <div class="row">
                    <div class="col-4">
                        <div class="corp-stat-box"><i class="fas fa-calendar-check corp-stat-icon"></i><span class="corp-stat-value">2024</span><span class="corp-stat-label">Tahun</span></div>
                    </div>
                    <div class="col-4">
                        <div class="corp-stat-box"><i class="fas fa-chart-line corp-stat-icon"></i><span class="corp-stat-value">Q4</span><span class="corp-stat-label">Kuartal</span></div>
                    </div>
                    <div class="col-4">
                        <div class="corp-stat-box"><i class="fas fa-file-invoice-dollar corp-stat-icon"></i><span class="corp-stat-value">IDR</span><span class="corp-stat-label">Mata Uang</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm mb-4 border-left-primary">
    <div class="card-body py-3 d-flex flex-wrap align-items-center justify-content-between">
        <div class="mb-2 mb-md-0">
            <h5 class="m-0 font-weight-bold text-primary"><i class="fas fa-calendar-alt mr-2"></i> Periode Laporan</h5>
            <small class="text-muted">
                <?= date('d M Y', strtotime($startDate ?? '2024-01-01')) ?> s/d <?= date('d M Y', strtotime($endDate ?? '2024-12-31')) ?>
            </small>
        </div>
        <form action="" method="get" class="form-inline">
            <div class="input-group">
                <input type="date" name="start_date" class="form-control" value="<?= $startDate ?? '2024-01-01' ?>">
                <input type="date" name="end_date" class="form-control" value="<?= $endDate ?? '2024-12-31' ?>">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit"><i class="fas fa-filter"></i> Update</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="small-box border-left-primary h-100">
            <div class="inner">
                <p>TOTAL ASET BERSIH</p>
                <h3>Rp <?= number_format(($totalAset ?? 0) / 1000000000, 2, ',', '.') ?> M</h3>
                <small class="text-primary"><i class="fas fa-check-circle"></i> Posisi Kuat</small>
            </div>
            <div class="icon"><i class="fas fa-building"></i></div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="small-box border-left-success h-100">
            <div class="inner">
                <p>TOTAL PENDAPATAN</p>
                <h3>Rp <?= number_format(($totalPendapatan ?? 0) / 1000000000, 2, ',', '.') ?> M</h3>
                <small class="text-success"><i class="fas fa-arrow-up"></i> Pertumbuhan Positif</small>
            </div>
            <div class="icon"><i class="fas fa-chart-line"></i></div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="small-box border-left-warning h-100">
            <div class="inner">
                <p>LABA BERSIH (NET PROFIT)</p>
                <h3>Rp <?= number_format(($labaBersih ?? 0) / 1000000000, 2, ',', '.') ?> M</h3>
                <?php
                $omzet = $totalPendapatan ?? 0;
                $laba  = $labaBersih ?? 0;
                $margin = ($omzet != 0) ? ($laba / $omzet) * 100 : 0;
                ?>
                <small class="text-warning font-weight-bold">Net Margin: <?= number_format($margin, 1) ?>%</small>
            </div>
            <div class="icon"><i class="fas fa-coins"></i></div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <a href="/dapur/jurnal" class="btn-quick shadow-sm p-3">
            <i class="fas fa-plus-circle fa-3x mb-2"></i>
            <span class="font-weight-bold text-uppercase">Input Transaksi</span>
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
                        }
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
                        }
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