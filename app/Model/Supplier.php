<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Supplier extends Model
{
    //
    protected $table = 'supplier';
    protected $fillable = [
    	'kode_supplier', 'nama', 'alamat', 
    	'id_bank', 'nama_rekening', 'no_rekening',
    	'phone', 'status', 'status_beli'
    ];

    public function bank(){
    	 return $this->belongsTo('App\Model\Bank', 'id_bank');
    }
}
