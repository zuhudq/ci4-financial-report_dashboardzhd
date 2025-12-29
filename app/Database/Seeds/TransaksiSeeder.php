<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TransaksiSeeder extends Seeder
{
    public function run()
    {
        // KITA TIDAK MENGHAPUS DATA AKUN/ASET (Karena sudah disetup MayoraSeeder)
        // KITA HANYA MEMBERSIHKAN TRANSAKSI JURNAL SEBELUMNYA AGAR BERSIH
        $this->db->disableForeignKeyChecks();
        $this->db->query("TRUNCATE TABLE jurnal_detail");
        $this->db->query("TRUNCATE TABLE jurnal_header");
        $this->db->query("ALTER TABLE jurnal_detail AUTO_INCREMENT = 1");
        $this->db->query("ALTER TABLE jurnal_header AUTO_INCREMENT = 1");
        $this->db->enableForeignKeyChecks();

        // Helper untuk ambil ID Akun berdasarkan Kode
        $getAkunId = function ($kode) {
            $query = $this->db->table('chart_of_accounts')->where('kode_akun', $kode)->get()->getRow();
            return $query ? $query->id_akun : null;
        };

        // ID AKUN PENTING
        $idKas          = $getAkunId('1-1100'); // Kas
        $idPiutang      = $getAkunId('1-1200'); // Piutang Usaha
        $idPersediaan   = $getAkunId('1-1400'); // Persediaan
        $idUtang        = $getAkunId('2-1200'); // Utang Usaha

        $idPenjualan    = $getAkunId('4-1000'); // Penjualan
        $idHPP          = $getAkunId('5-1000'); // HPP (COGS)

        $idBebanGaji    = $getAkunId('6-2100'); // Beban Gaji
        $idBebanUmum    = $getAkunId('6-2000'); // Beban Umum
        $idBebanJual    = $getAkunId('6-1000'); // Beban Penjualan

        // SIMULASI TRANSAKSI JANUARI - DESEMBER 2024
        $bulan = 1;
        while ($bulan <= 12) {

            // Format Tanggal: 2024-01-15, 2024-02-15, dst
            $tglTransaksi = date('Y-m-d', strtotime("2024-$bulan-15"));
            $tglAkhirBulan = date('Y-m-t', strtotime("2024-$bulan-01"));
            $namaBulan = date('F Y', strtotime($tglTransaksi));

            // --- 1. TRANSAKSI PENJUALAN (SALES) ---
            // Anggap Mayora menjual rata-rata 2 Triliun per bulan (Campuran Tunai & Kredit)
            $nilaiPenjualan = rand(2000000000000, 2500000000000); // Random 2T - 2.5T
            $porsiTunai     = $nilaiPenjualan * 0.3; // 30% Tunai
            $porsiKredit    = $nilaiPenjualan * 0.7; // 70% Piutang

            // Header Jurnal Penjualan
            $this->db->table('jurnal_header')->insert([
                'tanggal_jurnal' => $tglTransaksi,
                'deskripsi'      => "Penjualan Produk (Konsolidasi) - $namaBulan",
                'created_at'     => date('Y-m-d H:i:s')
            ]);
            $idJurnalJual = $this->db->insertID();

            // Detail Jurnal Penjualan
            $detailJual = [
                // Debit: Kas (Uang Masuk)
                ['id_jurnal' => $idJurnalJual, 'id_akun' => $idKas, 'debit' => $porsiTunai, 'kredit' => 0],
                // Debit: Piutang (Tagihan)
                ['id_jurnal' => $idJurnalJual, 'id_akun' => $idPiutang, 'debit' => $porsiKredit, 'kredit' => 0],
                // Kredit: Penjualan (Omzet)
                ['id_jurnal' => $idJurnalJual, 'id_akun' => $idPenjualan, 'debit' => 0, 'kredit' => $nilaiPenjualan],
            ];
            $this->db->table('jurnal_detail')->insertBatch($detailJual);


            // --- 2. PENCATATAN HPP (Biaya Barang Terjual) ---
            // HPP biasanya sekitar 60-70% dari Penjualan
            $nilaiHPP = $nilaiPenjualan * 0.75;

            $this->db->table('jurnal_header')->insert([
                'tanggal_jurnal' => $tglTransaksi,
                'deskripsi'      => "Pencatatan Beban Pokok Penjualan - $namaBulan",
                'created_at'     => date('Y-m-d H:i:s')
            ]);
            $idJurnalHPP = $this->db->insertID();

            $detailHPP = [
                // Debit: Beban Pokok Penjualan
                ['id_jurnal' => $idJurnalHPP, 'id_akun' => $idHPP, 'debit' => $nilaiHPP, 'kredit' => 0],
                // Kredit: Persediaan (Barang Keluar)
                ['id_jurnal' => $idJurnalHPP, 'id_akun' => $idPersediaan, 'debit' => 0, 'kredit' => $nilaiHPP],
            ];
            $this->db->table('jurnal_detail')->insertBatch($detailHPP);


            // --- 3. PEMBAYARAN BEBAN OPERASIONAL (Gaji, Listrik, Iklan) ---
            // Gaji sekitar 200 Miliar/bulan, Iklan 100 Miliar, Umum 50 Miliar
            $gaji = 200000000000;
            $iklan = 100000000000;
            $umum = 50000000000;
            $totalBeban = $gaji + $iklan + $umum;

            $this->db->table('jurnal_header')->insert([
                'tanggal_jurnal' => $tglAkhirBulan, // Bayar di akhir bulan
                'deskripsi'      => "Pembayaran Beban Operasional - $namaBulan",
                'created_at'     => date('Y-m-d H:i:s')
            ]);
            $idJurnalBeban = $this->db->insertID();

            $detailBeban = [
                // Debit: Beban-beban
                ['id_jurnal' => $idJurnalBeban, 'id_akun' => $idBebanGaji, 'debit' => $gaji, 'kredit' => 0],
                ['id_jurnal' => $idJurnalBeban, 'id_akun' => $idBebanJual, 'debit' => $iklan, 'kredit' => 0],
                ['id_jurnal' => $idJurnalBeban, 'id_akun' => $idBebanUmum, 'debit' => $umum, 'kredit' => 0],
                // Kredit: Kas (Uang Keluar)
                ['id_jurnal' => $idJurnalBeban, 'id_akun' => $idKas, 'debit' => 0, 'kredit' => $totalBeban],
            ];
            $this->db->table('jurnal_detail')->insertBatch($detailBeban);


            // --- 4. PELUNASAN UTANG USAHA (Simulasi Arus Kas Keluar) ---
            // Bayar utang supplier 500 Miliar
            $bayarUtang = 500000000000;
            $this->db->table('jurnal_header')->insert([
                'tanggal_jurnal' => $tglTransaksi,
                'deskripsi'      => "Pelunasan Utang Supplier - $namaBulan",
                'created_at'     => date('Y-m-d H:i:s')
            ]);
            $idJurnalUtang = $this->db->insertID();
            $detailUtang = [
                ['id_jurnal' => $idJurnalUtang, 'id_akun' => $idUtang, 'debit' => $bayarUtang, 'kredit' => 0],
                ['id_jurnal' => $idJurnalUtang, 'id_akun' => $idKas, 'debit' => 0, 'kredit' => $bayarUtang],
            ];
            $this->db->table('jurnal_detail')->insertBatch($detailUtang);


            // Lanjut ke bulan berikutnya
            $bulan++;
        }
    }
}
