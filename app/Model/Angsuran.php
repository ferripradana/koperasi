<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Angsuran extends Model
{
    protected $table = "angsuran";

    protected $fillable = [
    	'no_transaksi', 'tanggal_transaksi', 'id_pinjaman',
   		'id_anggota', 'pokok', 'bunga', 'simpanan_wajib',
   		'denda' , 'status', 'created_by', 'approve_by', 'angsuran_ke', 'total', 'id_proyeksi'
    ];

    public function setTanggalTransaksiAttribute($value)
    {
        $this->attributes['tanggal_transaksi'] = Carbon::createFromFormat('d-m-Y', $value)->toDateString();
    }

    public function getTanggalTransaksiAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y');
    }

    public function peminjaman(){
    	return $this->belongsTo('App\Model\Peminjaman', 'id_pinjaman');
    }

     public function anggota(){
        return $this->belongsTo('App\Model\Anggota', 'id_anggota');
    }

    public function proyeksiangsuran(){
        return $this->belongsTo('App\Model\ProyeksiAngsuran', 'id_proyeksi');
    }




}
