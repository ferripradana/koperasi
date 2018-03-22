<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class JenisSimpanan extends Model
{
    //
     protected $table = 'jenis_simpanan';
     protected $fillable = [
        'nama_simpanan', 'nominal_minimum',
        'peminjaman_debit_coa', 'peminjaman_credit_coa',
        'pengambilan_debit_coa', 'pengambilan_credit_coa'
     ];
     protected $appends = array('minimum');


    public function getMinimumAttribute($value)
    {
        return number_format( $this->nominal_minimum ,2,",",".");
    }
}
