<?php

namespace App\Http\Controllers\Transaction;

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

use App\Helpers\Common;

use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\File;
use App\Utilities\ImportFile;
use Excel;
use Illuminate\Support\Facades\Input;

class AngsuranController extends Controller
{
    protected $helper;

    public function __construct(Common $helper){
        $this->helper = $helper;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Builder $htmlBuilder)
    {
        //
        //
        if ($request->ajax()) {
           $angsurans = Angsuran::with('peminjaman', 'anggota', 'peminjaman.keteranganpinjaman', 'proyeksiangsuran');
            return Datatables::of($angsurans)
                   ->addColumn('action', function($angsuran){
                        return view('datatable._action',[
                                'model' => $angsuran,
                                'form_url' => route('angsuran.destroy', $angsuran->id),
                                'edit_url' => route('angsuran.edit', $angsuran->id),
                                'show_url' => route('angsuran.show', $angsuran->id),
                                'confirm_message' => 'Yakin mau menghapus ' . $angsuran->no_transaksi . '?'
                            ]);
                   })->make(true);
       }
       $html = $htmlBuilder
            ->addColumn(['data' => 'id', 'name' => 'id', 'title' => 'ID'])
            ->addColumn(['data' => 'no_transaksi', 'name' => 'no_transaksi', 'title' => 'No. Angsuran'])
             ->addColumn(['data' => 'peminjaman.no_transaksi', 'name' => 'peminjaman.no_transaksi', 'title' => 'No. Pinjaman'])
            ->addColumn(['data' => 'anggota.nik', 'name' => 'anggota.nik', 'title' => 'NIK'])
            ->addColumn(['data' => 'anggota.nama', 'name' => 'anggota.nama', 'title' => 'Nama'])
            ->addColumn(['data' => 'tanggal_transaksi', 'name' => 'tanggal_transaksi', 'title' => 'Tanggal Angsuran'])
            ->addColumn(['data' => 'proyeksiangsuran.tanggal_proyeksi', 'name' => 'proyeksiangsuran.tanggal_proyeksi', 'title' => 'Jatuh Tempo'])
            ->addColumn(['data' => 'angsuran_ke', 'name' => 'angsuran_ke', 'title' => 'Angsuran Ke-'])
            ->addColumn(['data' => 'action', 'name' => 'action', 'title' => '', 'orderable' => false, 'searchable' => false])
            ->parameters([
                    'order' => [
                        0, // here is the column number
                        'desc'
                    ]
            ]);

        return view('admin.angsuran.index')->with(compact('html'));   
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.angsuran.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function viewpeminjaman(Request $request){
        if($request->ajax()){
            $id_anggota = $request->id_anggota;
            $peminjaman = Peminjaman::where('id_anggota', $id_anggota)
                                    ->where('status',1)    
                                    ->get();

            return response()->json($peminjaman);
        }
    }

    public function viewproyeksi(Request $request){
        if($request->ajax()){
            $id_pinjaman = $request->id_pinjaman;
            $peminjaman  = ProyeksiAngsuran::where('peminjaman_id', $id_pinjaman)
                                    ->where('status',0)
                                    ->where('tanggal_proyeksi', '<', date('Y-m-d'))   
                                    ->get()->toArray();


            $peminjaman2 = ProyeksiAngsuran::where('peminjaman_id', $id_pinjaman)
                                    ->where('status',0)
                                    ->where('tanggal_proyeksi', '>=', date('Y-m-d'))    
                                    ->limit(1)
                                    ->get()->toArray();

            return response()->json(array_merge($peminjaman,$peminjaman2) );
        }
    }

    public function viewdetailproyeksi(Request $request){
        if($request->ajax()){
            $id_proyeksi = $request->id_proyeksi;
            if ($id_proyeksi<1) {
                return response()->json([]);
            }
            $proyeksi  = ProyeksiAngsuran::find($id_proyeksi)->toArray();
            $getProyeksiNextMonth = $this->helper->getNextMonth($proyeksi['tanggal_proyeksi']);
            if (date('Y-m-d')>$getProyeksiNextMonth) {
                $proyeksi['denda'] = 1/100*$proyeksi['cicilan'];
            }else{
                $proyeksi['denda'] = 0;
            }

            return response()->json($proyeksi );
        }
    }


}
