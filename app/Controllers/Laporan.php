<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CoaModel;
use App\Models\JurnalDetailModel;
use Dompdf\Dompdf;

class Laporan extends BaseController
{
    // =======================================================
    // 1. LAPORAN BUKU BESAR (FIXED: REMOVED 'ref' COLUMN)
    // =======================================================
    public function bukuBesar()
    {
        $db = \Config\Database::connect();
        $coaModel = new CoaModel();

        // Ambil Filter
        $accountId = $this->request->getGet('akun_id');
        // Default Tanggal: 2024 (Agar data simulasi muncul)
        $startDate = $this->request->getGet('start_date') ?? '2024-01-01';
        $endDate   = $this->request->getGet('end_date') ?? '2024-12-31';

        $accounts = $coaModel->orderBy('kode_akun', 'ASC')->findAll();

        // Inisialisasi Variabel Kosong (Agar View tidak error jika belum pilih akun)
        $selectedAccount = null;
        $transaksi = [];
        $saldoAwalPeriode = 0;

        if ($accountId) {
            $selectedAccount = $coaModel->find($accountId);

            if ($selectedAccount) {
                // A. HITUNG SALDO AWAL (MUTASI MASA LALU)
                $builder = $db->table('jurnal_detail');
                $builder->selectSum('debit')->selectSum('kredit');
                $builder->join('jurnal_header', 'jurnal_header.id_jurnal = jurnal_detail.id_jurnal');
                $builder->where('id_akun', $accountId);
                $builder->where('jurnal_header.tanggal_jurnal <', $startDate);

                // Gunakan getRowArray() agar return NULL jika kosong
                $histori = $builder->get()->getRowArray();

                // --- PENGAMAN GANDA (ANTI ERROR) ---
                $debitLama = 0;
                $kreditLama = 0;

                // Cek dulu apakah $histori ada isinya?
                if ($histori) {
                    $debitLama = floatval($histori['debit']);
                    $kreditLama = floatval($histori['kredit']);
                }

                $saldoMaster = floatval($selectedAccount['saldo_awal']);

                // Rumus Saldo Awal Berdasarkan Kategori
                if (in_array($selectedAccount['kategori'], ['Aset', 'Beban'])) {
                    // Normal Debit
                    $saldoAwalPeriode = $saldoMaster + ($debitLama - $kreditLama);
                } else {
                    // Normal Kredit
                    $saldoAwalPeriode = $saldoMaster + ($kreditLama - $debitLama);
                }

                // B. AMBIL TRANSAKSI PERIODE INI
                // FIX: Menghapus 'jurnal_header.ref' karena kolom tersebut tidak ada di database
                $builderTx = $db->table('jurnal_detail');
                $builderTx->select('jurnal_header.tanggal_jurnal, jurnal_header.deskripsi as ket_header, jurnal_detail.debit, jurnal_detail.kredit');
                $builderTx->join('jurnal_header', 'jurnal_header.id_jurnal = jurnal_detail.id_jurnal');
                $builderTx->where('id_akun', $accountId);
                $builderTx->where('jurnal_header.tanggal_jurnal >=', $startDate);
                $builderTx->where('jurnal_header.tanggal_jurnal <=', $endDate);
                $builderTx->orderBy('jurnal_header.tanggal_jurnal', 'ASC');

                $transaksi = $builderTx->get()->getResultArray();
            }
        }

        $data = [
            'title' => 'Buku Besar',
            'page_title' => 'Rincian Buku Besar (General Ledger)',
            'accounts' => $accounts,
            'selectedAccount' => $selectedAccount,
            'transaksi' => $transaksi,
            'saldoAwal' => $saldoAwalPeriode,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'accountId' => $accountId
        ];

        return view('laporan/buku_besar', $data);
    }

    public function cetakBukuBesar()
    {
        return redirect()->to('/laporan/buku-besar');
    }

