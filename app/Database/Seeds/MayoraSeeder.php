<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MayoraSeeder extends Seeder
{
    public function run()
    {
        // 1. RESET DATABASE (BERSIHKAN TOTAL)
        $this->db->disableForeignKeyChecks();
        $this->db->query("TRUNCATE TABLE jurnal_detail");
        $this->db->query("TRUNCATE TABLE jurnal_header");
        $this->db->query("TRUNCATE TABLE fixed_assets");
        $this->db->query("TRUNCATE TABLE chart_of_accounts");
        $this->db->query("ALTER TABLE chart_of_accounts AUTO_INCREMENT = 1");
        $this->db->enableForeignKeyChecks();

        // 2. DAFTAR AKUN (TANPA SALDO LABA!)
        // PENTING: Saldo Laba (3-3000) JANGAN dimasukkan disini. Biar script yang hitung.
        $dataAkun = [
            // --- ASET (DEBIT) ---
            ['kode_akun' => '1-1100', 'nama_akun' => 'Kas dan Setara Kas', 'kategori' => 'Aset', 'saldo_awal' => 4156738667354],
            ['kode_akun' => '1-1200', 'nama_akun' => 'Piutang Usaha - Pihak Ketiga', 'kategori' => 'Aset', 'saldo_awal' => 250189161535],
            ['kode_akun' => '1-1210', 'nama_akun' => 'Piutang Usaha - Pihak Berelasi', 'kategori' => 'Aset', 'saldo_awal' => 5848243953678],
            ['kode_akun' => '1-1300', 'nama_akun' => 'Piutang Lain-lain', 'kategori' => 'Aset', 'saldo_awal' => 98527776182],
            ['kode_akun' => '1-1400', 'nama_akun' => 'Persediaan', 'kategori' => 'Aset', 'saldo_awal' => 3556864426525],
            ['kode_akun' => '1-1500', 'nama_akun' => 'Uang Muka Pembelian', 'kategori' => 'Aset', 'saldo_awal' => 314374995704],
            ['kode_akun' => '1-1600', 'nama_akun' => 'Pajak Dibayar Dimuka', 'kategori' => 'Aset', 'saldo_awal' => 482036426350],
            ['kode_akun' => '1-1700', 'nama_akun' => 'Biaya Dibayar Dimuka', 'kategori' => 'Aset', 'saldo_awal' => 31946980201],
            ['kode_akun' => '1-2100', 'nama_akun' => 'Aset Tetap - Tanah', 'kategori' => 'Aset', 'saldo_awal' => 721016985407],
            ['kode_akun' => '1-2200', 'nama_akun' => 'Aset Tetap - Bangunan & Prasarana', 'kategori' => 'Aset', 'saldo_awal' => 2612351676071],
            ['kode_akun' => '1-2290', 'nama_akun' => 'Akumulasi Penyusutan Bangunan', 'kategori' => 'Aset', 'saldo_awal' => -980584548233], // Minus
            ['kode_akun' => '1-2300', 'nama_akun' => 'Aset Tetap - Mesin & Peralatan', 'kategori' => 'Aset', 'saldo_awal' => 9525457223266],
            ['kode_akun' => '1-2390', 'nama_akun' => 'Akumulasi Penyusutan Mesin', 'kategori' => 'Aset', 'saldo_awal' => -6610572016517], // Minus
            ['kode_akun' => '1-2400', 'nama_akun' => 'Aset Tetap - Kendaraan & Inv', 'kategori' => 'Aset', 'saldo_awal' => 529041785862],
            ['kode_akun' => '1-2490', 'nama_akun' => 'Akumulasi Penyusutan Kendaraan & Inv', 'kategori' => 'Aset', 'saldo_awal' => -452154768621], // Minus
            ['kode_akun' => '1-2450', 'nama_akun' => 'Aset Dalam Penyelesaian', 'kategori' => 'Aset', 'saldo_awal' => 2815285457302],
            ['kode_akun' => '1-2500', 'nama_akun' => 'Aset Hak Guna (Sewa)', 'kategori' => 'Aset', 'saldo_awal' => 4626540933],
            ['kode_akun' => '1-2600', 'nama_akun' => 'Aset Pajak Tangguhan', 'kategori' => 'Aset', 'saldo_awal' => 51556446752],
            ['kode_akun' => '1-2700', 'nama_akun' => 'Uang Muka Aset Tetap', 'kategori' => 'Aset', 'saldo_awal' => 872161016043],
            ['kode_akun' => '1-2800', 'nama_akun' => 'Uang Jaminan', 'kategori' => 'Aset', 'saldo_awal' => 43296776678],

            // --- LIABILITAS (KREDIT) ---
            ['kode_akun' => '2-1100', 'nama_akun' => 'Utang Bank Jangka Pendek', 'kategori' => 'Liabilitas', 'saldo_awal' => 85000000000],
            ['kode_akun' => '2-1200', 'nama_akun' => 'Utang Usaha - Pihak Ketiga', 'kategori' => 'Liabilitas', 'saldo_awal' => 1785245057218],
            ['kode_akun' => '2-1210', 'nama_akun' => 'Utang Usaha - Pihak Berelasi', 'kategori' => 'Liabilitas', 'saldo_awal' => 109415027985],
            ['kode_akun' => '2-1300', 'nama_akun' => 'Utang Lain-lain', 'kategori' => 'Liabilitas', 'saldo_awal' => 74569565357],
            ['kode_akun' => '2-1400', 'nama_akun' => 'Uang Muka Penjualan', 'kategori' => 'Liabilitas', 'saldo_awal' => 93681689279],
            ['kode_akun' => '2-1500', 'nama_akun' => 'Utang Pajak', 'kategori' => 'Liabilitas', 'saldo_awal' => 465942717971],
            ['kode_akun' => '2-1600', 'nama_akun' => 'Beban Akrual', 'kategori' => 'Liabilitas', 'saldo_awal' => 750268983125],
            ['kode_akun' => '2-1700', 'nama_akun' => 'Utang Jangka Panjang Jatuh Tempo 1 Thn', 'kategori' => 'Liabilitas', 'saldo_awal' => 649077460479],
            ['kode_akun' => '2-2100', 'nama_akun' => 'Utang Bank Jangka Panjang', 'kategori' => 'Liabilitas', 'saldo_awal' => 1703883498124],
            ['kode_akun' => '2-2200', 'nama_akun' => 'Utang Obligasi', 'kategori' => 'Liabilitas', 'saldo_awal' => 1829449117872],
            ['kode_akun' => '2-2300', 'nama_akun' => 'Liabilitas Imbalan Kerja', 'kategori' => 'Liabilitas', 'saldo_awal' => 1011417406765],
            ['kode_akun' => '2-2500', 'nama_akun' => 'Liabilitas Pajak Tangguhan', 'kategori' => 'Liabilitas', 'saldo_awal' => 30365251561],

            // --- EKUITAS (KREDIT) ---
            ['kode_akun' => '3-1000', 'nama_akun' => 'Modal Saham', 'kategori' => 'Ekuitas', 'saldo_awal' => 447173994500],
            ['kode_akun' => '3-2000', 'nama_akun' => 'Tambahan Modal Disetor', 'kategori' => 'Ekuitas', 'saldo_awal' => 330005500],
            ['kode_akun' => '3-4000', 'nama_akun' => 'Selisih Kurs Penjabaran', 'kategori' => 'Ekuitas', 'saldo_awal' => 13054200471],
            ['kode_akun' => '3-5000', 'nama_akun' => 'Kepentingan Non-Pengendali', 'kategori' => 'Ekuitas', 'saldo_awal' => 242601575073],
            // NOTE: Saldo Laba dihapus dari sini agar tidak bentrok.

            // --- PENDAPATAN & BEBAN (NOL) ---
            ['kode_akun' => '4-1000', 'nama_akun' => 'Penjualan Bersih', 'kategori' => 'Pendapatan', 'saldo_awal' => 0],
            ['kode_akun' => '4-2000', 'nama_akun' => 'Pendapatan Keuangan', 'kategori' => 'Pendapatan', 'saldo_awal' => 0],
            ['kode_akun' => '4-3000', 'nama_akun' => 'Keuntungan Kurs Mata Uang', 'kategori' => 'Pendapatan', 'saldo_awal' => 0],
            ['kode_akun' => '5-1000', 'nama_akun' => 'Beban Pokok Penjualan (COGS)', 'kategori' => 'Beban', 'saldo_awal' => 0],
            ['kode_akun' => '6-1000', 'nama_akun' => 'Beban Penjualan', 'kategori' => 'Beban', 'saldo_awal' => 0],
            ['kode_akun' => '6-2000', 'nama_akun' => 'Beban Umum dan Administrasi', 'kategori' => 'Beban', 'saldo_awal' => 0],
            ['kode_akun' => '6-2100', 'nama_akun' => 'Beban Gaji & Tunjangan', 'kategori' => 'Beban', 'saldo_awal' => 0],
            ['kode_akun' => '6-2200', 'nama_akun' => 'Beban Penyusutan Aset Tetap', 'kategori' => 'Beban', 'saldo_awal' => 0],
            ['kode_akun' => '6-3000', 'nama_akun' => 'Beban Bunga & Keuangan', 'kategori' => 'Beban', 'saldo_awal' => 0],
        ];

        // 3. HITUNG PENYEIMBANG (AUTO-BALANCE LOGIC)
        $totalAset = 0;
        $totalPasivaLain = 0;

        foreach ($dataAkun as $akun) {
            if ($akun['kategori'] == 'Aset') {
                // Tambahkan Aset (Akumulasi bernilai negatif akan otomatis mengurangi total aset)
                $totalAset += $akun['saldo_awal'];
            } else {
                // Tambahkan Liabilitas & Ekuitas yang ada
                $totalPasivaLain += $akun['saldo_awal'];
            }
        }

        // RUMUS: Total Aset = Total Pasiva (Liabilitas + Ekuitas + Saldo Laba)
        // Maka: Saldo Laba = Total Aset - (Liabilitas + Ekuitas Lain)
        $saldoLaba = $totalAset - $totalPasivaLain;

        // Cetak di Terminal untuk memastikan (Debug)
        echo "\n--- AUTO BALANCE CALCULATION ---\n";
        echo "Total Aset       : " . number_format($totalAset, 0, ',', '.') . "\n";
        echo "Total Pasiva Lain: " . number_format($totalPasivaLain, 0, ',', '.') . "\n";
        echo "PLUG (Saldo Laba): " . number_format($saldoLaba, 0, ',', '.') . "\n";
        echo "--------------------------------\n";

        // Tambahkan Akun Saldo Laba HASIL HITUNGAN ke Array
        $dataAkun[] = [
            'kode_akun' => '3-3000',
            'nama_akun' => 'Saldo Laba (Retained Earnings)',
            'kategori'  => 'Ekuitas',
            'saldo_awal' => $saldoLaba
        ];

        // 4. INSERT DATA
        $this->db->table('chart_of_accounts')->insertBatch($dataAkun);

        // 5. INJECT ASET (Perlu ID Akun)
        $getAkunId = function ($kode) {
            $query = $this->db->table('chart_of_accounts')->where('kode_akun', $kode)->get()->getRow();
            return $query ? $query->id_akun : 0;
        };

        $dataAset = [
            [
                'nama_aset' => 'Tanah - Berbagai Lokasi',
                'tanggal_beli' => '2010-01-01',
                'harga_perolehan' => 721016985407,
                'nilai_sisa' => 721016985407,
                'umur_ekonomis' => 0,
                'metode' => 'Tidak Disusutkan',
                'akun_aset_id' => $getAkunId('1-2100'),
                'akun_akumulasi_id' => 0,
                'akun_beban_id' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'nama_aset' => 'Bangunan Pabrik (Konsolidasi)',
                'tanggal_beli' => '2015-01-01',
                'harga_perolehan' => 2612351676071,
                'nilai_sisa' => 0,
                'umur_ekonomis' => 240,
                'metode' => 'Garis Lurus',
                'akun_aset_id' => $getAkunId('1-2200'),
                'akun_akumulasi_id' => $getAkunId('1-2290'),
                'akun_beban_id' => $getAkunId('6-2200'),
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'nama_aset' => 'Mesin Produksi (Konsolidasi)',
                'tanggal_beli' => '2018-01-01',
                'harga_perolehan' => 9525457223266,
                'nilai_sisa' => 1000000000,
                'umur_ekonomis' => 120,
                'metode' => 'Garis Lurus',
                'akun_aset_id' => $getAkunId('1-2300'),
                'akun_akumulasi_id' => $getAkunId('1-2390'),
                'akun_beban_id' => $getAkunId('6-2200'),
                'created_at' => date('Y-m-d H:i:s')
            ]
        ];

        $this->db->table('fixed_assets')->insertBatch($dataAset);
    }
}
