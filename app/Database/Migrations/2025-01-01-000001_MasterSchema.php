<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MasterSchema extends Migration
{
    public function up()
    {
        // 1. CHART OF ACCOUNTS
        $this->forge->addField([
            'id_akun' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'kode_akun' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
            ],
            'nama_akun' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'kategori' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'default'    => 'Umum',
            ],
            'saldo_awal' => [
                'type'       => 'DECIMAL',
                'constraint' => '20,2',
                'default'    => 0,
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id_akun', true);
        $this->forge->createTable('chart_of_accounts', true);

        // 2. JURNAL HEADER
        $this->forge->addField([
            'id_jurnal' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'tanggal_jurnal' => ['type' => 'DATE'],
            'deskripsi'      => ['type' => 'TEXT'],
            'created_at'     => ['type' => 'DATETIME', 'null' => true],
            'updated_at'     => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id_jurnal', true);
        $this->forge->createTable('jurnal_header', true);

        // 3. JURNAL DETAIL
        $this->forge->addField([
            'id_jurnal_detail' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_jurnal' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'id_akun' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'debit' => [
                'type'       => 'DECIMAL',
                'constraint' => '20,2',
                'default'    => 0,
            ],
            'kredit' => [
                'type'       => 'DECIMAL',
                'constraint' => '20,2',
                'default'    => 0,
            ],
        ]);
        $this->forge->addKey('id_jurnal_detail', true);
        $this->forge->createTable('jurnal_detail', true);

        // 4. FIXED ASSETS
        $this->forge->addField([
            'id_aset' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama_aset'         => ['type' => 'VARCHAR', 'constraint' => 255],
            'tanggal_beli'      => ['type' => 'DATE'],
            'harga_perolehan'   => ['type' => 'DECIMAL', 'constraint' => '20,2'],
            'nilai_sisa'        => ['type' => 'DECIMAL', 'constraint' => '20,2', 'default' => 0],
            'umur_ekonomis'     => ['type' => 'INT', 'constraint' => 11],
            'metode'            => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'Garis Lurus'],
            'akun_aset_id'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'akun_akumulasi_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'akun_beban_id'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'created_at'        => ['type' => 'DATETIME', 'null' => true],
            'updated_at'        => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id_aset', true);
        $this->forge->createTable('fixed_assets', true);
    }

    public function down()
    {
        $this->forge->dropTable('fixed_assets', true);
        $this->forge->dropTable('jurnal_detail', true);
        $this->forge->dropTable('jurnal_header', true);
        $this->forge->dropTable('chart_of_accounts', true);
    }
}
