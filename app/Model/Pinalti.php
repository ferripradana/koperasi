<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pinalti extends Model
{
    //
    protected $table = 'pinalti';

    protected $fillable = [
    	'no_transaksi', 'id_peminjaman', 
    	'id_anggota', 'nominal', 'angsuran_nominal', 'tanggal', 'banyak_angsuran',
    	'keterangan', 'status', 'created_by', 'tanggal_validasi', 'approve_by'
    ];

    protected $appends = ['statusview', 'tanggal_original', 'tanggal_validasi_original'];

    public function setTanggalAttribute($value){
    	$this->attributes['tanggal'] = Carbon::createFromFormat('d-m-Y', $value)->toDateString();
    }

    public function getTanggalAttribute($value) {
        return Carbon::parse($value)->format('d-m-Y');
    }

    public function peminjaman(){
    	return $this->belongsTo('App\Model\Peminjaman', 'id_peminjaman');
    }

    public function anggota(){
        return $this->belongsTo('App\Model\Anggota', 'id_anggota');
    }

    public function setTanggalValidasiAttribute($value) {
        $this->attributes['tanggal_validasi'] = Carbon::createFromFormat('d-m-Y', $value)->toDateString();
    }

    public function getTanggalValidasiAttribute($value) {
        return Carbon::parse($value)->format('d-m-Y');
    }

    public function getTanggalValidasiOriginalAttribute($value) {
        return Carbon::createFromFormat('d-m-Y', $this->tanggal_validasi)->toDateString();
    }

    public function getTanggalOriginalAttribute($value) {
        return Carbon::createFromFormat('d-m-Y', $this->tanggal)->toDateString();
    }


    public function getStatusviewAttribute($value) {
        $arraystatus = [
            0 => 'To Validate',
            1 => 'Valid',
        ];

        return $arraystatus[$this->status];
    }

}
