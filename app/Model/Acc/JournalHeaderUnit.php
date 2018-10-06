<?php

namespace App\Model\Acc;

use Illuminate\Database\Eloquent\Model;

class JournalHeaderUnit extends Model
{
    //
    protected $table = 'jurnal_header_unit';

    protected $fillable = [
        'entry_type', 'jurnal_number', 'tanggal',
        'debit_total', 'credit_total', 'narasi', 'id_unit'
    ];


    public function jurnaldetail (){
    	return $this->hasMany('App\Model\Acc\JournalDetailUnit', 'jurnal_header_id', 'id');
    }

    public function unit(){
		return $this->hasMany('App\Model\Unit', 'id_unit');    	
    }

}