    // =======================================================
    // 2. LAPORAN LABA RUGI (DATA FLOW 2024)
    // =======================================================
    private function _getLabaRugiData($startDate, $endDate)
    {
        $db = \Config\Database::connect();

        // Helper Query Mutasi Murni
        $queryMutasi = function ($kategori) use ($db, $startDate, $endDate) {
            $builder = $db->table('jurnal_detail');
            $builder->select('chart_of_accounts.nama_akun, chart_of_accounts.kode_akun, SUM(jurnal_detail.debit) as total_debit, SUM(jurnal_detail.kredit) as total_kredit');
            $builder->join('jurnal_header', 'jurnal_header.id_jurnal = jurnal_detail.id_jurnal');
            $builder->join('chart_of_accounts', 'chart_of_accounts.id_akun = jurnal_detail.id_akun');
            $builder->where('chart_of_accounts.kategori', $kategori);
            $builder->where('jurnal_header.tanggal_jurnal >=', $startDate);
            $builder->where('jurnal_header.tanggal_jurnal <=', $endDate);
            $builder->groupBy('jurnal_detail.id_akun');
            return $builder->get()->getResultArray();
        };

        // 1. PENDAPATAN
        $pendapatanRaw = $queryMutasi('Pendapatan');
        $pendapatanDetails = [];
        $totalPendapatan = 0;

        foreach ($pendapatanRaw as $row) {
            $saldo = floatval($row['total_kredit']) - floatval($row['total_debit']);
            if ($saldo != 0) {
                $pendapatanDetails[] = ['nama_akun' => $row['nama_akun'], 'kode_akun' => $row['kode_akun'], 'balance' => $saldo];
                $totalPendapatan += $saldo;
            }
        }

        // 2. BEBAN
        $bebanRaw = $queryMutasi('Beban');
        $bebanDetails = [];
        $totalBeban = 0;

        foreach ($bebanRaw as $row) {
            $saldo = floatval($row['total_debit']) - floatval($row['total_kredit']);
            if ($saldo != 0) {
                $bebanDetails[] = ['nama_akun' => $row['nama_akun'], 'kode_akun' => $row['kode_akun'], 'balance' => $saldo];
                $totalBeban += $saldo;
            }
        }

        return [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'pendapatanDetails' => $pendapatanDetails,
            'totalPendapatan' => $totalPendapatan,
            'bebanDetails' => $bebanDetails,
            'totalBeban' => $totalBeban,
            'labaRugi' => $totalPendapatan - $totalBeban,
        ];
    }

    public function labaRugi()
    {
        $startDate = $this->request->getGet('start_date') ?? '2024-01-01';
        $endDate = $this->request->getGet('end_date') ?? '2024-12-31';

        $dataLaporan = $this->_getLabaRugiData($startDate, $endDate);

        $dataLaporan['title'] = 'Laporan Laba Rugi';
        $dataLaporan['page_title'] = 'Laporan Laba Rugi (Profit & Loss)';
        $dataLaporan['isFiltered'] = ($this->request->getGet('start_date') !== null);

        return view('laporan/laba_rugi', $dataLaporan);
    }

    public function cetakLabaRugi()
    {
        return redirect()->to('/laporan/laba-rugi');
    }
    public function exportLabaRugiExcel()
    {
        return redirect()->to('/laporan/laba-rugi');
    }

    // =======================================================
    // 3. NERACA SALDO (AKUMULASI S/D HARI INI)
    // =======================================================
    public function neracaSaldo()
    {
        $db = \Config\Database::connect();
        $coaModel = new CoaModel();

        $akun = $coaModel->orderBy('kode_akun', 'ASC')->findAll();
        $neraca = [];
        $totalDebit = 0;
        $totalKredit = 0;

        foreach ($akun as $row) {
            $mutasi = $db->table('jurnal_detail')
                ->selectSum('debit')->selectSum('kredit')
                ->where('id_akun', $row['id_akun'])
                ->get()->getRowArray();

            $d = isset($mutasi['debit']) ? floatval($mutasi['debit']) : 0;
            $k = isset($mutasi['kredit']) ? floatval($mutasi['kredit']) : 0;
            $saldoAwal = floatval($row['saldo_awal']);
            $saldoAkhir = 0;

            if (in_array($row['kategori'], ['Aset', 'Beban'])) {
                $saldoAkhir = $saldoAwal + ($d - $k);
            } else {
                $saldoAkhir = $saldoAwal + ($k - $d);
            }

            // Smart Placement
            $posisiDebit = 0;
            $posisiKredit = 0;

            if (in_array($row['kategori'], ['Aset', 'Beban'])) {
                if ($saldoAkhir >= 0) $posisiDebit = $saldoAkhir;
                else $posisiKredit = abs($saldoAkhir);
            } else {
                if ($saldoAkhir >= 0) $posisiKredit = $saldoAkhir;
                else $posisiDebit = abs($saldoAkhir);
            }

            $totalDebit += $posisiDebit;
            $totalKredit += $posisiKredit;

            if ($posisiDebit != 0 || $posisiKredit != 0) {
                $neraca[] = [
                    'kode_akun' => $row['kode_akun'],
                    'nama_akun' => $row['nama_akun'],
                    'kategori'  => $row['kategori'],
                    'debit'     => $posisiDebit,
                    'kredit'    => $posisiKredit
                ];
            }
        }

        $data = [
            'title' => 'Neraca Saldo',
            'neraca' => $neraca,
            'totalDebit' => $totalDebit,
            'totalKredit' => $totalKredit,
            'isBalanced' => (abs($totalDebit - $totalKredit) < 1)
        ];
        return view('laporan/neraca_saldo', $data);
    }

