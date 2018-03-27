<?php

namespace App\Http\Controllers\Transaction;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Model\Simpanan;
use App\Model\Penarikan;
use App\Model\JenisSimpanan;
use App\Model\Anggota;
use App\Model\Acc\Coa;
use App\Model\Acc\JournalHeader;
use App\Model\Acc\JournalDetail;

use App\Helpers\Common;

use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\File;
use App\Utilities\ImportFile;
use Excel;
use Illuminate\Support\Facades\Input;

use Session;

class PenarikanController extends Controller
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
        if ($request->ajax()) {
            $penarikans = Penarikan::with('anggota', 'jenissimpanan')->select('penarikan.*');
            return Datatables::of($penarikans)
                   ->addColumn('action', function($penarikan){
                        return  view('datatable._action',[
                                'model' => $penarikan,
                                'form_url' => route('penarikan.destroy', $penarikan->id),
                                'edit_url' => route('penarikan.edit', $penarikan->id),
                                'show_url' => route('penarikan.show', $penarikan->id),
                                'confirm_message' => 'Yakin mau menghapus '. $penarikan->id . '?'
                            ]);
                   })->make(true);
        }

        $html = $htmlBuilder
                ->addColumn(['data' => 'id', 'name' => 'id', 'title' => 'ID'])
                ->addColumn(['data' => 'no_transaksi', 'name' => 'no_transaksi', 'title' => 'Nomor Transaksi'])
                ->addColumn(['data' => 'anggota.nama', 'name' => 'anggota.nama', 'title' => 'Nama Anggota'])
                ->addColumn(['data' => 'jenissimpanan.nama_simpanan', 'name' => 'jenissimpanan.nama_simpanan', 'title' => 'Jenis Simpanan'])
                ->addColumn(['data' => 'tanggal_transaksi', 'name' => 'tanggal_transaksi', 'title' => 'Tanggal Transaksi'])
                ->addColumn(['data' => 'nominalview', 'name' => 'nominal', 'title' => 'Nominal'])
                ->addColumn(['data' => 'action', 'name' => 'action', 'title' => '', 'orderable' => false, 'searchable' => false])
                ->parameters([
                    'order' => [
                        0, // here is the column number
                        'desc'
                    ]
                ]);


        return view('admin.penarikan.index')->with(compact('html'));   
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.penarikan.create');
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
        $this->validate($request,[
            'no_transaksi' => 'required|unique:penarikan',
            'id_anggota'  => 'required|numeric',
            'id_simpanan' => 'required|numeric',
            'nominal'     => 'required|numeric',
            'tanggal_transaksi' => 'required'
        ],[]);

        $penarikan = Penarikan::create($request->all());
        $penarikan->jurnal_id = $this->insertJournal($penarikan);
        $penarikan->save();

        Session::flash(
            "flash_notification",
            [
                'level' => 'success',
                "icon" => "fa fa-check",
                'message' => 'Berhasil melakukan penarikan '.$penarikan->no_transaksi,
            ]
        );

        return redirect()->route('penarikan.index');
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
        $penarikan = Penarikan::find($id);
        return view('admin.penarikan.show')->with(compact('penarikan'));
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
        $penarikan = Penarikan::find($id);
        return view('admin.penarikan.edit')->with(compact('penarikan'));
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
        $penarikan_old = Penarikan::find($id);
        if (!Penarikan::destroy($id)) {
            return redirect()->back();
        }
        try {
            $delete_header = JournalHeader::where('id', $penarikan_old->jurnal_id)->delete();
            $delete_detail = JournalDetail::where('jurnal_header_id', $penarikan_old->jurnal_id)->delete();   
        } catch (Exception $e) {
            
        }

        Session::flash("flash_notification", [
            "level" => "success",
            "icon" => "fa fa-check",
            "message" => "Transaksi Penarikan berhasil dihapus"
        ]);
        return redirect()->route('penarikan.index');
    }


    public function viewSaldo(Request $request){
         if($request->ajax()){
            $id_anggota = $request->id_anggota;
            $id_simpanan = $request->id_simpanan;
            $simpanan = Simpanan::with('anggota','jenissimpanan')
                                  ->where('simpanan.id_anggota', (int) $id_anggota)
                                  ->where('simpanan.id_simpanan', (int) $id_simpanan )
                                  ->orderBy('simpanan.id_simpanan', 'asc')
                                  ->sum('nominal');
            
            $penarikan = Penarikan::with('anggota','jenissimpanan')
                                  ->where('penarikan.id_anggota', (int) $id_anggota)
                                  ->where('penarikan.id_simpanan', (int) $id_simpanan )
                                  ->orderBy('penarikan.id_simpanan', 'asc')
                                  ->sum('nominal');

            $return = [
                'saldo' => $simpanan - $penarikan
            ];
                                  
            return response()->json($return);
        }
    }


     private function insertJournal(Penarikan $penarikan){
        $jenis_simpanan = JenisSimpanan::find($penarikan->id_simpanan);
        $anggota        = Anggota::find($penarikan->id_anggota);
        try {
            $return = $this->helper->insertJournalHeader(
                0, $penarikan->tanggal_transaksi_original,  $penarikan->nominal ,  $penarikan->nominal, "Penarikan ".$jenis_simpanan->nama_simpanan.' '.$anggota->nama.' '.$penarikan->no_transaksi
            );
            $this->helper->insertJournalDetail($return, $jenis_simpanan->pengambilan_debit_coa, $penarikan->nominal, 'D' );
            $this->helper->insertJournalDetail($return, $jenis_simpanan->pengambilan_credit_coa, $penarikan->nominal, 'C' );
        } catch (Exception $e) {
            
        }
        return $return;
    }
}
