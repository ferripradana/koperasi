<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class JenisSimpanan extends Model
{
    //
     protected $table = 'jenis_simpanan';
     protected $fillable = [
        'nama_simpanan', 'nominal_minimum'
     ];
     protected $appends = array('minimum');


    public function getMinimumAttribute($value)
    {
        return number_format( $this->nominal_minimum ,2,",",".");
    }
}
