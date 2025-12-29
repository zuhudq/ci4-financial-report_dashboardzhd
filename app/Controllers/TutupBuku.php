<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CoaModel;
use App\Models\JurnalHeaderModel;
use App\Models\JurnalDetailModel;

class TutupBuku extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Tutup Buku',
            'page_title' => 'Proses Tutup Buku (Closing Entries)'
        ];
        return view('dapur/tutup_buku/index', $data);
    }

    public function proses()
    {
        $bulan = $this->request->getPost('bulan');
        $tahun = $this->request->getPost('tahun');

        // Validasi input sederhana
        if (!$bulan || !$tahun) {
            return redirect()->back()->with('error', 'Harap pilih Bulan dan Tahun!');
        }

        $startDate = "$tahun-$bulan-01";
        $endDate   = date("Y-m-t", strtotime($startDate));

        $coaModel = new CoaModel();
        $headerModel = new JurnalHeaderModel();
        $detailModel = new JurnalDetailModel();

        // 1. Cek apakah sudah pernah tutup buku di periode ini?
        // Kita cek deskripsi jurnal agar tidak dobel
        $cek = $headerModel->like('deskripsi', "Jurnal Penutup Periode $bulan/$tahun")->first();
        if ($cek) {
            return redirect()->back()->with('error', 'Periode ini sudah ditutup sebelumnya! Silakan cek Jurnal Umum.');
        }

        // =========================================================================
        // PERBAIKAN DI SINI: Mengganti 'kategori_akun' menjadi 'kategori'
        // Sesuai dengan struktur tabel chart_of_accounts di database kamu
        // =========================================================================

        // 2. Ambil Semua Akun Pendapatan (Saldo Normal: Kredit) -> Harus di-Debit
        $pendapatanAcc = $coaModel->where('kategori', 'Pendapatan')->findAll();

        $closingEntries = [];
        $totalPendapatan = 0;

        foreach ($pendapatanAcc as $acc) {
            // Hitung Saldo Akhir akun Pendapatan
            $saldo = $this->getSaldoPeriode($acc['id_akun'], $startDate, $endDate, 'kredit');

            // Jika ada saldonya, kita nol-kan dengan mendebitnya
            if ($saldo > 0) {
                $closingEntries[] = [
                    'id_akun' => $acc['id_akun'],
                    'debit'   => $saldo,   // Dibalik jadi Debit
                    'kredit'  => 0
                ];
                $totalPendapatan += $saldo;
            }
        }

        // 3. Ambil Semua Akun Beban (Saldo Normal: Debit) -> Harus di-Kredit
        // PERBAIKAN: Mengganti 'kategori_akun' menjadi 'kategori'
        $bebanAcc = $coaModel->where('kategori', 'Beban')->findAll();

        $totalBeban = 0;

        foreach ($bebanAcc as $acc) {
            // Hitung Saldo Akhir akun Beban
            $saldo = $this->getSaldoPeriode($acc['id_akun'], $startDate, $endDate, 'debit');

            // Jika ada saldonya, kita nol-kan dengan mengkreditnya
            if ($saldo > 0) {
                $closingEntries[] = [
                    'id_akun' => $acc['id_akun'],
                    'debit'   => 0,
                    'kredit'  => $saldo  // Dibalik jadi Kredit
                ];
                $totalBeban += $saldo;
            }
        }

        // Jika tidak ada transaksi sama sekali, batalkan
        if (empty($closingEntries)) {
            return redirect()->back()->with('error', 'Tidak ada transaksi Pendapatan/Beban yang perlu ditutup pada periode ini.');
        }

        // 4. Hitung Selisih (Laba/Rugi) -> Masuk ke Saldo Laba (Retained Earnings)
        // Sesuai DB kamu ID Akun 46: 'Saldo Laba (Retained Earnings)'
        // Kita cari pakai 'like' nama_akun agar fleksibel
        $saldoLabaAcc = $coaModel->like('nama_akun', 'Saldo Laba')->first();

        if (!$saldoLabaAcc) {
            // Fallback manual jika like gagal, cari kategori Ekuitas yang namanya mengandung Laba
            $saldoLabaAcc = $coaModel->where('kategori', 'Ekuitas')->like('nama_akun', 'Laba')->first();

            if (!$saldoLabaAcc) {
                return redirect()->back()->with('error', 'Akun "Saldo Laba" tidak ditemukan di COA (Chart of Accounts). Pastikan ada akun kategori Ekuitas dengan nama "Saldo Laba".');
            }
        }

        $labaBersih = $totalPendapatan - $totalBeban;

        if ($labaBersih > 0) {
            // Untung (Laba) -> Modal Bertambah di Kredit
            $closingEntries[] = [
                'id_akun' => $saldoLabaAcc['id_akun'],
                'debit'   => 0,
                'kredit'  => $labaBersih
            ];
        } else {
            // Rugi -> Modal Berkurang di Debit
            $closingEntries[] = [
                'id_akun' => $saldoLabaAcc['id_akun'],
                'debit'   => abs($labaBersih),
                'kredit'  => 0
            ];
        }

        // 5. EKSEKUSI SIMPAN JURNAL PENUTUP
        // Simpan Header
        $headerData = [
            'tanggal_jurnal' => $endDate, // Jurnal penutup selalu di akhir bulan
            'deskripsi'      => "Jurnal Penutup Periode $bulan/$tahun",
            'created_at'     => date('Y-m-d H:i:s')
        ];

        $headerModel->insert($headerData);
        $idJurnal = $headerModel->getInsertID();

        // Simpan Detail (Looping array closingEntries)
        foreach ($closingEntries as $row) {
            $detailModel->insert([
                'id_jurnal' => $idJurnal,
                'id_akun'   => $row['id_akun'],
                'debit'     => $row['debit'],
                'kredit'    => $row['kredit']
            ]);
        }

        return redirect()->to('/dapur/jurnal')->with('success', "Tutup Buku Periode $bulan/$tahun Berhasil! Jurnal Penutup telah dibuat otomatis.");
    }

    // --- Helper Private untuk Hitung Saldo ---
    private function getSaldoPeriode($idAkun, $start, $end, $normalPos)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('jurnal_detail');
        $builder->selectSum('debit');
        $builder->selectSum('kredit');
        $builder->join('jurnal_header', 'jurnal_header.id_jurnal = jurnal_detail.id_jurnal');
        $builder->where('id_akun', $idAkun);
        $builder->where('jurnal_header.tanggal_jurnal >=', $start);
        $builder->where('jurnal_header.tanggal_jurnal <=', $end);

        // Exclude Jurnal Penutup agar tidak double counting jika dijalankan ulang (safety)
        $builder->notLike('jurnal_header.deskripsi', 'Jurnal Penutup');

        $row = $builder->get()->getRow();

        $debit  = $row->debit ?? 0;
        $kredit = $row->kredit ?? 0;

        if ($normalPos == 'debit') {
            return $debit - $kredit;
        } else {
            return $kredit - $debit;
        }
    }
}
