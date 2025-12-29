<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyDecimalLength extends Migration
{
    public function up()
    {
        // 1. PERBESAR KOLOM DI TABEL AKUN (COA)
        // Dari 15,2 menjadi 20,2 (Biar muat angka Triliunan)
        $this->forge->modifyColumn('chart_of_accounts', [
            'saldo_awal' => [
                'name'       => 'saldo_awal',
                'type'       => 'DECIMAL',
                'constraint' => '20,2',
                'default'    => 0.00,
            ],
        ]);

        // 2. PERBESAR KOLOM DI TABEL JURNAL DETAIL JUGA
        // Supaya nanti kalau input transaksi triliunan tidak error juga
        $this->forge->modifyColumn('jurnal_detail', [
            'debit' => [
                'name'       => 'debit',
                'type'       => 'DECIMAL',
                'constraint' => '20,2',
                'default'    => 0.00,
            ],
            'kredit' => [
                'name'       => 'kredit',
                'type'       => 'DECIMAL',
                'constraint' => '20,2',
                'default'    => 0.00,
            ],
        ]);
    }

    public function down()
    {
        // Kembalikan ke ukuran semula (15,2) jika di-rollback
        $this->forge->modifyColumn('chart_of_accounts', [
            'saldo_awal' => [
                'name'       => 'saldo_awal',
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0.00,
            ],
        ]);

        $this->forge->modifyColumn('jurnal_detail', [
            'debit' => [
                'name'       => 'debit',
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0.00,
            ],
            'kredit' => [
                'name'       => 'kredit',
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0.00,
            ],
        ]);
    }
}
