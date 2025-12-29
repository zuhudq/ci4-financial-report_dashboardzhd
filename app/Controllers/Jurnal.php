<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\JurnalHeaderModel;
use App\Models\JurnalDetailModel;
use App\Models\CoaModel;

class Jurnal extends BaseController
{
    protected $headerModel;
    protected $detailModel;
    protected $coaModel;

    public function __construct()
    {
        $this->headerModel = new JurnalHeaderModel();
        $this->detailModel = new JurnalDetailModel();
        $this->coaModel = new CoaModel();
    }

    public function index()
    {
        // KITA GUNAKAN QUERY BUILDER AGAR BISA JOIN TABEL
        $db      = \Config\Database::connect();
        $builder = $db->table('jurnal_detail');

        // Select data lengkap: Tanggal, Deskripsi, Nama Akun, Debit, Kredit
        // HAPUS bagian 'jurnal_header.ref,'
        $builder->select('jurnal_header.id_jurnal, jurnal_header.tanggal_jurnal, jurnal_header.deskripsi, chart_of_accounts.nama_akun, chart_of_accounts.kode_akun, jurnal_detail.debit, jurnal_detail.kredit');

        // Join 3 Tabel: Detail -> Header -> Akun
        $builder->join('jurnal_header', 'jurnal_header.id_jurnal = jurnal_detail.id_jurnal');
        $builder->join('chart_of_accounts', 'chart_of_accounts.id_akun = jurnal_detail.id_akun');

        // Urutkan dari yang terbaru
        $builder->orderBy('jurnal_header.tanggal_jurnal', 'DESC');
        $builder->orderBy('jurnal_header.id_jurnal', 'DESC'); // Biar debit kredit ngumpul per transaksi

        $query = $builder->get();

        $data = [
            'title' => 'Jurnal Umum',
            'page_title' => 'Buku Jurnal Umum (General Journal)',
            'jurnal' => $query->getResultArray() // Kirim data hasil join
        ];

        return view('dapur/jurnal/index', $data);
    }

    public function new()
    {
        // Ambil data akun untuk dropdown di Smart Input
        $data = [
            'title' => 'Input Jurnal',
            'page_title' => 'Input Jurnal Umum (Smart Entry)',
            'accounts' => $this->coaModel->orderBy('kode_akun', 'ASC')->findAll()
        ];
        // Pastikan file ini ada di app/Views/dapur/jurnal/new.php
        return view('dapur/jurnal/new', $data);
    }

    public function create()
    {
        // 1. Ambil Data dari Smart Form
        $tanggal    = $this->request->getPost('tanggal_transaksi');
        $deskripsi  = $this->request->getPost('deskripsi');

        // Data Array dari Tabel Dinamis
        $akunIds    = $this->request->getPost('akun_id'); // Array
        $debits     = $this->request->getPost('debit');   // Array
        $kredits    = $this->request->getPost('kredit');  // Array

        if (!$akunIds) {
            return redirect()->back()->with('error', 'Mohon isi detail transaksi.');
        }

        // 2. Validasi Balance (Server Side Check)
        $totalDebit = 0;
        $totalKredit = 0;

        // Bersihkan format rupiah (hapus titik)
        $cleanDebits = [];
        $cleanKredits = [];

        foreach ($debits as $d) {
            $val = (float)str_replace('.', '', $d);
            $cleanDebits[] = $val;
            $totalDebit += $val;
        }
        foreach ($kredits as $k) {
            $val = (float)str_replace('.', '', $k);
            $cleanKredits[] = $val;
            $totalKredit += $val;
        }

        // Cek Balance
        if ($totalDebit != $totalKredit) {
            return redirect()->back()->withInput()->with('error', 'Jurnal TIDAK BALANCE! Total Debit: ' . number_format($totalDebit) . ', Total Kredit: ' . number_format($totalKredit));
        }

        if ($totalDebit == 0) {
            return redirect()->back()->withInput()->with('error', 'Nominal transaksi tidak boleh 0.');
        }

        // 3. Simpan Header Jurnal
        $headerData = [
            'tanggal_jurnal' => $tanggal,
            'deskripsi'      => $deskripsi,
            'created_at'     => date('Y-m-d H:i:s')
        ];

        $this->headerModel->insert($headerData);
        $idJurnal = $this->headerModel->getInsertID();

        // 4. Simpan Detail Jurnal (Looping)
        for ($i = 0; $i < count($akunIds); $i++) {
            if (!empty($akunIds[$i])) {
                $this->detailModel->insert([
                    'id_jurnal' => $idJurnal,
                    'id_akun'   => $akunIds[$i],
                    'debit'     => $cleanDebits[$i],
                    'kredit'    => $cleanKredits[$i]
                ]);
            }
        }

        return redirect()->to('/dapur/jurnal')->with('success', 'Transaksi Berhasil Disimpan!');
    }

    public function detail($id)
    {
        $header = $this->headerModel->find($id);

        // Join ke tabel akun untuk ambil nama akun
        $details = $this->detailModel->select('jurnal_detail.*, chart_of_accounts.nama_akun, chart_of_accounts.kode_akun')
            ->join('chart_of_accounts', 'chart_of_accounts.id_akun = jurnal_detail.id_akun')
            ->where('id_jurnal', $id)
            ->findAll();

        $data = [
            'title' => 'Detail Jurnal',
            'page_title' => 'Detail Transaksi',
            'header' => $header,
            'details' => $details
        ];

        return view('dapur/jurnal/detail', $data);
    }

    // Fungsi delete standar...
    public function delete($id)
    {
        $this->headerModel->delete($id);
        // Detail otomatis terhapus jika setting database CASCADE, jika tidak, hapus manual detailnya
        $this->detailModel->where('id_jurnal', $id)->delete();

        return redirect()->to('/dapur/jurnal')->with('success', 'Data berhasil dihapus.');
    }
}
