<?php

namespace App\Model\Acc;

use Illuminate\Database\Eloquent\Model;

class JournalDetail extends Model
{
    //
    protected $table = 'jurnal_detail';
     protected $fillable = [
        'jurnal_header_id', 'coa_id', 'amount','dc'
     ];

    public function jurnalheader(){
    	return $this->belongsTo('App\Model\Acc\JournalHeader', 'jurnal_header_id');
    } 
    public function coa(){
    	return $this->belongsTo('App\Model\Acc\Coa', 'coa_id');
    } 



}
