<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CoaModel;

class Coa extends BaseController
{
    protected $coaModel;

    public function __construct()
    {
        $this->coaModel = new CoaModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Chart of Accounts',
            'page_title' => 'Daftar Akun (COA)',
            'accounts' => $this->coaModel->orderBy('kode_akun', 'ASC')->findAll()
        ];
        return view('dapur/coa/index', $data);
    }

    public function new()
    {
        $data = [
            'title' => 'Tambah Akun',
            'page_title' => 'Tambah Akun Baru'
        ];
        return view('dapur/coa/new', $data);
    }

    public function create()
    {
        // 1. Validasi Input (Sesuai nama field di Form 'new.php')
        if (!$this->validate([
            'kode_akun' => 'required|is_unique[chart_of_accounts.kode_akun]',
            'nama_akun' => 'required',
            'kategori'  => 'required', // Pastikan ini 'kategori', BUKAN 'kategori_akun'
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // 2. Bersihkan format rupiah (hapus titik)
        $saldoAwal = str_replace('.', '', $this->request->getPost('saldo_awal'));

        // 3. Simpan ke Database
        $this->coaModel->save([
            'kode_akun' => $this->request->getPost('kode_akun'),
            'nama_akun' => $this->request->getPost('nama_akun'),
            'kategori'  => $this->request->getPost('kategori'), // Sesuai DB baru
            'saldo_awal' => $saldoAwal
        ]);

        return redirect()->to('/dapur/coa')->with('success', 'Akun berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $account = $this->coaModel->find($id);

        if (!$account) {
            return redirect()->to('/dapur/coa')->with('error', 'Akun tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Akun',
            'page_title' => 'Edit Data Akun',
            'account' => $account
        ];

        return view('dapur/coa/edit', $data);
    }

    public function update($id)
    {
        // Cek dulu datanya
        $oldData = $this->coaModel->find($id);
        if (!$oldData) {
            return redirect()->to('/dapur/coa')->with('error', 'Data tidak ditemukan.');
        }

        // Rule Validasi (Unique Kode Akun kecuali punya sendiri)
        $ruleKode = ($oldData['kode_akun'] == $this->request->getPost('kode_akun'))
            ? 'required'
            : 'required|is_unique[chart_of_accounts.kode_akun]';

        if (!$this->validate([
            'kode_akun' => $ruleKode,
            'nama_akun' => 'required',
            'kategori'  => 'required',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $saldoAwal = str_replace('.', '', $this->request->getPost('saldo_awal'));

        $this->coaModel->save([
            'id_akun'   => $id,
            'kode_akun' => $this->request->getPost('kode_akun'),
            'nama_akun' => $this->request->getPost('nama_akun'),
            'kategori'  => $this->request->getPost('kategori'), // Update ke 'kategori'
            'saldo_awal' => $saldoAwal
        ]);

        return redirect()->to('/dapur/coa')->with('success', 'Akun berhasil diperbarui.');
    }

    public function delete($id)
    {
        $db = \Config\Database::connect();

        // 1. CEK SAFETY: Apakah akun sudah dipakai di Jurnal?
        $usedInJurnal = $db->table('jurnal_detail')->where('id_akun', $id)->countAllResults();
        if ($usedInJurnal > 0) {
            return redirect()->back()->with('error', 'Gagal Hapus! Akun ini sudah memiliki riwayat transaksi jurnal. Menghapusnya akan merusak Laporan Keuangan.');
        }

        // 2. CEK SAFETY: Apakah akun dipakai di Aset Tetap?
        $usedInAsset = $db->table('fixed_assets')
            ->groupStart()
            ->where('akun_aset_id', $id)
            ->orWhere('akun_akumulasi_id', $id)
            ->orWhere('akun_beban_id', $id)
            ->groupEnd()
            ->countAllResults();

        if ($usedInAsset > 0) {
            return redirect()->back()->with('error', 'Gagal Hapus! Akun ini terhubung dengan data Aset Tetap.');
        }

        // 3. JIKA AMAN, HAPUS
        $this->coaModel->delete($id);
        return redirect()->to('/dapur/coa')->with('success', 'Akun berhasil dihapus permanen.');
    }
}
