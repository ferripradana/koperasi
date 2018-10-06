<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TransaksiUnit extends Model
{
    //id_unit, id_supplier, nama_bank, no_ref
    protected $table = "transaksi_unit";
    protected $fillable = [
    	'no_transaksi', 'id_jenis_transaksi', 'type',
    	'nominal', 'keterangan', 'created_by', 'tanggal', 'jurnal_id',
    	'edited_by', 'id_unit', 'id_supplier', 'nama_bank', 'no_ref'
    ];

    protected $appends = array('nominalview','tanggal_original');

     public function setTanggalAttribute($value)
    {
        $this->attributes['tanggal'] = Carbon::createFromFormat('d-m-Y', $value)->toDateString();
    }

    public function getTanggalAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y');
    }

    public function getTanggalOriginalAttribute($value)
    {
        return Carbon::createFromFormat('d-m-Y', $this->tanggal)->toDateString();
    }

    public function getNominalviewAttribute($value)
    {
        return number_format( $this->nominal ,2,",",".");
    }

    public function jenistransaksi(){
    	return $this->belongsTo('App\Model\JenisTransaksi', 'id_jenis_transaksi');
    }

    public function unit(){
    	return $this->belongsTo('App\Model\Unit', 'id_unit');
    }

    public function supplier(){
    	return $this->belongsTo('App\Model\Supplier', 'id_supplier');
    }



}
