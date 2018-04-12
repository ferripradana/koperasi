<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Transaksi extends Model
{
    //
    protected $table = "transaksi";
    protected $fillable = [
    	'no_transaksi', 'id_jenis_transaksi', 'type',
    	'nominal', 'keterangan', 'created_by', 'tanggal', 'jurnal_id',
    	'edited_by'
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

}
