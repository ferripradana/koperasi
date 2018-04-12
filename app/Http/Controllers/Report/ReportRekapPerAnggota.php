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

class ReportRekapPerAnggota extends Controller
{
    
    public function index(){
    	return view('admin.reportrekapperanggota.export');
    }

    public function post(Request $request){
		$this->validate($request, [
            'id_anggota' => 'required|numeric',
            'id_pinjaman' => 'required|numeric',
            'type' => 'required|in:pdf,xls,html',
        ], []);

        $id_anggota = $request->id_anggota;
        $id_pinjaman = $request->id_pinjaman;
        $type = $request->type;    

        $anggota = \App\Model\Anggota::find($id_anggota);

        $q ='select an.angsuran_ke, an.tanggal_transaksi, pe.nominal, an.pokok, an.bunga, an.denda
        	 from angsuran an 
        	 join peminjaman pe on (pe.id = an.id_pinjaman)
        	 where pe.id = '.$id_pinjaman;

        $rekap = \DB::select($q);
        $peminjaman = \App\Model\Peminjaman::find($id_pinjaman);

        if ($request->type == 'html') {
    			return view('admin.pdf.reportrekapperanggota',compact('rekap', 'anggota', 'peminjaman'));
    	}

    	$handler = 'export' . ucfirst($request->get('type'));

        return $this->$handler($rekap, $anggota, $peminjaman);	  

    }

    private function exportPdf($rekap, $anggota, $peminjaman)
    {
       $pdf = PDF::loadview('admin.pdf.reportrekapperanggota', compact('rekap','anggota', 'peminjaman'))->setPaper('A3', 'landscape');

       return $pdf->download('reportrekapperanggota'.date('Y-m-d H:i:s').'.pdf');
    }
}
