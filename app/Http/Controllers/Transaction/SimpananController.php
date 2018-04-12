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


class SimpananController extends Controller
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
           $simpanans = Simpanan::with('anggota', 'jenissimpanan')->select('simpanan.*');
            return Datatables::of($simpanans)
                   ->addColumn('action', function($simpanan){
                        return view('datatable._action',[
                                'model' => $simpanan,
                                'form_url' => route('simpanan.destroy', $simpanan->id),
                                'edit_url' => route('simpanan.edit', $simpanan->id),
                                'show_url' => route('simpanan.show', $simpanan->id),
                                'confirm_message' => 'Yakin mau menghapus ' . $simpanan->no_transaksi . '?'
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

        return view('admin.simpanan.index')->with(compact('html'));   
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.simpanan.create');
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
            'no_transaksi' => 'required|unique:simpanan',
            'id_anggota' => 'required|numeric',
            'id_simpanan' => 'required|numeric',
            'nominal' => 'required|numeric',
            'tanggal_transaksi' => 'required',
        ],[]);

        $simpanan = Simpanan::create($request->all());
        $simpanan->jurnal_id = $this->insertJournal($simpanan);
        $simpanan->save();

        Session::flash(
            "flash_notification",
            [
                'level' => 'success',
                "icon" => "fa fa-check",
                'message' => 'Berhasil melakukan simpanan '.$simpanan->no_transaksi,
            ]
        );


        return redirect()->route('simpanan.index');
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
        echo "show";
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
        $simpanan = Simpanan::find($id);
        return view('admin.simpanan.edit')->with(compact('simpanan'));
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
        $this->validate($request,[
            'no_transaksi' => 'required|unique:simpanan,no_transaksi,'.$id,
            'id_anggota' => 'required|numeric',
            'id_simpanan' => 'required|numeric',
            'nominal' => 'required|numeric',
            'tanggal_transaksi' => 'required',
        ],[]);

        $simpanan = Simpanan::find($id);
        $simpanan->update($request->all());

        $simpanan->jurnal_id = $this->updateJournal($simpanan);
        $simpanan->save();



        Session::flash(
            "flash_notification",
            [
                'level' => 'success',
                "icon" => "fa fa-check",
                'message' => 'Berhasil mengedit simpanan '.$simpanan->no_transaksi,
            ]
        );


        return redirect()->route('simpanan.index');
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
        $simpanan_old = Simpanan::find($id);
        if (!Simpanan::destroy($id)) {
            return redirect()->back();
        }
        try {
            $delete_header = JournalHeader::where('id', $simpanan_old->jurnal_id)->delete();
            $delete_detail = JournalDetail::where('jurnal_header_id', $simpanan_old->jurnal_id)->delete();   
        } catch (Exception $e) {
            
        }

        Session::flash("flash_notification", [
            "level" => "success",
            "icon" => "fa fa-check",
            "message" => "Transaksi Simpanan berhasil dihapus"
        ]);
        return redirect()->route('simpanan.index');
    }



    private function updateJournal(Simpanan $simpanan){
        $jenis_simpanan = JenisSimpanan::find($simpanan->id_simpanan);
        $anggota        = Anggota::find($simpanan->id_anggota);

        try {
            $return = $this->helper->updateJournalHeader($simpanan->jurnal_id,0, $simpanan->tanggal_transaksi_original,  $simpanan->nominal ,  $simpanan->nominal, $jenis_simpanan->nama_simpanan.' '.$anggota->nama.' '.$simpanan->no_transaksi
            );
            $delete_detail = JournalDetail::where('jurnal_header_id', $simpanan->jurnal_id)->delete();  
            $this->helper->insertJournalDetail($simpanan->jurnal_id, $jenis_simpanan->peminjaman_debit_coa, $simpanan->nominal, 'D' );
            $this->helper->insertJournalDetail($simpanan->jurnal_id, $jenis_simpanan->peminjaman_credit_coa, $simpanan->nominal, 'C' );


        } catch (Exception $e) {
            
        }

        return $return;
    }


    private function insertJournal(Simpanan $simpanan){
        $jenis_simpanan = JenisSimpanan::find($simpanan->id_simpanan);
        $anggota        = Anggota::find($simpanan->id_anggota);
        try {
            $return = $this->helper->insertJournalHeader(
                0, $simpanan->tanggal_transaksi_original,  $simpanan->nominal ,  $simpanan->nominal, $jenis_simpanan->nama_simpanan.' '.$anggota->nama.' '.$simpanan->no_transaksi
            );
            $this->helper->insertJournalDetail($return, $jenis_simpanan->peminjaman_debit_coa, $simpanan->nominal, 'D' );
            $this->helper->insertJournalDetail($return, $jenis_simpanan->peminjaman_credit_coa, $simpanan->nominal, 'C' );
        } catch (Exception $e) {
            
        }
        return $return;
    }


    public function viewAnggota(){
        return view('admin.simpanan.viewanggota');
    }

    public function viewTabungan(Request $request){
        if($request->ajax()){
            $id_anggota = $request->id_anggota;
            $from = date('Y-m-d', strtotime($request->from));
            $to = date('Y-m-d', strtotime($request->to));
            $simpanan = Simpanan::with('anggota','jenissimpanan')
                                  ->where('simpanan.id_anggota', (int) $id_anggota)
                                  ->whereBetween('simpanan.tanggal_transaksi', [$from, $to])
                                  ->orderBy('simpanan.id_simpanan', 'asc')
                                  ->get();

            $penarikan = Penarikan::with('anggota','jenissimpanan')
                                  ->where('penarikan.id_anggota', (int) $id_anggota)
                                  ->whereBetween('penarikan.tanggal_transaksi', [$from, $to])
                                  ->orderBy('penarikan.id_simpanan', 'asc')
                                  ->get();

            $return = [
              'simpanan' => $simpanan,
              'penarikan' => $penarikan,
            ] ;     

            return response()->json($return);
        }

    }

}
