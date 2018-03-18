<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Anggota extends Model
{
    //
    protected $table = 'anggota';

    protected $fillable = [
        'nama', 'nia', 'nik', 'no_ktp', 'tempat_lahir',
        'tanggal_lahir', 'alamat', 'kecamatan', 'kabupaten', 'unit_kerja',
        'tahun_masuk', 'pengampu', 'nama_bank', 'nomor_rekening', 'foto', 'phone', 'jabatan',
        'status'
    ];


     public function setTanggalLahirAttribute($value)
    {
        $this->attributes['tanggal_lahir'] = Carbon::createFromFormat('d-m-Y', $value)->toDateString();
    }

    public function getTanggalLahirAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y');
    }

    public function unit()
    {
        return $this->belongsTo('App\Model\Unit', 'unit_kerja');
    }


    public function pembawa(){
    	 return $this->belongsTo('App\Model\Anggota', 'pengampu');
    }
}
