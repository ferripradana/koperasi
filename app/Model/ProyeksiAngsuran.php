<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ProyeksiAngsuran extends Model
{
    //
    protected $table = "proyeksi_angsuran";

    protected $fillable = [
    	'peminjaman_id', 'tanggal_proyeksi', 'cicilan',
    	'bunga_nominal', 'simpanan_wajib', 'status', 'angsuran_id',
        'angsuran_ke'
    ];

    protected $appends = array('tgl_proyeksi' , 'statusview', 'cicilanview', 'bunganominalview');

    public function getCicilanviewAttribute($value)
    {
        return number_format( $this->cicilan ,2,",",".");
    }

    public function getBunganominalviewAttribute($value)
    {
        return number_format( $this->bunga_nominal ,2,",",".");
    }

    public function getStatusviewAttribute($value)
    {
    	$arraystatus = [
    		0 => 'Belum',
    		1 => 'Sudah'
    	];

    	return $arraystatus[$this->status];
    }

    public function getTglProyeksiAttribute()
    {
        return Carbon::parse($this->tanggal_proyeksi)->format('d-m-Y');
    }

   
    public function peminjaman(){
        return $this->belongsTo('App\Model\Peminjaman', 'peminjaman_id');
    }

    public function angsuran(){
        return $this->belongsTo('App\Model\Angsuran', 'angsuran_id');   
    }





}
