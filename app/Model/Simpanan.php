<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Simpanan extends Model
{
    //
    protected $table = 'simpanan';

    protected $fillable = [
        'no_transaksi','id_anggota','id_simpanan',
        'nominal', 'keterangan', 'created_by', 'edited_by',
        'tanggal_transaksi'
     ];
     protected $appends = array('nominalview');

    public function setTanggalTransaksiAttribute($value)
    {
        $this->attributes['tanggal_transaksi'] = Carbon::createFromFormat('d-m-Y', $value)->toDateString();
    }

    public function getTanggalTransaksiAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y');
    }



    public function getNominalviewAttribute($value)
    {
        return number_format( $this->nominal ,2,",",".");
    }

    public function anggota(){
    	return $this->belongsTo('App\Model\Anggota', 'id_anggota');
    }

    public function jenissimpanan(){
    	return $this->belongsTo('App\Model\JenisSimpanan', 'id_simpanan');
    }

    





}
