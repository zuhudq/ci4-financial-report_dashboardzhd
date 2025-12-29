<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CoaModel;
use App\Models\JurnalDetailModel;

class Analisis extends BaseController
{
    public function rasio()
    {
        // Default Periode: Sampai Hari Ini
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-t');

        $coaModel = new CoaModel();
        $detailModel = new JurnalDetailModel();

        // ---------------------------------------------------------
        // 1. HELPER: AMBIL SALDO AKUN (Posisi per Tanggal)
        // ---------------------------------------------------------
        $getSaldo = function ($kategori, $kodeAwal = null) use ($coaModel, $detailModel, $endDate) {
            $builder = $coaModel->builder();
            $builder->where('kategori', $kategori); // FIX: Pakai 'kategori'
            if ($kodeAwal) {
                $builder->like('kode_akun', $kodeAwal, 'after');
            }
            $accounts = $builder->get()->getResultArray();

            $total = 0;
            foreach ($accounts as $acc) {
                // Hitung mutasi sampai end_date
                $q = $detailModel->builder()
                    ->selectSum('debit')->selectSum('kredit')
                    ->join('jurnal_header', 'jurnal_header.id_jurnal = jurnal_detail.id_jurnal')
                    ->where('id_akun', $acc['id_akun'])
                    ->where('jurnal_header.tanggal_jurnal <=', $endDate)
                    ->get()->getRow();

                // Rumus Dasar: Saldo Awal + Debit - Kredit
                $mutasi = ($q->debit ?? 0) - ($q->kredit ?? 0);
                $saldo = $acc['saldo_awal'] + $mutasi;

                // FIX: Tentukan Posisi Saldo Normal secara Manual
                $isKredit = in_array($acc['kategori'], ['Liabilitas', 'Ekuitas', 'Pendapatan']);
                if (strpos($acc['nama_akun'], 'Akumulasi') !== false) {
                    $isKredit = true;
                }

                // Jika saldo normalnya Kredit, kita kali -1 agar hasilnya Positif untuk perhitungan rasio
                // (Karena rumus Debit - Kredit akan menghasilkan minus untuk akun kredit)
                if ($isKredit) {
                    $saldo *= -1;
                }
                $total += $saldo;
            }
            return $total;
        };

        // ---------------------------------------------------------
        // 2. HELPER: AMBIL LABA BERSIH (Periode Berjalan)
        // ---------------------------------------------------------
        // Kita butuh hitung Laba Rugi dari Awal Tahun sampai End Date
        $startYear = date('Y-01-01', strtotime($endDate));

        $getLabaRugi = function () use ($coaModel, $detailModel, $startYear, $endDate) {
            $pendapatan = 0;
            $beban = 0;

            // Pendapatan (FIX: Pakai 'kategori')
            $accPend = $coaModel->where('kategori', 'Pendapatan')->findAll();
            foreach ($accPend as $acc) {
                $q = $detailModel->builder()->selectSum('debit')->selectSum('kredit')
                    ->join('jurnal_header', 'jurnal_header.id_jurnal = jurnal_detail.id_jurnal')
                    ->where('id_akun', $acc['id_akun'])
                    ->where('jurnal_header.tanggal_jurnal >=', $startYear)
                    ->where('jurnal_header.tanggal_jurnal <=', $endDate)
                    ->get()->getRow();
                $pendapatan += (($q->kredit ?? 0) - ($q->debit ?? 0));
            }

            // Beban (FIX: Pakai 'kategori')
            $accBeban = $coaModel->where('kategori', 'Beban')->findAll();
            foreach ($accBeban as $acc) {
                $q = $detailModel->builder()->selectSum('debit')->selectSum('kredit')
                    ->join('jurnal_header', 'jurnal_header.id_jurnal = jurnal_detail.id_jurnal')
                    ->where('id_akun', $acc['id_akun'])
                    ->where('jurnal_header.tanggal_jurnal >=', $startYear)
                    ->where('jurnal_header.tanggal_jurnal <=', $endDate)
                    ->get()->getRow();
                $beban += (($q->debit ?? 0) - ($q->kredit ?? 0));
            }

            return ['laba' => $pendapatan - $beban, 'pendapatan' => $pendapatan];
        };

        // ---------------------------------------------------------
        // 3. TARIK DATA UTAMA
        // ---------------------------------------------------------

        // A. Komponen Neraca
        $asetLancar    = $getSaldo('Aset', '1-1'); // Asumsi kode 1-1xxx adalah Aset Lancar
        $asetTetap     = $getSaldo('Aset', '1-2');
        $totalAset     = $asetLancar + $asetTetap;

        $utangLancar   = $getSaldo('Liabilitas', '2-1'); // Asumsi kode 2-1xxx Utang Lancar
        $utangPanjang  = $getSaldo('Liabilitas', '2-2');
        $totalLiabilitas = $utangLancar + $utangPanjang;

        $totalEkuitas  = $getSaldo('Ekuitas'); // Ini ekuitas statis (Modal Saham)

        // B. Komponen Laba Rugi
        $lrData = $getLabaRugi();
        $labaBersih = $lrData['laba'];
        $totalPendapatan = $lrData['pendapatan'];

        // Tambahkan Laba Berjalan ke Ekuitas Total agar Balance Sheet Imbang
        $totalEkuitas += $labaBersih;

        // ---------------------------------------------------------
        // 4. HITUNG RASIO (THE MATHEMATICS)
        // ---------------------------------------------------------

        // 1. Current Ratio (Likuiditas) -> Kemampuan bayar utang jangka pendek
        // Rumus: Aset Lancar / Utang Lancar
        $currentRatio = ($utangLancar > 0) ? ($asetLancar / $utangLancar) : 0;

        // 2. Debt to Equity Ratio (Solvabilitas) -> Seberapa besar modal dari utang
        // Rumus: Total Liabilitas / Total Ekuitas
        $der = ($totalEkuitas > 0) ? ($totalLiabilitas / $totalEkuitas) : 0;

        // 3. Net Profit Margin (Profitabilitas) -> Efisiensi Laba
        // Rumus: Laba Bersih / Pendapatan * 100
        $npm = ($totalPendapatan > 0) ? ($labaBersih / $totalPendapatan) * 100 : 0;

        // 4. Return on Asset (ROA) -> Efisiensi Aset menghasilkan Laba
        // Rumus: Laba Bersih / Total Aset * 100
        $roa = ($totalAset > 0) ? ($labaBersih / $totalAset) * 100 : 0;

        $data = [
            'title' => 'Analisis Rasio',
            'page_title' => 'Analisis Rasio Keuangan (Financial Ratios)',
            'endDate' => $endDate,

            // Data Mentah (Untuk Debugging/Display jika perlu)
            'asetLancar' => $asetLancar,
            'utangLancar' => $utangLancar,
            'totalLiabilitas' => $totalLiabilitas,
            'totalEkuitas' => $totalEkuitas,
            'labaBersih' => $labaBersih,

            // Hasil Rasio
            'currentRatio' => $currentRatio,
            'der' => $der,
            'npm' => $npm,
            'roa' => $roa
        ];

        return view('analisis/rasio', $data);
    }
}