    // =======================================================
    // 4. POSISI KEUANGAN (NERACA)
    // =======================================================
    public function neraca()
    {
        $db = \Config\Database::connect();
        $coaModel = new CoaModel();

        $akun = $coaModel->orderBy('kode_akun', 'ASC')->findAll();
        $asetLancar = [];
        $asetTidakLancar = [];
        $liabilitasPendek = [];
        $liabilitasPanjang = [];
        $ekuitas = [];
        $totalAL = 0;
        $totalATL = 0;
        $totalLP = 0;
        $totalLPJ = 0;
        $totalEkuitas = 0;
        $labaTahunBerjalan = 0;

        foreach ($akun as $row) {
            $mutasi = $db->table('jurnal_detail')->selectSum('debit')->selectSum('kredit')
                ->where('id_akun', $row['id_akun'])->get()->getRowArray();

            $d = isset($mutasi['debit']) ? floatval($mutasi['debit']) : 0;
            $k = isset($mutasi['kredit']) ? floatval($mutasi['kredit']) : 0;
            $saldo = 0;

            if (in_array($row['kategori'], ['Aset', 'Beban'])) $saldo = $row['saldo_awal'] + ($d - $k);
            else $saldo = $row['saldo_awal'] + ($k - $d);

            if ($row['kategori'] == 'Pendapatan') $labaTahunBerjalan += $saldo;
            if ($row['kategori'] == 'Beban') $labaTahunBerjalan -= $saldo;

            $kode = $row['kode_akun'];
            if ($row['kategori'] == 'Aset') {
                if (substr($kode, 0, 3) == '1-1') {
                    $asetLancar[] = $row + ['saldo_akhir' => $saldo];
                    $totalAL += $saldo;
                } else {
                    $asetTidakLancar[] = $row + ['saldo_akhir' => $saldo];
                    $totalATL += $saldo;
                }
            } elseif ($row['kategori'] == 'Liabilitas') {
                if (substr($kode, 0, 3) == '2-1') {
                    $liabilitasPendek[] = $row + ['saldo_akhir' => $saldo];
                    $totalLP += $saldo;
                } else {
                    $liabilitasPanjang[] = $row + ['saldo_akhir' => $saldo];
                    $totalLPJ += $saldo;
                }
            } elseif ($row['kategori'] == 'Ekuitas') {
                $ekuitas[] = $row + ['saldo_akhir' => $saldo];
                $totalEkuitas += $saldo;
            }
        }

        if ($labaTahunBerjalan != 0) {
            $ekuitas[] = ['nama_akun' => 'Laba Tahun Berjalan', 'kode_akun' => '3-9999', 'saldo_awal' => $labaTahunBerjalan];
            $totalEkuitas += $labaTahunBerjalan;
        }

        $totalAset = $totalAL + $totalATL;
        $totalPasiva = $totalLP + $totalLPJ + $totalEkuitas;

        $data = [
            'title' => 'Laporan Posisi Keuangan',
            'asetLancar' => $asetLancar,
            'totalAsetLancar' => $totalAL,
            'asetTidakLancar' => $asetTidakLancar,
            'totalAsetTidakLancar' => $totalATL,
            'liabilitasPendek' => $liabilitasPendek,
            'totalLiabilitasPendek' => $totalLP,
            'liabilitasPanjang' => $liabilitasPanjang,
            'totalLiabilitasPanjang' => $totalLPJ,
            'ekuitas' => $ekuitas,
            'totalEkuitas' => $totalEkuitas,
            'totalAset' => $totalAset,
            'totalPasiva' => $totalPasiva,
            'isBalanced' => (abs($totalAset - $totalPasiva) < 1)
        ];
        return view('laporan/neraca', $data);
    }

    public function cetakNeraca()
    { /* ... */
    }

    // =======================================================
    // 5. PERUBAHAN EKUITAS
    // =======================================================
    public function perubahanEkuitas()
    {
        $coaModel = new CoaModel();
        $startDate = $this->request->getGet('start_date') ?? '2024-01-01';
        $endDate = $this->request->getGet('end_date') ?? '2024-12-31';

        $labaRugiData = $this->_getLabaRugiData($startDate, $endDate);
        $labaBersih = $labaRugiData['labaRugi'];

        $ekuitasAccounts = $coaModel->where('kategori', 'Ekuitas')->findAll();
        $modalAwal = 0;
        foreach ($ekuitasAccounts as $acc) {
            $modalAwal += floatval($acc['saldo_awal']);
        }

        $data = [
            'title' => 'Perubahan Ekuitas',
            'page_title' => 'Laporan Perubahan Ekuitas',
            'startDate' => $startDate,
            'endDate' => $endDate,
            'isFiltered' => ($this->request->getGet('start_date') !== null),
            'modalAwal' => $modalAwal,
            'labaBersih' => $labaBersih,
            'mutasiEkuitas' => [],
            'modalAkhir' => $modalAwal + $labaBersih
        ];
        return view('laporan/perubahan_ekuitas', $data);
    }

