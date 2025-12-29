<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FixedAssetModel;
use App\Models\CoaModel;
use App\Models\JurnalHeaderModel;
use App\Models\JurnalDetailModel;

class Aset extends BaseController
{
    protected $assetModel;
    protected $coaModel;

    public function __construct()
    {
        $this->assetModel = new FixedAssetModel();
        $this->coaModel = new CoaModel();
    }

    public function index()
    {
        $jurnalHeaderModel = new JurnalHeaderModel();

        // 1. Ambil Data Aset (Join dengan akun)
        $db = \Config\Database::connect();
        $builder = $db->table('fixed_assets');
        $builder->select('fixed_assets.*, coa.nama_akun as nama_aset_akun');
        $builder->join('chart_of_accounts as coa', 'coa.id_akun = fixed_assets.akun_aset_id', 'left');
        $assets = $builder->get()->getResultArray();

        // 2. AMBIL RIWAYAT PENYUSUTAN
        // Cari jurnal yang deskripsinya mengandung 'Penyusutan Aset Tetap'
        $riwayatPenyusutan = $jurnalHeaderModel
            ->like('deskripsi', 'Penyusutan Aset Tetap')
            ->orderBy('tanggal_jurnal', 'DESC')
            ->findAll();

        $data = [
            'title' => 'Manajemen Aset',
            'page_title' => 'Fixed Asset Management',
            'assets' => $assets,
            'riwayat' => $riwayatPenyusutan
        ];
        return view('dapur/aset/index', $data);
    }

    public function new()
    {
        $data = [
            'title' => 'Tambah Aset',
            'page_title' => 'Registrasi Aset Baru',
            'accounts' => $this->coaModel->orderBy('kode_akun', 'ASC')->findAll()
        ];
        return view('dapur/aset/new', $data);
    }

    public function create()
    {
        // Bersihkan format rupiah (hapus titik)
        $harga = str_replace('.', '', $this->request->getPost('harga_perolehan'));
        $sisa  = str_replace('.', '', $this->request->getPost('nilai_sisa'));

        $this->assetModel->save([
            'nama_aset' => $this->request->getPost('nama_aset'),
            'tanggal_beli' => $this->request->getPost('tanggal_beli'),
            'harga_perolehan' => $harga,
            'nilai_sisa' => $sisa,
            'umur_ekonomis' => $this->request->getPost('umur_ekonomis'),
            'metode' => $this->request->getPost('metode'),
            'akun_aset_id' => $this->request->getPost('akun_aset_id'),
            'akun_akumulasi_id' => $this->request->getPost('akun_akumulasi_id'),
            'akun_beban_id' => $this->request->getPost('akun_beban_id'),
        ]);

        return redirect()->to('/dapur/aset')->with('success', 'Aset Tetap berhasil didaftarkan!');
    }

    public function edit($id)
    {
        $aset = $this->assetModel->find($id);
        if (!$aset) return redirect()->to('/dapur/aset')->with('error', 'Aset tidak ditemukan.');

        $data = [
            'title' => 'Edit Aset Tetap',
            'aset' => $aset,
            'akunAset' => $this->coaModel->where('kategori', 'Aset')->findAll(),
            'akunBeban' => $this->coaModel->where('kategori', 'Beban')->findAll(),
            'akunAkumulasi' => $this->coaModel->where('kategori', 'Aset')->findAll()
        ];

        return view('dapur/aset/edit', $data);
    }

    public function update($id)
    {
        $harga = str_replace('.', '', $this->request->getPost('harga_perolehan'));
        $sisa  = str_replace('.', '', $this->request->getPost('nilai_sisa'));

        $dataUpdate = [
            'nama_aset' => $this->request->getPost('nama_aset'),
            'tanggal_beli' => $this->request->getPost('tanggal_beli'),
            'harga_perolehan' => $harga,
            'nilai_sisa' => $sisa,
            'umur_ekonomis' => $this->request->getPost('umur_ekonomis'),
            'metode' => $this->request->getPost('metode'),
            'akun_aset_id' => $this->request->getPost('akun_aset_id'),
            'akun_akumulasi_id' => $this->request->getPost('akun_akumulasi_id'),
            'akun_beban_id' => $this->request->getPost('akun_beban_id'),
        ];
        $this->assetModel->update($id, $dataUpdate);
        return redirect()->to('/dapur/aset')->with('success', 'Data aset berhasil diperbarui.');
    }

    public function delete($id)
    {
        $this->assetModel->delete($id);
        return redirect()->to('/dapur/aset')->with('success', 'Aset berhasil dihapus.');
    }

