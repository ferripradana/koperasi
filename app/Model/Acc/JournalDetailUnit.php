<?php

namespace App\Model\Acc;

use Illuminate\Database\Eloquent\Model;

class JournalDetailUnit extends Model
{
    //
     protected $table = 'jurnal_detail_unit';
     protected $fillable = [
        'jurnal_header_id', 'coa_id', 'amount','dc'
     ];

     public function jurnalheader(){
    	return $this->belongsTo('App\Model\Acc\JournalHeaderUnit', 'jurnal_header_id');
    } 
    public function coa(){
    	return $this->belongsTo('App\Model\Acc\Coa', 'coa_id');
    } 


}
