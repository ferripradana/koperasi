<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


use App\Model\Peminjaman;
use App\Model\KeteranganPinjaman;
use App\Model\Anggota;
use App\Model\Acc\Coa;
use App\Model\Acc\JournalHeader;
use App\Model\Acc\JournalDetail;
use App\Model\Settingcoa;
use App\Model\ProyeksiAngsuran;
use App\Model\Angsuran;
use App\Model\JenisSimpanan;
use App\Model\Simpanan;

use Illuminate\Support\Facades\File;
use App\Utilities\ImportFile;
use PDF;
use Excel;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;

use Session;

class ReportPinjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.reportpeminjaman.export');
    }

    public function post(Request $request){
         $this->validate($request, [
            'id_anggota' => 'required|numeric',
            'id_pinjaman' => 'required|numeric',
            'type' => 'required|in:pdf,xls',
        ], []);

        $id_anggota = $request->id_anggota;
        $id_pinjaman = $request->id_pinjaman;
        $type = $request->type;    

        $proyeksi = ProyeksiAngsuran::with('peminjaman.anggota.unit')->where('peminjaman_id', $id_pinjaman)->get();

         // return response()->json($proyeksi);

        $handler = 'export' . ucfirst($request->get('type'));

        return $this->$handler($proyeksi);

    }


    private function exportXls($proyeksi)
    {
        Excel::create('Data-Proyeksi-Angsuran-Pinjaman', function($excel) use ($proyeksi) {
            // Set property
            $excel->setTitle('Data Proyeksi Angsuran')
                ->setCreator(Auth::user()->name);

            $excel->sheet('Data Proyeksi Angsuran', function($sheet) use ($proyeksi) {
                $row = 1;
                $sheet->row($row,[
                    'Tanggal Pengajuan',
                    $proyeksi[0]->peminjaman->tanggal_pengajuan
                 ]
                );
                $sheet->row($row,[
                    'No. Transaksi Peminjaman',
                    $proyeksi[0]->peminjaman->no_transaksi
                 ]
                );
                $row++;
                $sheet->row(++$row,[
                    'Nama',
                    $proyeksi[0]->peminjaman->anggota->nama
                 ]
                );
                 $sheet->row(++$row,[
                    'Alamat',
                    $proyeksi[0]->peminjaman->anggota->alamat
                 ]
                );
                  $sheet->row(++$row,[
                    'NIK',
                    $proyeksi[0]->peminjaman->anggota->nik
                 ]
                );
                 $sheet->row(++$row,[
                    'Plafon',
                    $proyeksi[0]->peminjaman->anggota->jabatane->plafone
                 ]
                );  

                $row = $row+2;

                $sheet->row(++$row, [
                    'Bulan Ke',
                    'Tanggal',
                    'Pokok Pinjaman',
                    'Pokok Angsuran',
                    'Bunga',
                    'Angsuran Per Bulan',
                    'Saldo Pokok Pinjaman'
                ]);
                $i = 0;
                $total = 0;
                foreach ($proyeksi as $p) {
                    if ($i==0) {
                        $total = $p->peminjaman->nominal;
                    }
                    $sheet->row(++$row, [
                        $p->angsuran_ke,
                        $p->tgl_proyeksi,
                        number_format($total, 0, ".", ","),
                        number_format($p->cicilan,0,'.',',' ),
                        number_format($p->bunga_nominal, 0, '.',',') ,
                        number_format($p->cicilan + $p->bunga_nominal, 0, '.',','),
                        number_format(($total-($p->cicilan)),0, '.', ',') ,
                    ]);
                    $total -= $p->cicilan ;
                    $i++;
                }
            });
        })->export('xls');
    }

    private function exportPdf($proyeksi)
    {
        //return view('admin.pdf.reportpeminjaman',compact('proyeksi') );
        $pdf = PDF::loadview('admin.pdf.reportpeminjaman', compact('proyeksi'))->setPaper('A4', 'potrait');

       return $pdf->download('reportpeminjaman'.date('Y-m-d H:i:s').'.pdf');
    }

    public function viewpeminjaman(Request $request){
        if($request->ajax()){
            $id_anggota = $request->id_anggota;
            $peminjaman = Peminjaman::where('id_anggota', $id_anggota)  
                                    ->get();

            return response()->json($peminjaman);
        }
    }
   
}
