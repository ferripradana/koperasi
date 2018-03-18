<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    //
     protected $table = 'jabatan';
     protected $fillable = [
        'nama_jabatan', 'plafon'
     ];
     protected $appends = array('plafone');


    public function getPlafoneAttribute($value)
    {
        return number_format( $this->plafon ,2,",",".");
    }


    

}


