<?php

namespace App\Http\Controllers\Transaction;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Model\JenisTransaksi;
use App\Model\TransaksiUnit;

use App\Model\Acc\Coa;
use App\Model\Acc\JournalHeaderUnit;
use App\Model\Acc\JournalDetailUnit;

use App\Helpers\Common;

use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\File;
use App\Utilities\ImportFile;
use Excel;
use Illuminate\Support\Facades\Input;

use Session;

class TransaksiUnitController extends Controller
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
        if ($request->ajax()) {
           $transaksis = TransaksiUnit::with(['jenistransaksi', 'unit'])->select('transaksi_unit.*');
            return Datatables::of($transaksis)
                   ->addColumn('action', function($transaksi){
                        return view('datatable._action',[
                                'model' => $transaksi,
                                'form_url' => route('transaksiunit.destroy', $transaksi->id),
                                'edit_url' => route('transaksiunit.edit', $transaksi->id),
                                'show_url' => route('transaksiunit.show', $transaksi->id),
                                'confirm_message' => 'Yakin mau menghapus ' . $transaksi->no_transaksi . '?'
                            ]);
                   })->make(true);
       }
       $html = $htmlBuilder
            ->addColumn(['data' => 'id', 'name' => 'id', 'title' => 'ID'])
            ->addColumn(['data' => 'no_transaksi', 'name' => 'no_transaksi', 'title' => 'Nomor Transaksi'])
            ->addColumn(['data' => 'tanggal', 'name' => 'tanggal', 'title' => 'Tanggal'])
            ->addColumn(['data' => 'nominalview', 'name' => 'nominal', 'title' => 'Nominal'])
            ->addColumn(['data' => 'jenistransaksi.nama_transaksi', 'name' => 'jenistransaksi.nama_transaksi', 'title' => 'Jenis Transaksi'])
            ->addColumn(['data' => 'unit.name', 'name' => 'unit.name', 'title' => 'Unit'])
            ->addColumn(['data' => 'action', 'name' => 'action', 'title' => '', 'orderable' => false, 'searchable' => false])
            ->parameters([
                    'order' => [
                        0, // here is the column number
                        'desc'
                    ]
            ]);

        return view('admin.transaksiunit.index')->with(compact('html')); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.transaksiunit.create'); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'no_transaksi' => 'required|unique:transaksi_unit',
            'type' => 'required|numeric',
            'id_jenis_transaksi' => 'required|numeric',
            'id_unit' => 'required|numeric',
            'nominal' => 'required|numeric',
            'tanggal' => 'required',
        ],[]);

        $transaksiunit = TransaksiUnit::create($request->all());
        $transaksiunit->jurnal_id = $this->insertJournal($transaksiunit);
        $transaksiunit->save();

        Session::flash(
            "flash_notification",
            [
                'level' => 'success',
                "icon" => "fa fa-check",
                'message' => 'Berhasil melakukan simpanan '.$transaksiunit->no_transaksi,
            ]
        );


        return redirect()->route('transaksiunit.index');
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
        $transaksiunit = TransaksiUnit::find($id);
        return view('admin.transaksiunit.edit')->with(compact('transaksiunit'));
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
            'no_transaksi' => 'required|unique:transaksi_unit,no_transaksi,'.$id,
            'type' => 'required|numeric',
            'id_jenis_transaksi' => 'required|numeric',
            'nominal' => 'required|numeric',
            'tanggal' => 'required',
            'id_unit' => 'required|numeric',
        ],[]);

        $transaksiunit = TransaksiUnit::find($id);
        $transaksiunit->update($request->all());

        $transaksiunit->jurnal_id = $this->updateJournal($transaksiunit);
        $transaksiunit->save();



        Session::flash(
            "flash_notification",
            [
                'level' => 'success',
                "icon" => "fa fa-check",
                'message' => 'Berhasil mengedit transaksi '.$transaksiunit->no_transaksi,
            ]
        );


        return redirect()->route('transaksiunit.index');
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
        $transaksi_old = TransaksiUnit::find($id);
        if (!TransaksiUnit::destroy($id)) {
            return redirect()->back();
        }
        try {
            $delete_header = JournalHeaderUnit::where('id', $transaksi_old->jurnal_id)->delete();
            $delete_detail = JournalDetailUnit::where('jurnal_header_id', $transaksi_old->jurnal_id)->delete();   
        } catch (Exception $e) {
            
        }

        Session::flash("flash_notification", [
            "level" => "success",
            "icon" => "fa fa-check",
            "message" => "Transaksi berhasil dihapus"
        ]);
        return redirect()->route('transaksiunit.index');
    }

    private function insertJournal(TransaksiUnit $transaksi){
        $jenis_transaksi = JenisTransaksi::find($transaksi->id_jenis_transaksi);

        try {
            $return = $this->helper->insertJournalHeaderUnit(
                0, $transaksi->tanggal_original,  $transaksi->nominal ,  $transaksi->nominal, $jenis_transaksi->nama_transaksi.' '.$transaksi->no_transaksi.' '.$transaksi->keterangan, $transaksi->id_unit
            );
            $this->helper->insertJournalDetailUnit($return, $jenis_transaksi->debit_coa, $transaksi->nominal, 'D' );
            $this->helper->insertJournalDetailUnit($return, $jenis_transaksi->credit_coa, $transaksi->nominal, 'C' );
        } catch (Exception $e) {
            
        }
        return $return;
    }

    private function updateJournal(TransaksiUnit $transaksi){
        $jenis_transaksi = JenisTransaksi::find($transaksi->id_jenis_transaksi);

        try {
            $return = $this->helper->updateJournalHeaderUnit($transaksi->jurnal_id,0, $transaksi->tanggal_original,  $transaksi->nominal ,  $transaksi->nominal, $jenis_transaksi->nama_transaksi.' '.$transaksi->no_transaksi.' '.$transaksi->keterangan, $transaksi->id_unit
            );
            $delete_detail = JournalDetailUnit::where('jurnal_header_id', $transaksi->jurnal_id)->delete();  
            $this->helper->insertJournalDetailUnit($transaksi->jurnal_id, $jenis_transaksi->debit_coa, $transaksi->nominal, 'D' );
            $this->helper->insertJournalDetailUnit($transaksi->jurnal_id, $jenis_transaksi->credit_coa, $transaksi->nominal, 'C' );


        } catch (Exception $e) {
            
        }

        return $return;
    }



   
}