    // --- FITUR MAGIC: GENERATE JURNAL OTOMATIS ---
    public function generatePenyusutan()
    {
        $periodeInput = $this->request->getPost('periode');

        if (!$periodeInput) {
            return redirect()->back()->with('error', 'Periode belum dipilih!');
        }

        $tanggalJurnal = date('Y-m-t', strtotime($periodeInput . '-01'));
        $deskripsiJurnal = "Penyusutan Aset Tetap Periode " . date('F Y', strtotime($tanggalJurnal));

        $jurnalHeaderModel = new JurnalHeaderModel();
        $jurnalDetailModel = new JurnalDetailModel();

        // 1. CEK DUPLIKASI
        $cekJurnal = $jurnalHeaderModel->where('deskripsi', $deskripsiJurnal)->first();
        if ($cekJurnal) {
            return redirect()->back()->with('error', "Gagal! Penyusutan periode $periodeInput SUDAH PERNAH DIGENERATE. Cek menu 'Riwayat & Reset' untuk menghapusnya.");
        }

        // 2. AMBIL ASET
        $assets = $this->assetModel->findAll();
        if (empty($assets)) {
            return redirect()->back()->with('error', 'Tidak ada data aset untuk disusutkan.');
        }

        // 3. MULAI TRANSAKSI
        $db = \Config\Database::connect();
        $db->transStart();

        // Insert Header
        $dataHeader = [
            'tanggal_jurnal' => $tanggalJurnal,
            'deskripsi'      => $deskripsiJurnal,
        ];

        // Coba Insert Header
        if (!$jurnalHeaderModel->insert($dataHeader)) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Gagal menyimpan Header Jurnal.');
        }

        $idJurnalOtomatis = $jurnalHeaderModel->getInsertID();

        $detailData = [];
        $countSukses = 0;
        $countSkipBelumBeli = 0;
        $countSkipSudahHabis = 0;
        $countSkipMetodeSalah = 0;

        foreach ($assets as $aset) {
            // A. Cek Metode & Umur
            if ($aset['metode'] !== 'Garis Lurus' || $aset['umur_ekonomis'] <= 0) {
                $countSkipMetodeSalah++;
                continue;
            }

            // B. Cek Tanggal Beli
            $bulanBeli = date('Y-m', strtotime($aset['tanggal_beli']));
            if ($periodeInput < $bulanBeli) {
                $countSkipBelumBeli++;
                continue;
            }

            // C. Cek Batas Umur (Expired)
            $tglBeli = new \DateTime($aset['tanggal_beli']);
            $tglHabis = $tglBeli->modify('+' . intval($aset['umur_ekonomis']) . ' months')->format('Y-m');

            if ($periodeInput >= $tglHabis) {
                $countSkipSudahHabis++;
                continue;
            }

            // D. Hitung Nominal
            $biayaRaw = ($aset['harga_perolehan'] - $aset['nilai_sisa']) / $aset['umur_ekonomis'];
            $biayaPerBulan = ceil($biayaRaw);

            // E. Masukkan ke Antrian
            if ($biayaPerBulan > 0 && !empty($aset['akun_beban_id']) && !empty($aset['akun_akumulasi_id'])) {
                $countSukses++;

                // Debit
                $detailData[] = [
                    'id_jurnal' => $idJurnalOtomatis,
                    'id_akun'   => $aset['akun_beban_id'],
                    'debit'     => $biayaPerBulan,
                    'kredit'    => 0
                ];
                // Kredit
                $detailData[] = [
                    'id_jurnal' => $idJurnalOtomatis,
                    'id_akun'   => $aset['akun_akumulasi_id'],
                    'debit'     => 0,
                    'kredit'    => $biayaPerBulan
                ];
            }
        }

        // 4. FINALISASI
        if (!empty($detailData)) {
            $jurnalDetailModel->insertBatch($detailData);

            $db->transComplete();

            if ($db->transStatus() === FALSE) {
                return redirect()->back()->with('error', 'Terjadi kesalahan database saat menyimpan detail jurnal.');
            }

            return redirect()->to('/dapur/aset')->with(
                'success',
                "Sukses! Jurnal Penyusutan periode $periodeInput berhasil dibuat. ($countSukses aset dihitung)."
            );
        } else {
            $db->transRollback();

            $msg = "<b>Tidak ada jurnal yang dibuat untuk periode $periodeInput.</b><br><small>";
            if ($countSkipBelumBeli > 0) $msg .= "- $countSkipBelumBeli aset belum dibeli.<br>";
            if ($countSkipSudahHabis > 0) $msg .= "- $countSkipSudahHabis aset sudah habis masa manfaatnya.<br>";
            $msg .= "</small>";

            return redirect()->back()->with('warning', $msg);
        }
    }

    // --- FITUR RESET RIWAYAT ---
    public function deletePenyusutan($idJurnal)
    {
        $headerModel = new JurnalHeaderModel();
        $detailModel = new JurnalDetailModel();

        // Cek apakah jurnal ada
        $cek = $headerModel->find($idJurnal);
        if (!$cek) {
            return redirect()->back()->with('error', 'Data penyusutan tidak ditemukan!');
        }

        // Hapus Detail & Header
        $db = \Config\Database::connect();
        $db->transStart();

        $detailModel->where('id_jurnal', $idJurnal)->delete();
        $headerModel->delete($idJurnal);

        $db->transComplete();

        return redirect()->to('/dapur/aset')->with('success', 'Riwayat penyusutan berhasil dihapus (Reset). Anda bisa generate ulang.');
    }
}
