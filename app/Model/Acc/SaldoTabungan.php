<?php

namespace App\Model\Acc;

use Illuminate\Database\Eloquent\Model;

class SaldoTabungan extends Model
{
    //
    protected $table = 'saldo_tabungan';
    protected $fillable = [
        'id_anggota', 'jenis_simpanan', 'nominal'
    ];

}
