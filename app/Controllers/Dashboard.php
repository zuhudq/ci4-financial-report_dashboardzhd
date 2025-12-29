<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CoaModel;
use App\Models\JurnalDetailModel;
use App\Models\JurnalHeaderModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $headerModel = new JurnalHeaderModel();

        // 1. SETUP TANGGAL (Force 2024 agar Data Simulasi Muncul)
        $reqStart = $this->request->getGet('start_date');
        $reqEnd   = $this->request->getGet('end_date');

        $startDate = $reqStart ? $reqStart : '2024-01-01';
        $endDate   = $reqEnd   ? $reqEnd   : '2024-12-31';
        $queryEndDate = $endDate . ' 23:59:59';

        // 2. HELPER: Hitung Saldo Mutasi
        $getNetMutation = function ($accountId, $start = null, $end) use ($db) {
            $builder = $db->table('jurnal_detail');
            $builder->selectSum('debit')->selectSum('kredit');
            $builder->join('jurnal_header', 'jurnal_header.id_jurnal = jurnal_detail.id_jurnal');
            $builder->where('jurnal_detail.id_akun', $accountId);
            $builder->where('jurnal_header.tanggal_jurnal <=', $end);
            if ($start) $builder->where('jurnal_header.tanggal_jurnal >=', $start);
            $query = $builder->get()->getRow();
            return ($query->debit ?? 0) - ($query->kredit ?? 0);
        };

        // --- A. HITUNG KPI (KARTU ATAS) ---

        // ASET
        $qAset = $db->table('jurnal_detail')
            ->selectSum('debit')->selectSum('kredit')
            ->join('chart_of_accounts', 'chart_of_accounts.id_akun = jurnal_detail.id_akun')
            ->join('jurnal_header', 'jurnal_header.id_jurnal = jurnal_detail.id_jurnal')
            ->where('chart_of_accounts.kategori', 'Aset')
            ->where('jurnal_header.tanggal_jurnal <=', $queryEndDate)
            ->get()->getRowArray();
        $totalAset = ($qAset['debit'] ?? 0) - ($qAset['kredit'] ?? 0);

        // PENDAPATAN
        $qPendapatan = $db->table('jurnal_detail')
            ->selectSum('debit')->selectSum('kredit')
            ->join('chart_of_accounts', 'chart_of_accounts.id_akun = jurnal_detail.id_akun')
            ->join('jurnal_header', 'jurnal_header.id_jurnal = jurnal_detail.id_jurnal')
            ->like('chart_of_accounts.kategori', 'Pendapatan', 'after')
            ->where('jurnal_header.tanggal_jurnal >=', $startDate)
            ->where('jurnal_header.tanggal_jurnal <=', $queryEndDate)
            ->get()->getRowArray();
        $totalPendapatan = ($qPendapatan['kredit'] ?? 0) - ($qPendapatan['debit'] ?? 0);

        // BEBAN
        $qBeban = $db->table('jurnal_detail')
            ->selectSum('debit')->selectSum('kredit')
            ->join('chart_of_accounts', 'chart_of_accounts.id_akun = jurnal_detail.id_akun')
            ->join('jurnal_header', 'jurnal_header.id_jurnal = jurnal_detail.id_jurnal')
            ->like('chart_of_accounts.kategori', 'Beban', 'after')
            ->where('jurnal_header.tanggal_jurnal >=', $startDate)
            ->where('jurnal_header.tanggal_jurnal <=', $queryEndDate)
            ->get()->getRowArray();
        $totalBeban = ($qBeban['debit'] ?? 0) - ($qBeban['kredit'] ?? 0);

        $labaBersih = $totalPendapatan - $totalBeban;
        $profitMargin = ($totalPendapatan > 0) ? ($labaBersih / $totalPendapatan) * 100 : 0;

        // --- B. SIAPKAN DATA GRAFIK ---

        // 1. Grafik Line (Bulanan)
        $chartLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $chartInc = array_fill(0, 12, 0);
        $chartExp = array_fill(0, 12, 0);

        $sqlChart = "SELECT MONTH(h.tanggal_jurnal) as bulan, c.kategori, 
                            SUM(d.debit) as deb, SUM(d.kredit) as kre
                     FROM jurnal_detail d
                     JOIN jurnal_header h ON h.id_jurnal = d.id_jurnal
                     JOIN chart_of_accounts c ON c.id_akun = d.id_akun
                     WHERE h.tanggal_jurnal BETWEEN ? AND ?
                     AND (c.kategori LIKE 'Pendapatan%' OR c.kategori LIKE 'Beban%')
                     GROUP BY MONTH(h.tanggal_jurnal), c.kategori";

        $qGrafik = $db->query($sqlChart, [$startDate, $queryEndDate])->getResultArray();

        if (!empty($qGrafik)) {
            foreach ($qGrafik as $row) {
                $idx = intval($row['bulan']) - 1;
                if ($idx >= 0 && $idx < 12) {
                    if (strpos($row['kategori'], 'Pendapatan') !== false) {
                        $chartInc[$idx] += floatval($row['kre']) - floatval($row['deb']);
                    } elseif (strpos($row['kategori'], 'Beban') !== false) {
                        $chartExp[$idx] += floatval($row['deb']) - floatval($row['kre']);
                    }
                }
            }
        }

        // 2. Pie Chart Beban (Top 5)
        $topBeban = $db->query("SELECT c.nama_akun, (SUM(d.debit) - SUM(d.kredit)) as total
                                FROM jurnal_detail d
                                JOIN jurnal_header h ON h.id_jurnal = d.id_jurnal
                                JOIN chart_of_accounts c ON c.id_akun = d.id_akun
                                WHERE c.kategori LIKE 'Beban%' AND h.tanggal_jurnal BETWEEN ? AND ?
                                GROUP BY c.id_akun ORDER BY total DESC LIMIT 5", [$startDate, $queryEndDate])->getResultArray();
        $bebanLabels = array_column($topBeban, 'nama_akun');
        $bebanValues = array_map('floatval', array_column($topBeban, 'total'));

        // 3. Pie Chart Pendapatan (Top 5)
        $topInc = $db->query("SELECT c.nama_akun, (SUM(d.kredit) - SUM(d.debit)) as total
                              FROM jurnal_detail d
                              JOIN jurnal_header h ON h.id_jurnal = d.id_jurnal
                              JOIN chart_of_accounts c ON c.id_akun = d.id_akun
                              WHERE c.kategori LIKE 'Pendapatan%' AND h.tanggal_jurnal BETWEEN ? AND ?
                              GROUP BY c.id_akun ORDER BY total DESC LIMIT 5", [$startDate, $queryEndDate])->getResultArray();
        $incomeLabels = array_column($topInc, 'nama_akun');
        $incomeValues = array_map('floatval', array_column($topInc, 'total'));

        // 4. Grafik Kas
        $cashLabels = [];
        $cashData = [];
        $akunKas = $db->table('chart_of_accounts')->like('nama_akun', 'Kas')->orLike('nama_akun', 'Bank')->get()->getRow();

        if ($akunKas) {
            $dMin30 = date('Y-m-d', strtotime('-30 days', strtotime($endDate)));
            $qSaldoAwal = $db->query("SELECT SUM(debit - kredit) as saldo FROM jurnal_detail JOIN jurnal_header ON jurnal_header.id_jurnal = jurnal_detail.id_jurnal WHERE id_akun = ? AND tanggal_jurnal < ?", [$akunKas->id_akun, $dMin30])->getRow();
            $saldoKas = floatval($akunKas->saldo_awal) + floatval($qSaldoAwal->saldo ?? 0);

            $qKas = $db->query("SELECT jh.tanggal_jurnal, SUM(jd.debit - jd.kredit) as mutasi FROM jurnal_detail jd JOIN jurnal_header jh ON jh.id_jurnal = jd.id_jurnal WHERE jd.id_akun = ? AND jh.tanggal_jurnal BETWEEN ? AND ? GROUP BY jh.tanggal_jurnal", [$akunKas->id_akun, $dMin30, $queryEndDate])->getResultArray();
            $mapKas = [];
            foreach ($qKas as $k) $mapKas[$k['tanggal_jurnal']] = $k['mutasi'];

            $curr = $dMin30;
            while ($curr <= $endDate) {
                if (isset($mapKas[$curr])) $saldoKas += $mapKas[$curr];
                $cashLabels[] = date('d M', strtotime($curr));
                $cashData[] = $saldoKas;
                $curr = date('Y-m-d', strtotime('+1 day', strtotime($curr)));
            }
        }
        if (empty($cashData)) {
            $cashLabels = ['-'];
            $cashData = [0];
        }

        $data = [
            'title' => 'Executive Dashboard',
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalAset' => $totalAset,
            'totalPendapatan' => $totalPendapatan,
            'labaBersih' => $labaBersih,
            'profitMargin' => $profitMargin,
            'revenueGrowth' => 0,

            // Variabel Grafik
            'chartLabels' => json_encode($chartLabels),
            'dataPendapatan' => json_encode($chartInc),
            'dataBeban' => json_encode($chartExp),
            'bebanLabels' => json_encode($bebanLabels),
            'bebanValues' => json_encode($bebanValues),
            'incomeLabels' => json_encode($incomeLabels),
            'incomeData' => json_encode($incomeValues),
            'cashTrendLabels' => json_encode($cashLabels),
            'cashTrendData' => json_encode($cashData),

            'jurnalTerbaru' => $headerModel->orderBy('created_at', 'DESC')->limit(5)->findAll()
        ];

        return view('dashboard/index', $data);
    }
}
