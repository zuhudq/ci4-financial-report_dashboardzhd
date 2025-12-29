<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
Analisis Rasio
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Financial Health Check (Analisis Rasio)
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card shadow-sm mb-4">
    <div class="card-body py-2">
        <form action="" method="get" class="form-inline justify-content-end">
            <label class="mr-2 font-weight-bold">Analisis per Tanggal:</label>
            <input type="date" name="end_date" class="form-control mr-2" value="<?= esc($endDate) ?>">
            <button type="submit" class="btn btn-primary" style="background-color: #b71c1c;">Update Analisis</button>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-md-6 col-lg-3">
        <div class="card shadow">
            <div class="card-header bg-white text-center font-weight-bold text-primary">Likuiditas (Current Ratio)</div>
            <div class="card-body text-center">
                <div style="position: relative; height: 150px; width: 100%;">
                    <canvas id="chartCurrentRatio"></canvas>
                    <div style="position: absolute; top: 70%; left: 50%; transform: translate(-50%, -50%); font-size: 1.5rem; font-weight: bold;">
                        <?= number_format($currentRatio, 2) ?>x
                    </div>
                </div>
                <hr>
                <p class="mb-0 small text-muted">Aset Lancar / Utang Lancar</p>
                <?php if ($currentRatio >= 2): ?>
                    <span class="badge badge-success mt-2">SANGAT SEHAT (> 2x)</span>
                <?php elseif ($currentRatio >= 1): ?>
                    <span class="badge badge-warning mt-2">CUKUP AMAN (> 1x)</span>
                <?php else: ?>
                    <span class="badge badge-danger mt-2">BERISIKO (< 1x)</span>
                        <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card shadow">
            <div class="card-header bg-white text-center font-weight-bold text-danger">Solvabilitas (D.E.R)</div>
            <div class="card-body text-center">
                <div style="position: relative; height: 150px; width: 100%;">
                    <canvas id="chartDer"></canvas>
                    <div style="position: absolute; top: 70%; left: 50%; transform: translate(-50%, -50%); font-size: 1.5rem; font-weight: bold;">
                        <?= number_format($der, 2) ?>x
                    </div>
                </div>
                <hr>
                <p class="mb-0 small text-muted">Total Utang / Modal Sendiri</p>
                <?php if ($der <= 1): ?>
                    <span class="badge badge-success mt-2">AMAN (Modal > Utang)</span>
                <?php else: ?>
                    <span class="badge badge-warning mt-2">HATI-HATI (Utang > Modal)</span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card shadow">
            <div class="card-header bg-white text-center font-weight-bold text-success">Net Profit Margin</div>
            <div class="card-body text-center">
                <div style="position: relative; height: 150px; width: 100%;">
                    <canvas id="chartNpm"></canvas>
                    <div style="position: absolute; top: 70%; left: 50%; transform: translate(-50%, -50%); font-size: 1.5rem; font-weight: bold;">
                        <?= number_format($npm, 1) ?>%
                    </div>
                </div>
                <hr>
                <p class="mb-0 small text-muted">Laba Bersih / Penjualan</p>
                <?php if ($npm > 10): ?>
                    <span class="badge badge-success mt-2">PROFIT TINGGI (>10%)</span>
                <?php elseif ($npm > 0): ?>
                    <span class="badge badge-info mt-2">PROFIT POSITIF</span>
                <?php else: ?>
                    <span class="badge badge-danger mt-2">RUGI</span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card shadow">
            <div class="card-header bg-white text-center font-weight-bold text-info">Return on Assets (ROA)</div>
            <div class="card-body text-center">
                <div style="position: relative; height: 150px; width: 100%;">
                    <canvas id="chartRoa"></canvas>
                    <div style="position: absolute; top: 70%; left: 50%; transform: translate(-50%, -50%); font-size: 1.5rem; font-weight: bold;">
                        <?= number_format($roa, 1) ?>%
                    </div>
                </div>
                <hr>
                <p class="mb-0 small text-muted">Laba Bersih / Total Aset</p>
                <span class="badge badge-light border mt-2">Efisiensi Aset</span>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card border-left-primary shadow-sm">
            <div class="card-body">
                <h5 class="font-weight-bold"><i class="fas fa-lightbulb text-warning mr-2"></i> Rekomendasi Sistem (AI Insight)</h5>
                <p class="text-muted">Berdasarkan data keuangan per <strong><?= date('d M Y', strtotime($endDate)) ?></strong>:</p>
                <ul>
                    <?php if ($currentRatio < 1): ?>
                        <li class="text-danger"><strong>Likuiditas Rendah!</strong> Perusahaan mungkin kesulitan membayar kewajiban jangka pendek. Segera tagih piutang atau tambah modal kerja.</li>
                    <?php else: ?>
                        <li class="text-success"><strong>Likuiditas Aman.</strong> Perusahaan mampu melunasi utang jangka pendek dengan aset lancar yang ada.</li>
                    <?php endif; ?>

                    <?php if ($npm <= 0): ?>
                        <li class="text-danger"><strong>Operasional Merugi.</strong> Perlu efisiensi biaya operasional atau strategi peningkatan penjualan segera.</li>
                    <?php else: ?>
                        <li class="text-success"><strong>Bisnis Menguntungkan.</strong> Pertahankan efisiensi biaya dan genjot volume penjualan.</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('page_scripts') ?>
<script>
    // FUNGSI BIKIN GAUGE CHART (Doughnut Setengah Lingkaran)
    function createGauge(ctx, value, maxValue, color) {
        // Normalisasi value biar gak lebih dari max chart
        let val = value < 0 ? 0 : value;
        let sisa = maxValue - val;
        if (sisa < 0) sisa = 0;

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ["Capaian", "Sisa"],
                datasets: [{
                    data: [val, sisa],
                    backgroundColor: [color, "#e0e0e0"],
                    borderWidth: 0
                }]
            },
            options: {
                rotation: 1 * Math.PI,
                circumference: 1 * Math.PI, // Setengah lingkaran
                cutoutPercentage: 70,
                legend: {
                    display: false
                },
                tooltips: {
                    enabled: false
                }
            }
        });
    }

    // 1. CURRENT RATIO (Max scale 3x)
    createGauge(document.getElementById('chartCurrentRatio').getContext('2d'), <?= $currentRatio ?>, 3, '#007bff');

    // 2. DER (Max scale 2x - Makin rendah makin baik, jadi warnanya kita balik logikanya nanti di visual)
    createGauge(document.getElementById('chartDer').getContext('2d'), <?= $der ?>, 2, '#dc3545');

    // 3. NPM (Max scale 30%)
    createGauge(document.getElementById('chartNpm').getContext('2d'), <?= $npm ?>, 30, '#28a745');

    // 4. ROA (Max scale 20%)
    createGauge(document.getElementById('chartRoa').getContext('2d'), <?= $roa ?>, 20, '#17a2b8');
</script>
<?= $this->endSection() ?>