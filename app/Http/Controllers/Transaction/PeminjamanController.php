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

use App\Helpers\Common;

use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\File;
use App\Utilities\ImportFile;
use Excel;
use Illuminate\Support\Facades\Input;

use Session;

class PeminjamanController extends Controller
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
           $peminjamans = Peminjaman::with('anggota', 'keteranganpinjaman');
            return Datatables::of($peminjamans)
                   ->addColumn('action', function($peminjaman){
                        return view('datatable._action',[
                                'model' => $peminjaman,
                                'form_url' => route('peminjaman.destroy', $peminjaman->id),
                                'edit_url' => route('peminjaman.edit', $peminjaman->id),
                                'show_url' => route('peminjaman.show', $peminjaman->id),
                                'confirm_message' => 'Yakin mau menghapus ' . $peminjaman->no_transaksi . '?'
                            ]);
                   })->make(true);
       }
       $html = $htmlBuilder
            ->addColumn(['data' => 'id', 'name' => 'id', 'title' => 'ID'])
            ->addColumn(['data' => 'no_transaksi', 'name' => 'no_transaksi', 'title' => 'Nomor Transaksi'])
            ->addColumn(['data' => 'anggota.nik', 'name' => 'anggota.nik', 'title' => 'NIK'])
            ->addColumn(['data' => 'anggota.nama', 'name' => 'anggota.nama', 'title' => 'Nama'])
            ->addColumn(['data' => 'tanggal_pengajuan', 'name' => 'tanggal_pengajuan', 'title' => 'Tanggal Pengajuan'])
            ->addColumn(['data' => 'nominalview', 'name' => 'nominal', 'title' => 'Nominal'])
            ->addColumn(['data' => 'statusview', 'name' => 'status', 'title' => 'Status'])
            ->addColumn(['data' => 'action', 'name' => 'action', 'title' => '', 'orderable' => false, 'searchable' => false])
            ->parameters([
                    'order' => [
                        0, // here is the column number
                        'desc'
                    ]
            ]);

        return view('admin.peminjaman.index')->with(compact('html'));   
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.peminjaman.create');
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
            'no_transaksi' => 'required|unique:peminjaman',
            'id_anggota' => 'required|numeric',
            'id_keterangan_pinjaman' => 'required|numeric',
            'nominal' => 'required|numeric',
            'tanggal_pengajuan' => 'required',
            'tenor' => 'required',
            'bunga_persen' => 'required',
            'cicilan' => 'required',
            'bunga_nominal' => 'required',
        ],[]);

        $peminjaman = Peminjaman::create($request->all());
        Session::flash(
            "flash_notification",
            [
                'level' => 'success',
                "icon" => "fa fa-check",
                'message' => 'Berhasil melakukan pengajuan pinjaman '.$peminjaman->no_transaksi,
            ]
        );


        return redirect()->route('peminjaman.index');

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
        $peminjaman = Peminjaman::find($id);
        return view('admin.peminjaman.edit')->with(compact('peminjaman'));
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
            'no_transaksi' => 'required|unique:peminjaman,no_transaksi,'.$id,
            'id_anggota' => 'required|numeric',
            'id_keterangan_pinjaman' => 'required|numeric',
            'nominal' => 'required|numeric',
            'tanggal_pengajuan' => 'required',
            'tenor' => 'required',
            'bunga_persen' => 'required',
            'cicilan' => 'required',
            'bunga_nominal' => 'required',
        ],[]);

        $peminjaman = Peminjaman::find($id);
        $peminjaman->update($request->all());

        if (isset($request->approve) && $request->approve=='Approve') {
            $peminjaman->status = 1;
            $peminjaman->tanggal_disetujui = date('Y-m-d');
            $peminjaman->approve_by = auth()->user()->id;
            $peminjaman->save();
            $peminjaman->jurnal_id = $this->insertJournal($peminjaman);
            $peminjaman->save();
            $this->insertProyeksi($peminjaman);
        }

        Session::flash(
            "flash_notification",
            [
                'level' => 'success',
                "icon" => "fa fa-check",
                'message' => 'Berhasil mengedit pengajuan pinjaman '.$peminjaman->no_transaksi,
            ]
        );


        return redirect()->route('peminjaman.index');
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
        $peminjaman_old = Peminjaman::find($id);
        if ($peminjaman_old->status ==1) {
            Session::flash("flash_notification", [
                "level" => "error",
                "icon" => "fa fa-check",
                "message" => "Transaksi Peminjaman tidak bisa dihapus"
            ]);
            return redirect()->route('peminjaman.index');
        }
    

        if (!Peminjaman::destroy($id)) {
            return redirect()->back();
        }
        // try {
        //     $delete_header = JournalHeader::where('id', $simpanan_old->jurnal_id)->delete();
        //     $delete_detail = JournalDetail::where('jurnal_header_id', $simpanan_old->jurnal_id)->delete();   
        // } catch (Exception $e) {
            
        // }

        Session::flash("flash_notification", [
            "level" => "success",
            "icon" => "fa fa-check",
            "message" => "Transaksi Peminjaman berhasil dihapus"
        ]);
        return redirect()->route('peminjaman.index');
    }


    private function insertJournal(Peminjaman $peminjaman){
        $anggota = Anggota::find($peminjaman->id_anggota);
        try {
             $return = $this->helper->insertJournalHeader(
                0, $peminjaman->tanggal_disetujui,  $peminjaman->nominal ,  $peminjaman->nominal, 'Pengajuan Pinjaman '.$anggota->nama.'( '.$anggota->nik.' ) '.$peminjaman->no_transaksi
            );

            $peminjaman_debit  = Settingcoa::where('transaksi','peminjaman_debit')->select('id_coa')->first();
            $peminjaman_credit =  Settingcoa::where('transaksi','peminjaman_credit')->select('id_coa')->first() ;

            $this->helper->insertJournalDetail($return, $peminjaman_debit->id_coa, $peminjaman->nominal, 'D' );
            $this->helper->insertJournalDetail($return, $peminjaman_credit->id_coa, $peminjaman->nominal, 'C' );
        } catch (Exception $e) {
            
        }

        
        return $return;
    }


    public function insertProyeksi(Peminjaman $peminjaman){
        $tanggal_proyeksi = $this->helper->getNextMonth($peminjaman->tanggal_disetujui);
        for ($i=1; $i <= $peminjaman->tenor ; $i++) { 
            $proyeksi = ProyeksiAngsuran::create(
                [
                    'peminjaman_id' => $peminjaman->id, 
                    'tanggal_proyeksi' => $tanggal_proyeksi, 
                    'cicilan' => $peminjaman->cicilan,
                    'bunga_nominal' => $peminjaman->bunga_nominal, 
                    'simpanan_wajib' => 15000, 
                    'status' => 0,
                    'angsuran_ke' => $i,
                ]
            );
            $tanggal_proyeksi = $this->helper->getNextMonth($proyeksi->tanggal_proyeksi);
        }
    }

    public function viewpeminjaman(){
        return view('admin.peminjaman.viewpeminjaman');
    }

    public function viewproyeksi(Request $request){
         if($request->ajax()){
            $peminjaman_id = $request->id_peminjaman;
            $proyeksi = ProyeksiAngsuran::with('peminjaman','peminjaman.anggota','angsuran')
                                  ->where('proyeksi_angsuran.peminjaman_id', (int) $peminjaman_id)
                                  ->get();


            return response()->json($proyeksi);
        }
    }


}
