<?php

namespace App\Http\Controllers\Transaction;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Model\Peminjaman;
use App\Model\Anggota;
use App\Model\Acc\Coa;
use App\Model\Acc\JournalHeader;
use App\Model\Acc\JournalDetail;
use App\Model\Settingcoa;
use App\Model\Angsuran;
use App\Model\Pinalti;
use App\Model\ProyeksiAngsuran;

use App\Helpers\Common;

use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\File;
use App\Utilities\ImportFile;
use Excel;
use Illuminate\Support\Facades\Input;

use Session;





class PinaltiController extends Controller
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
           $pinaltis = Pinalti::with('anggota', 'peminjaman')->select('pinalti.*');
            return Datatables::of($pinaltis)
                   ->addColumn('action', function($pinalti){
                        return view('datatable._action',[
                                'model' => $pinalti,
                                'form_url' => route('pinalti.destroy', $pinalti->id),
                                'edit_url' => route('pinalti.edit', $pinalti->id),
                                'show_url' => route('pinalti.show', $pinalti->id),
                                'confirm_message' => 'Yakin mau menghapus ' . $pinalti->no_transaksi . '?'
                            ]);
                   })->make(true);
       }
       $html = $htmlBuilder
            ->addColumn(['data' => 'id', 'name' => 'id', 'title' => 'ID'])
            ->addColumn(['data' => 'no_transaksi', 'name' => 'no_transaksi', 'title' => 'No. Pinalti'])
             ->addColumn(['data' => 'peminjaman.no_transaksi', 'name' => 'peminjaman.no_transaksi', 'title' => 'No. Pinjaman'])
            ->addColumn(['data' => 'anggota.nik', 'name' => 'anggota.nik', 'title' => 'NIK'])
            ->addColumn(['data' => 'anggota.nama', 'name' => 'anggota.nama', 'title' => 'Nama'])
            ->addColumn(['data' => 'tanggal', 'name' => 'tanggal', 'title' => 'Tanggal'])
            ->addColumn(['data' => 'statusview', 'name' => 'status', 'title' => 'Status'])
            ->addColumn(['data' => 'action', 'name' => 'action', 'title' => '', 'orderable' => false, 'searchable' => false])
            ->parameters([
                    'order' => [
                        0, // here is the column number
                        'desc'
                    ]
            ]);

        return view('admin.pinalti.index')->with(compact('html'));   
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
         return view('admin.pinalti.create');
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
            'no_transaksi' => 'required|unique:pinalti',
            'id_anggota' => 'required|numeric',
            'tanggal' => 'required',
            'id_peminjaman' => 'required|numeric',
            'banyak_angsuran'      => 'required|numeric',
            'nominal'   => 'required|numeric',
            'angsuran_nominal' => 'required|numeric'
        ],[]);

        $pinalti = Pinalti::create($request->all());

        Session::flash(
            "flash_notification",
            [
                'level' => 'success',
                "icon" => "fa fa-check",
                'message' => 'Berhasil melakukan angsuran pinjaman '.$pinalti->no_transaksi,
            ]
        );


        return redirect()->route('pinalti.index');

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
        $pinalti = Pinalti::find($id);
        return view('admin.pinalti.edit')->with(compact('pinalti'));
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
            'no_transaksi' => 'required|unique:pinalti,no_transaksi,'.$id,
            'id_anggota' => 'required|numeric',
            'tanggal' => 'required',
            'id_peminjaman' => 'required|numeric',
            'banyak_angsuran'      => 'required|numeric',
            'nominal'   => 'required|numeric',
            'angsuran_nominal' => 'required|numeric'
        ],[]);

        $pinalti = Pinalti::find($id);
        $pinalti->update($request->all());

        if (isset($request->valid) && $request->valid=='Valid') {
            $pinalti->approve_by = auth()->user()->id;
            $pinalti->status = 1;
            $pinalti->save();


            $this->insertJournal($pinalti);
            $this->insertAngsuran($pinalti);
        }


         Session::flash(
            "flash_notification",
            [
                'level' => 'success',
                "icon" => "fa fa-check",
                'message' => 'Berhasil melakukan pinalti pinjaman '.$pinalti->no_transaksi,
            ]
        );


        return redirect()->route('pinalti.index');
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
        $pinalti_old = Pinalti::find($id);
        if ($pinalti_old->status > 0) {
            Session::flash("flash_notification", [
                "level" => "error",
                "icon" => "fa fa-check",
                "message" => "Transaksi Pinalti tidak bisa dihapus"
            ]);
            return redirect()->route('pinalti.index');
        }
    

        if (!Pinalti::destroy($id)) {
            return redirect()->back();
        }
     
        Session::flash("flash_notification", [
            "level" => "success",
            "icon" => "fa fa-check",
            "message" => "Transaksi Pinalti berhasil dihapus"
        ]);
        return redirect()->route('pinalti.index');
    }


     public function viewproyeksi(Request $request){
        if($request->ajax()){
            $id_pinjaman = (int)$request->id_pinjaman;
            $tanggal_transaksi =  date("Y-m-d", strtotime($request->tanggal) );

           
            $pinjaman =\DB::select('select p.nominal,(p.nominal-sum(ifnull(a.pokok,0) )) as saldo, p.tenor, p.cicilan
                                    from peminjaman p  
                                    left join angsuran a on (p.id = a.id_pinjaman )
                                    where p.id ='.$id_pinjaman.'
                                    group by p.id');

            $angsuran = Angsuran::where('id_pinjaman',$id_pinjaman)->get();

            $return =[
              'pinjaman' => isset($pinjaman[0])? $pinjaman[0]: []  ,
              'angsuran' => $angsuran,
            ];
            return response()->json($return);

            // $peminjaman  = ProyeksiAngsuran::where('peminjaman_id', $id_pinjaman)
            //                         ->where('status',0)
            //                         ->where('tanggal_proyeksi', '<', $tanggal_transaksi )   
            //                         ->get()->toArray();


            // $peminjaman2 = ProyeksiAngsuran::where('peminjaman_id', $id_pinjaman)
            //                         ->where('status',0)
            //                         ->where('tanggal_proyeksi', '>=', $tanggal_transaksi )    
            //                         ->limit(1)
            //                         ->get()->toArray();

            //return response()->json(array_merge($peminjaman,$peminjaman2) );
        }
    }


     private function insertJournal(Pinalti $pinalti){
        $anggota = Anggota::find($pinalti->id_anggota);

        ##nominal###
            if ($pinalti->nominal>0) {
              $return = $this->helper->insertJournalHeader(
                0, $pinalti->tanggal_validasi_original,  $pinalti->nominal ,  $pinalti->nominal,'Pinalti : '. $pinalti->no_transaksi.' , Peminjaman:'.$pinalti->peminjaman->no_transaksi
              );

              $pinalti_debit  = Settingcoa::where('transaksi','pinalti_debit')->select('id_coa')->first();
              $pinalti_credit =  Settingcoa::where('transaksi','pinalti_credit')->select('id_coa')->first() ;

              $this->helper->insertJournalDetail($return, $pinalti_debit->id_coa, $pinalti->nominal, 'D' );
              $this->helper->insertJournalDetail($return, $pinalti_credit->id_coa, $pinalti->nominal, 'C' );
            }
        ###
     }

     private function insertAngsuran(Pinalti $pinalti){
        $anggota = Anggota::find($pinalti->id_anggota);
        for ($i=0; $i < $pinalti->banyak_angsuran ; $i++) { 
            $proyeksi = ProyeksiAngsuran::where('status',0)
                                        ->where('peminjaman_id',$pinalti->id_peminjaman)
                                        ->first();
            $proyeksi->status = 1;
            $proyeksi->save();

            $data = [
                    'no_transaksi' =>  \App\Helpers\Common::getNoTransaksi('angsuran'),
                    'tanggal_transaksi' => $pinalti->tanggal ,
                    'id_pinjaman' => $pinalti->id_peminjaman ,
                    'id_anggota' => $pinalti->id_anggota ,
                    'pokok'      => $pinalti->peminjaman->cicilan ,
                    'bunga'      => 0 ,
                    'simpanan_wajib' => 0 ,
                    'denda'           => 0 ,
                    'angsuran_ke'     => $proyeksi->angsuran_ke ,
                    'total'           =>  $pinalti->peminjaman->cicilan  ,
                    'id_proyeksi'     =>  $proyeksi->id,    
                    'status'          => 1,
                    'created_by'      => auth()->user()->id,
                    'tanggal_validasi' => $pinalti->tanggal ,
                    'approve_by'       => auth()->user()->id,
            ];

            $angsuran = Angsuran::create($data);

            ### cicilan ###
            if ($angsuran->pokok>0) {
               $return = $this->helper->insertJournalHeader(
                0, $angsuran->tanggal_validasi_original,  $angsuran->pokok ,  $angsuran->pokok, 'Angsuran ke '.(int)$angsuran->angsuran_ke.', Pinjaman '.$anggota->nama.'( '.$anggota->nik.' ), No. Angsuran : '.$angsuran->no_transaksi.' peminjaman:'.$angsuran->peminjaman->no_transaksi
                );

                $angsuran_debit  = Settingcoa::where('transaksi','angsuran_debit')->select('id_coa')->first();
                $angsuran_credit =  Settingcoa::where('transaksi','angsuran_credit')->select('id_coa')->first() ;

                $this->helper->insertJournalDetail($return, $angsuran_debit->id_coa, $angsuran->pokok, 'D' );
                $this->helper->insertJournalDetail($return, $angsuran_credit->id_coa, $angsuran->pokok, 'C' );
            }
        }
        $_peminjaman = Peminjaman::find( $pinalti->id_peminjaman );
        $_peminjaman->status = 2;
        $_peminjaman->save();




     }

}
