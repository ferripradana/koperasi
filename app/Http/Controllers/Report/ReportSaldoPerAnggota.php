<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


use Illuminate\Support\Facades\File;
use App\Utilities\ImportFile;
use PDF;
use Excel;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;

class ReportSaldoPerAnggota extends Controller
{
    //
    public function index(){
    	return view('admin.reportsaldoperanggota.export');
    }

    public function post(Request $request){
    	$q = 'select d.name as departemen, u.name as unit,  a.nia, a.nik , a.nama, j.nama_jabatan,
    		  ifnull(x.nominal,0) as pinjaman,
    		  ifnull(y.pokok,0) as angsuran,
    		  (ifnull(x.nominal,0) - ifnull(y.pokok,0)) as saldo 
    		  from departments d 
    		  join units u  on (u.department_id = d.id)
    		  join anggota a on (a.unit_kerja = u.id)
    		  join jabatan j on (a.`jabatan` = j.id) 
    		  left join (
    		  	select sum(pe.nominal) as nominal , pe.id_anggota
    		  	from peminjaman pe 
    		  	where pe.status = 1
    		  	group by pe.id_anggota
    		  ) x on (a.id = x.id_anggota)
    		  left join (
    		  	select sum(an.pokok) as pokok , an.id_anggota
    		  	from angsuran an 
    		  	join peminjaman pe on (pe.id = an.id_pinjaman)
    		  	where (an.status = 1 or an.status = 2) and pe.status = 1
    		  	group by an.id_anggota
    		  ) y on (a.id = y.id_anggota)
    		  order by d.id , u.id, a.nik , a.nama	 
    		  '
    		 ;

    	
    	$saldo = \DB::select($q);

    	if ($request->type == 'html') {
    			return view('admin.pdf.reportsaldoperanggota',compact('saldo'));
    	}

    	$handler = 'export' . ucfirst($request->get('type'));

        return $this->$handler($saldo);	  
   		
    }

    private function exportPdf($saldo)
    {
       $pdf = PDF::loadview('admin.pdf.reportsaldoperanggota', compact('saldo'))->setPaper('A3', 'landscape');

       return $pdf->download('reportsaldoperanggota'.date('Y-m-d H:i:s').'.pdf');
    }
}
