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

class ReportAnggota extends Controller
{
	public function index(){
		return view('admin.reportanggota.export');	
	}

	public function post(Request $request){

		$q = 'select d.name as departemen,  u.name as unit, j.nama_jabatan , a.nik, a.nama , a.alamat, a.phone
			  from departments d 
			  join units u  on (u.department_id = d.id)
			  join anggota a on (a.unit_kerja = u.id)
			  join jabatan j on (a.`jabatan` = j.id) 
			  where a.status = 1
			  order by d.name, u.name, a.nik, a.nama
			  ';
		$aktif = \DB::select($q);

		$q = 'select d.name as departemen,  u.name as unit, j.nama_jabatan , a.nik, a.nama , a.alamat, a.phone
			  from departments d 
			  join units u  on (u.department_id = d.id)
			  join anggota a on (a.unit_kerja = u.id)
			  join jabatan j on (a.`jabatan` = j.id) 
			  where a.status = 2
			  order by d.name, u.name, a.nik, a.nama
			  ';
		$non_aktif = \DB::select($q);

		

		if ($request->type == 'html') {
    			return view('admin.pdf.reportanggota',compact('aktif', 'non_aktif'));
    	}

    	$handler = 'export' . ucfirst($request->get('type'));

        return $this->$handler($aktif, $non_aktif);	  
	}

	private function exportPdf($aktif, $non_aktif)
    {
       $pdf = PDF::loadview('admin.pdf.reportanggota', compact('aktif', 'non_aktif'))->setPaper('A3', 'landscape');

       return $pdf->download('reportanggota'.date('Y-m-d H:i:s').'.pdf');
    }

    
}
