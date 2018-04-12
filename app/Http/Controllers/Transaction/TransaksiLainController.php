<?php

namespace App\Http\Controllers\Transaction;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Model\JenisTransaksi;
use App\Model\Transaksi;

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


class TransaksiLainController extends Controller
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
           $transaksis = Transaksi::with('jenistransaksi')->select('transaksi.*');
            return Datatables::of($transaksis)
                   ->addColumn('action', function($transaksi){
                        return view('datatable._action',[
                                'model' => $transaksi,
                                'form_url' => route('lain.destroy', $transaksi->id),
                                'edit_url' => route('lain.edit', $transaksi->id),
                                'show_url' => route('lain.show', $transaksi->id),
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
            ->addColumn(['data' => 'action', 'name' => 'action', 'title' => '', 'orderable' => false, 'searchable' => false])
            ->parameters([
                    'order' => [
                        0, // here is the column number
                        'desc'
                    ]
            ]);

        return view('admin.transaksi.index')->with(compact('html'));   
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.transaksi.create');
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
         //
        $this->validate($request,[
            'no_transaksi' => 'required|unique:transaksi',
            'type' => 'required|numeric',
            'id_jenis_transaksi' => 'required|numeric',
            'nominal' => 'required|numeric',
            'tanggal' => 'required',
        ],[]);

        $transaksi = Transaksi::create($request->all());
        $transaksi->jurnal_id = $this->insertJournal($transaksi);
        $transaksi->save();

        Session::flash(
            "flash_notification",
            [
                'level' => 'success',
                "icon" => "fa fa-check",
                'message' => 'Berhasil melakukan simpanan '.$transaksi->no_transaksi,
            ]
        );


        return redirect()->route('lain.index');
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
        $transaksi = Transaksi::find($id);
        return view('admin.transaksi.edit')->with(compact('transaksi'));
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
        //
        $this->validate($request,[
            'no_transaksi' => 'required|unique:transaksi,no_transaksi,'.$id,
            'type' => 'required|numeric',
            'id_jenis_transaksi' => 'required|numeric',
            'nominal' => 'required|numeric',
            'tanggal' => 'required',
        ],[]);

        $transaksi = Transaksi::find($id);
        $transaksi->update($request->all());

        $transaksi->jurnal_id = $this->updateJournal($transaksi);
        $transaksi->save();



        Session::flash(
            "flash_notification",
            [
                'level' => 'success',
                "icon" => "fa fa-check",
                'message' => 'Berhasil mengedit transaksi '.$transaksi->no_transaksi,
            ]
        );


        return redirect()->route('lain.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $transaksi_old = Transaksi::find($id);
        if (!Transaksi::destroy($id)) {
            return redirect()->back();
        }
        try {
            $delete_header = JournalHeader::where('id', $transaksi_old->jurnal_id)->delete();
            $delete_detail = JournalDetail::where('jurnal_header_id', $transaksi_old->jurnal_id)->delete();   
        } catch (Exception $e) {
            
        }

        Session::flash("flash_notification", [
            "level" => "success",
            "icon" => "fa fa-check",
            "message" => "Transaksi berhasil dihapus"
        ]);
        return redirect()->route('lain.index');
    }


     private function insertJournal(Transaksi $transaksi){
        $jenis_transaksi = JenisTransaksi::find($transaksi->id_jenis_transaksi);

        try {
            $return = $this->helper->insertJournalHeader(
                0, $transaksi->tanggal_original,  $transaksi->nominal ,  $transaksi->nominal, $jenis_transaksi->nama_transaksi.' '.$transaksi->no_transaksi.' '.$transaksi->keterangan
            );
            $this->helper->insertJournalDetail($return, $jenis_transaksi->debit_coa, $transaksi->nominal, 'D' );
            $this->helper->insertJournalDetail($return, $jenis_transaksi->credit_coa, $transaksi->nominal, 'C' );
        } catch (Exception $e) {
            
        }
        return $return;
    }

    private function updateJournal(Transaksi $transaksi){
        $jenis_transaksi = JenisTransaksi::find($transaksi->id_jenis_transaksi);

        try {
            $return = $this->helper->updateJournalHeader($transaksi->jurnal_id,0, $transaksi->tanggal_original,  $transaksi->nominal ,  $transaksi->nominal, $jenis_transaksi->nama_transaksi.' '.$transaksi->no_transaksi.' '.$transaksi->keterangan
            );
            $delete_detail = JournalDetail::where('jurnal_header_id', $transaksi->jurnal_id)->delete();  
            $this->helper->insertJournalDetail($transaksi->jurnal_id, $jenis_transaksi->debit_coa, $transaksi->nominal, 'D' );
            $this->helper->insertJournalDetail($transaksi->jurnal_id, $jenis_transaksi->credit_coa, $transaksi->nominal, 'C' );


        } catch (Exception $e) {
            
        }

        return $return;
    }
}
