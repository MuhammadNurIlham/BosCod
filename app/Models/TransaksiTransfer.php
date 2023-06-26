<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiTransfer extends Model
{
    use HasFactory;

    protected $table = 'transaksi_transfers';

    protected $fillable = [
        'id_penerima',
        'id_penerima',
            'id_transaksi',
            'nilai_tansfer',
            'bank_tujuan',
            'rekening_tujuan',
            'atas_nama_tujuan',
            'bank_pengirim',
            'kode_unik',
            'biaya_admin',
            'total_transfers',
            'bank_perantara',
            'rekening_perantara',
            'berlaku_hingga'

    ];

    public function pengirim()
    {
        return $this->belongsTo(User::class, 'id_pengirim');
    }

    public function penerima()
    {
        return $this->belongsTo(User::class, 'id_penerima');
    }
}
