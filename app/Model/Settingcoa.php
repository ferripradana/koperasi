<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Settingcoa extends Model
{
    //
    protected $table = 'settingcoa';

     protected $fillable = [
        'transaksi', 'id_coa'
     ];
}
