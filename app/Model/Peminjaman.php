<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Peminjaman extends Model
{
    //

    protected $table = 'peminjaman';

    protected $fillable = [
    	'no_transaksi', 'id_anggota', 'id_keterangan_pinjaman',
    	'nominal' , 'tanggal_pengajuan' , 'tanggal_disetujui',
    	'tenor' , 'bunga_persen' , 'cicilan', 'bunga_nominal',
    	'status', 'approve_by', 'created_by', 'edited_by',
    	'deskripsi', 'jurnal_id', 'dana_resiko_credit', 'nominal_diterima'
    ];

    protected $appends = array('nominalview', 'tanggal_pengajuan_original' , 'statusview');

    public function setTanggalPengajuanAttribute($value)
    {
        $this->attributes['tanggal_pengajuan'] = Carbon::createFromFormat('d-m-Y', $value)->toDateString();
    }

    public function getTanggalPengajuanAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y');
    }

    public function getTanggalPengajuanOriginalAttribute($value)
    {
        return Carbon::createFromFormat('d-m-Y', $this->tanggal_pengajuan)->toDateString();
    }

    public function getNominalviewAttribute($value)
    {
        return number_format( $this->nominal ,2,",",".");
    }

    public function getStatusviewAttribute($value)
    {
    	$arraystatus = [
    		0 => 'Menunggu Persetujuan',
    		1 => 'Disetujui',
    		2 => 'Lunas',
    	];

    	return $arraystatus[$this->status];
    }

    public function anggota(){
    	return $this->belongsTo('App\Model\Anggota', 'id_anggota');
    }

    public function keteranganpinjaman(){
    	return $this->belongsTo('App\Model\KeteranganPinjaman', 'id_keterangan_pinjaman');
    }


}
