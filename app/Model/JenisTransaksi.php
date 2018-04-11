<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class JenisTransaksi extends Model
{
    //
    protected $table = 'jenis_transaksi';
    protected $fillable = [
        'nama_transaksi', 'type',
        'debit_coa', 'credit_coa'
    ];

}
