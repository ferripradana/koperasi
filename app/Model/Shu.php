<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Shu extends Model
{
    //
    protected $table = 'shu';
    protected $fillable = [
    	'id_anggota', 'bulan', 'tahun', 'tiga_puluh', 'tidak_diambil', 'diambil', 'tujuh_puluh'
    ];

}
