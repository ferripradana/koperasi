<?php

namespace App\Model\Acc;

use Illuminate\Database\Eloquent\Model;

class JournalHeader extends Model
{
    //
    protected $table = 'jurnal_header';
    protected $fillable = [
        'entry_type', 'jurnal_number', 'tanggal',
        'debit_total', 'credit_total', 'narasi'
    ];


    public function jurnaldetail (){
    	return $this->hasMany('App\Model\Acc\JournalDetail', 'jurnal_header_id', 'id');
    }
}
