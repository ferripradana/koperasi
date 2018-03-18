<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class KeteranganPinjaman extends Model
{
    //
     protected $table = 'keterangan_pinjaman';
     protected $fillable = [
        'guna_pinjaman'
     ];
}