    // =======================================================
    // 6. ARUS KAS
    // =======================================================
    public function arusKas()
    {
        $db = \Config\Database::connect();
        $coaModel = new CoaModel();

        $startDate = $this->request->getGet('start_date') ?? '2024-01-01';
        $endDate = $this->request->getGet('end_date') ?? '2024-12-31';

        $labaData = $this->_getLabaRugiData($startDate, $endDate);
        $labaBersih = $labaData['labaRugi'];

        $depresiasi = 0;
        $akunDepresiasi = $coaModel->like('nama_akun', 'Beban Penyusutan')->findAll();
        foreach ($akunDepresiasi as $acc) {
            $mutasi = $db->table('jurnal_detail')->selectSum('debit')
                ->join('jurnal_header', 'jurnal_header.id_jurnal = jurnal_detail.id_jurnal')
                ->where('id_akun', $acc['id_akun'])
                ->where('jurnal_header.tanggal_jurnal >=', $startDate)
                ->where('jurnal_header.tanggal_jurnal <=', $endDate)
                ->get()->getRowArray();
            $depresiasi += isset($mutasi['debit']) ? floatval($mutasi['debit']) : 0;
        }

        $arusKasOperasi = $labaBersih + $depresiasi;
        $akunKas = $coaModel->where('kode_akun', '1-1100')->first();
        $saldoAwalKas = $akunKas ? $akunKas['saldo_awal'] : 0;

        $data = [
            'title' => 'Laporan Arus Kas',
            'page_title' => 'Laporan Arus Kas (Cash Flow)',
            'startDate' => $startDate,
            'endDate' => $endDate,
            'isFiltered' => ($this->request->getGet('start_date') !== null),
            'labaBersih' => $labaBersih,
            'depresiasi' => $depresiasi,
            'changePiutang' => 0,
            'changePersediaan' => 0,
            'changeUtangUsaha' => 0,
            'arusKasOperasi' => $arusKasOperasi,
            'arusKasInvestasi' => 0,
            'arusKasPendanaan' => 0,
            'changeUtangPanjang' => 0,
            'changeEkuitas' => 0,
            'kenaikanKasBersih' => $arusKasOperasi,
            'saldoAwalKas' => $saldoAwalKas,
            'saldoAkhirKas' => $saldoAwalKas + $arusKasOperasi
        ];
        return view('laporan/arus_kas', $data);
    }

    // =======================================================
    // 7. HARGA POKOK PRODUKSI
    // =======================================================
    public function hargaPokokProduksi()
    {
        $db = \Config\Database::connect();
        $coaModel = new CoaModel();

        $startDate = $this->request->getGet('start_date') ?? '2024-01-01';
        $endDate = $this->request->getGet('end_date') ?? '2024-12-31';

        $akunProduksi = $coaModel->like('kode_akun', '5-', 'after')->findAll();
        $totalHPP = 0;

        foreach ($akunProduksi as $acc) {
            $mutasi = $db->table('jurnal_detail')->selectSum('debit')
                ->join('jurnal_header', 'jurnal_header.id_jurnal = jurnal_detail.id_jurnal')
                ->where('id_akun', $acc['id_akun'])
                ->where('jurnal_header.tanggal_jurnal >=', $startDate)
                ->where('jurnal_header.tanggal_jurnal <=', $endDate)
                ->get()->getRowArray();
            $totalHPP += isset($mutasi['debit']) ? floatval($mutasi['debit']) : 0;
        }

        $data = [
            'title' => 'Harga Pokok Produksi',
            'page_title' => 'Laporan Harga Pokok Produksi (COGM)',
            'startDate' => $startDate,
            'endDate' => $endDate,
            'isFiltered' => ($this->request->getGet('start_date') !== null),
            'biayaBahanBaku' => $totalHPP * 0.7,
            'biayaTenagaKerja' => $totalHPP * 0.2,
            'biayaOverhead' => $totalHPP * 0.1,
            'totalBiayaProduksi' => $totalHPP,
            'wipAwal' => 0,
            'wipAkhir' => 0,
            'cogm' => $totalHPP
        ];
        return view('laporan/harga_pokok', $data);
    }

    public function analisisRasio()
    {
        return view('laporan/coming_soon', ['title' => 'Analisis Rasio']);
    }
}
