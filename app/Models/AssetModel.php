<?php

namespace App\Models;

use CodeIgniter\Model;

class AssetModel extends Model
{
    protected $table            = 'fixed_assets';
    protected $primaryKey       = 'id_aset';
    protected $allowedFields    = [
        'nama_aset',
        'tanggal_beli',
        'harga_perolehan',
        'nilai_sisa',
        'umur_ekonomis',
        'metode',
        'akun_aset_id',
        'akun_akumulasi_id',
        'akun_beban_id'
    ];
    protected $useTimestamps    = true;
}
