<?php

namespace App\Models;

use CodeIgniter\Model;

class FixedAssetModel extends Model
{
    protected $table            = 'fixed_assets'; // Nama tabel di database
    protected $primaryKey       = 'id_aset'; // Pastikan primary key-nya 'id' (sesuai migrasi)

    // Kolom apa saja yang boleh diisi (Mass Assignment)
    protected $allowedFields    = [
        'nama_aset',
        'tanggal_beli',
        'harga_perolehan',
        'nilai_sisa',
        'umur_ekonomis',
        'metode',
        'akun_aset_id',
        'akun_akumulasi_id',
        'akun_beban_id',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true; // Agar created_at & updated_at otomatis terisi
}
