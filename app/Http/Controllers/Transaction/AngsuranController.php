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
use App\Model\JenisSimpanan;
use App\Model\Simpanan;

use App\Helpers\Common;

use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\File;
use App\Utilities\ImportFile;
use Excel;
use Illuminate\Support\Facades\Input;

use Session;

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
           $angsurans = Angsuran::with('peminjaman', 'anggota', 'peminjaman.keteranganpinjaman', 'proyeksiangsuran')->select('angsuran.*');
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
            ->addColumn(['data' => 'proyeksiangsuran.tgl_proyeksi', 'name' => 'proyeksiangsuran.tanggal_proyeksi', 'title' => 'Jatuh Tempo'])
            ->addColumn(['data' => 'angsuran_ke', 'name' => 'angsuran_ke', 'title' => 'Angsuran Ke-'])
             ->addColumn(['data' => 'statusview', 'name' => 'status', 'title' => 'Status'])
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
         $this->validate($request,[
            'no_transaksi' => 'required|unique:angsuran',
            'tanggal_transaksi' => 'required',
            'id_pinjaman' => 'required',
            'id_anggota' => 'required',
            'pokok'      => 'required|numeric',
            'bunga'      => 'required|numeric',
            'simpanan_wajib' => 'numeric',
            'denda'           => 'numeric',
            'angsuran_ke'     => 'required|numeric',
            'total'           => 'required|numeric' ,
            'id_proyeksi'     => 'required|numeric',    
        ],[]);

        $angsuran = Angsuran::create($request->all());

        // $proyeksi = ProyeksiAngsuran::find($angsuran->id_proyeksi);
        // $proyeksi->status = 1;
        // $proyeksi->save();

        Session::flash(
            "flash_notification",
            [
                'level' => 'success',
                "icon" => "fa fa-check",
                'message' => 'Berhasil melakukan angsuran pinjaman '.$angsuran->no_transaksi,
            ]
        );


        return redirect()->route('angsuran.index');
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
        $angsuran = Angsuran::find($id);
        return view('admin.angsuran.edit')->with(compact('angsuran'));
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
            'no_transaksi' => 'required|unique:angsuran,no_transaksi,'.$id,
            'tanggal_transaksi' => 'required',
            'id_pinjaman' => 'required',
            'id_anggota' => 'required',
            'pokok'      => 'required|numeric',
            'bunga'      => 'required|numeric',
            'simpanan_wajib' => 'numeric',
            'denda'           => 'numeric',
            'angsuran_ke'     => 'required|numeric',
            'total'           => 'required|numeric' ,
            'id_proyeksi'     => 'required|numeric',    
        ],[]);

        $angsuran = Angsuran::find($id);
        $angsuran->update($request->all());
        
        if (isset($request->valid) && $request->valid=='Valid') {
            $angsuran->status = 1;
            $angsuran->approve_by = auth()->user()->id;
            $angsuran->save();

            $this->insertJournal($angsuran);
            $this->insertSimpanan($angsuran);

            $proyeksi = ProyeksiAngsuran::find($angsuran->id_proyeksi);
            $proyeksi->status = 1;
            $proyeksi->save();

            if ($angsuran->peminjaman->tenor == $angsuran->angsuran_ke) {
                $peminjaman = Peminjaman::find($angsuran->id_pinjaman);
                $peminjaman->status = 2;
                $peminjaman->save();
            }
        }

        Session::flash(
            "flash_notification",
            [
                'level' => 'success',
                "icon" => "fa fa-check",
                'message' => 'Berhasil melakukan angsuran pinjaman '.$angsuran->no_transaksi,
            ]
        );


        return redirect()->route('angsuran.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $angsuran_old = Angsuran::find($id);
        if ($angsuran_old->status ==1) {
            Session::flash("flash_notification", [
                "level" => "error",
                "icon" => "fa fa-check",
                "message" => "Transaksi Angsuran tidak bisa dihapus"
            ]);
            return redirect()->route('angsuran.index');
        }
    

        if (!Angsuran::destroy($id)) {
            return redirect()->back();
        }
     
        Session::flash("flash_notification", [
            "level" => "success",
            "icon" => "fa fa-check",
            "message" => "Transaksi Angsuran berhasil dihapus"
        ]);
        return redirect()->route('angsuran.index');
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
            $id_pinjaman = (int)$request->id_pinjaman;
            $tanggal_transaksi =  date("Y-m-d", strtotime($request->tanggal_transaksi) );

            $q = '(select pa.* , DATE_FORMAT(tanggal_proyeksi,"%d-%m-%Y" ) as tgl_proyeksi
                  from proyeksi_angsuran pa
                  left join angsuran an on (pa.id = an.id_proyeksi)
                  where pa.peminjaman_id = '.$id_pinjaman.'
                  and pa.status = 0
                  and pa.tanggal_proyeksi >= "'.$tanggal_transaksi.'" 
                  and an.id_proyeksi is null
                  limit 1)
                  UNION
                  (select pa.* ,DATE_FORMAT(tanggal_proyeksi,"%d-%m-%Y" ) as tgl_proyeksi
                  from proyeksi_angsuran pa
                  left join angsuran an on (pa.id = an.id_proyeksi)
                  where pa.peminjaman_id = '.$id_pinjaman.'
                  and pa.status = 0
                  and pa.tanggal_proyeksi < "'.$tanggal_transaksi.'" 
                  and an.id_proyeksi is null)
                ';
            $proyeksi = \DB::select($q);
            $pinjaman =\DB::select('select p.nominal,(p.nominal-sum(ifnull(a.pokok,0) )) as saldo
                                    from peminjaman p  
                                    left join angsuran a on (p.id = a.id_pinjaman )
                                    where p.id ='.$id_pinjaman.'
                                    group by p.id');
            $return =[
              'proyeksi' => $proyeksi,
              'pinjaman' => isset($pinjaman[0])? $pinjaman[0]: []  ,
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

    public function viewdetailproyeksi(Request $request){
        if($request->ajax()){
            $id_proyeksi = $request->id_proyeksi;
             $tanggal_transaksi =  date("Y-m-d", strtotime($request->tanggal_transaksi) );
            if ($id_proyeksi<1) {
                return response()->json([]);
            }
            $proyeksi  = ProyeksiAngsuran::find($id_proyeksi)->toArray();
            $getProyeksiNextMonth = $this->helper->getNextMonth($proyeksi['tanggal_proyeksi']);
            if ($tanggal_transaksi>$getProyeksiNextMonth) {
                $proyeksi['denda'] = 1/100*$proyeksi['cicilan'];
            }else{
                $proyeksi['denda'] = 0;
            }

            return response()->json($proyeksi );
        }
    }

    private function insertJournal(Angsuran $angsuran){
        $anggota = Anggota::find($angsuran->id_anggota);
        try {
            ### cicilan ###
             $return = $this->helper->insertJournalHeader(
                0, $angsuran->tanggal_validasi_original,  $angsuran->pokok ,  $angsuran->pokok, 'Angsuran ke '.(int)$angsuran->angsuran_ke.', Pinjaman '.$anggota->nama.'( '.$anggota->nik.' ), No. Angsuran : '.$angsuran->no_transaksi.' peminjaman:'.$angsuran->peminjaman->no_transaksi
            );

            $angsuran_debit  = Settingcoa::where('transaksi','angsuran_debit')->select('id_coa')->first();
            $angsuran_credit =  Settingcoa::where('transaksi','angsuran_credit')->select('id_coa')->first() ;

            $this->helper->insertJournalDetail($return, $angsuran_debit->id_coa, $angsuran->pokok, 'D' );
            $this->helper->insertJournalDetail($return, $angsuran_credit->id_coa, $angsuran->pokok, 'C' );
            

            ## bunga ###
             $return = $this->helper->insertJournalHeader(
                0, $angsuran->tanggal_validasi_original,  $angsuran->bunga ,  $angsuran->bunga, 'Bunga Angsuran ke '.(int)$angsuran->angsuran_ke.', Pinjaman '.$anggota->nama.'( '.$anggota->nik.' ), No. Angsuran : '.$angsuran->no_transaksi.', Peminjaman:'.$angsuran->peminjaman->no_transaksi
            );

            $bunga_debit  = Settingcoa::where('transaksi','bunga_debit')->select('id_coa')->first();
            $bunga_credit =  Settingcoa::where('transaksi','bunga_credit')->select('id_coa')->first() ;

            $this->helper->insertJournalDetail($return, $bunga_debit->id_coa, $angsuran->bunga, 'D' );
            $this->helper->insertJournalDetail($return, $bunga_credit->id_coa, $angsuran->bunga, 'C' );
        } catch (Exception $e) {
            
        }
        
        return $return;
    }

    public function insertSimpanan(Angsuran $angsuran){
         if ($angsuran->simpanan_wajib<1) {
             return ;
         }
         $jenis_simpanan = JenisSimpanan::where('nama_simpanan','like','%Simpanan Wajib%')->first();
         $no_transaksi_simp = "SIMP".date("dmY").sprintf("%07d", \App\Model\Simpanan::count('id') + 1 );
         $simpanan = Simpanan::create(
            [
                'no_transaksi' => $no_transaksi_simp,
                'id_anggota' => $angsuran->id_anggota,
                'id_simpanan' => $jenis_simpanan->id,
                'nominal' => $angsuran->simpanan_wajib,
                'tanggal_transaksi' => $angsuran->tanggal_validasi,
                'keterangan'   => 'simpanan wajib pada angsuran '.$angsuran->no_transaksi,
            ]
         );

         $jenis_simpanan = JenisSimpanan::find($simpanan->id_simpanan);
         $anggota        = Anggota::find($simpanan->id_anggota);

         try {
            $return = $this->helper->insertJournalHeader(
                0, $simpanan->tanggal_transaksi_original,  $simpanan->nominal ,  $simpanan->nominal, $jenis_simpanan->nama_simpanan.' '.$anggota->nama.' '.$simpanan->no_transaksi ." Angsuran ".$angsuran->no_transaksi
            );
            $this->helper->insertJournalDetail($return, $jenis_simpanan->peminjaman_debit_coa, $simpanan->nominal, 'D' );
            $this->helper->insertJournalDetail($return, $jenis_simpanan->peminjaman_credit_coa, $simpanan->nominal, 'C' );
        } catch (Exception $e) {
            
        }
    }


    public function createmassal(){
        return view('admin.angsuran.createmassal');
    }

    public function viewjatuhtempo(Request $request){
        $return = [];
        if($request->ajax()){
            $id_unit = $request->id_unit;
            $from = date("Y-m-d", strtotime($request->from) );
            $to = date("Y-m-d", strtotime($request->to) ); 

            $q = 'select p.id, p.no_transaksi , p.id_anggota, 
                  CONCAT(a.nik,"-",a.nama) AS nama_lengkap,
                  pa.id as id_proyeksi, CONCAT("(",pa.angsuran_ke,")","", DATE_FORMAT(tanggal_proyeksi,"%d-%m-%Y" )) as label_pa,
                  pa.angsuran_ke , pa.cicilan, pa.bunga_nominal, pa.simpanan_wajib, pa.tanggal_proyeksi,  DATE_FORMAT(tanggal_proyeksi,"%d-%m-%Y" ) as tgl_proyeksi, p.nominal,  p.nominal - IFNULL((select sum(pokok) from angsuran where id_pinjaman = p.id ),0) as saldopinjaman
                  from peminjaman p
                  join anggota a on (p.id_anggota = a.id)
                  join proyeksi_angsuran pa on (pa.peminjaman_id = p.id)
                  left join angsuran an on (pa.id = an.id_proyeksi)
                  where p.status = 1 and a.unit_kerja = '.$id_unit.'
                  and pa.status = 0
                  and pa.tanggal_proyeksi >= "'.$from.'" and pa.tanggal_proyeksi <= "'.$to.'"
                  and an.id_proyeksi is null
                  UNION
                  select p.id, p.no_transaksi , p.id_anggota, 
                  CONCAT(a.nik,"-",a.nama) AS nama_lengkap,
                  pa.id as id_proyeksi, CONCAT("(",pa.angsuran_ke,")","", DATE_FORMAT(tanggal_proyeksi,"%d-%m-%Y" )) as label_pa,
                  pa.angsuran_ke , pa.cicilan, pa.bunga_nominal, pa.simpanan_wajib, pa.tanggal_proyeksi,  DATE_FORMAT(tanggal_proyeksi,"%d-%m-%Y" ) as tgl_proyeksi, p.nominal,  p.nominal - IFNULL((select sum(pokok) from angsuran where id_pinjaman = p.id ),0) as saldopinjaman 
                  from peminjaman p
                  join anggota a on (p.id_anggota = a.id)
                  join proyeksi_angsuran pa on (pa.peminjaman_id = p.id)
                  left join angsuran an on (pa.id = an.id_proyeksi)
                  where p.status = 1 and a.unit_kerja = '.$id_unit.'
                  and pa.status = 0
                  and pa.tanggal_proyeksi < "'.date('Y-m-d').'"
                  and an.id_proyeksi is null
                ';
            $peminjaman = \DB::select($q);
            foreach ($peminjaman as $p ) {
                $p = (array) $p;
                $getProyeksiNextMonth = $this->helper->getNextMonth($p['tanggal_proyeksi']);
                if (date('Y-m-d')>$getProyeksiNextMonth) {
                    $p['denda'] = 1/100*$p['cicilan'];
                }else{
                    $p['denda'] = 0;
                }
                $p['total'] = $p['cicilan']+ $p['bunga_nominal'] + $p['simpanan_wajib'] + $p['denda'];
                array_push($return, $p);
            }

            
        }
        return response()->json($return );
    }

    public function storemasal(Request $request){
        if (!isset($_POST['id_pinjaman'])) {
          return redirect()->route('angsuran.index');
        }
        $rows = count($_POST['id_pinjaman']);

        for ($i=0; $i < $rows ; $i++) { 
            $data = [
                [
                    'no_transaksi' =>  "ANGS".date("dmY").sprintf("%07d", Angsuran::count('id') + 1 ),
                    'tanggal_transaksi' => date('Y-m-d'),
                    'id_pinjaman' => $_POST['id_pinjaman'][$i] ,
                    'id_anggota' => $_POST['id_anggota'][$i] ,
                    'pokok'      => $_POST['pokok'][$i] ,
                    'bunga'      => $_POST['bunga'][$i] ,
                    'simpanan_wajib' => $_POST['simpanan_wajib'][$i] ,
                    'denda'           => $_POST['denda'][$i] ,
                    'angsuran_ke'     => $_POST['angsuran_ke'][$i] ,
                    'total'           => $_POST['total'][$i]  ,
                    'id_proyeksi'     => $_POST['id_proyeksi'][$i] ,    
                    'status'          => 0,
                    'created_by'      => auth()->user()->id,
                    'created_at'      => date('Y-m-d'),
                    'updated_at'      =>  date('Y-m-d'),
                ]
            ];

            Angsuran::insert($data);
        }

        Session::flash(
            "flash_notification",
            [
                'level' => 'success',
                "icon" => "fa fa-check",
                'message' => 'Berhasil melakukan insert angsuran sebanyak '.$rows,
            ]
        );


        return redirect()->route('angsuran.index');


    }


}
