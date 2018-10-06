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
            $angsuran->approve_by = auth()->user()->id;
           
            $proyeksi = ProyeksiAngsuran::find($angsuran->id_proyeksi);

            $sum_angsuran = Angsuran::where('id_proyeksi',$angsuran->id_proyeksi)->sum('pokok');
            $sum_bunga =  Angsuran::where('id_proyeksi',$angsuran->id_proyeksi)->sum('bunga');

            if ($sum_angsuran >= $proyeksi->cicilan && $sum_bunga >= $proyeksi->bunga_nominal  ) {
              $proyeksi->status = 1; //Lunas
              $angsuran->status = 1;
            }else if($sum_angsuran >= $proyeksi->cicilan &&   $sum_bunga < $proyeksi->bunga_nominal  ){
              $proyeksi->status = 2; //Pokok Saja
              $angsuran->status = 2;
            }else if( $sum_angsuran < $proyeksi->cicilan   && $sum_bunga >= $proyeksi->bunga_nominal  ){
               $proyeksi->status = 3; //Bunga Saja
               $angsuran->status = 3;
            }
            $angsuran->save();
            $proyeksi->save();

            if ($angsuran->peminjaman->tenor == $angsuran->angsuran_ke && $proyeksi->status == 1 ) {
                $peminjaman = Peminjaman::find($angsuran->id_pinjaman);
                $peminjaman->status = 2;
                $peminjaman->save();
            }

            if ($angsuran->status>0) {
              $this->insertJournal($angsuran);
              $this->insertSimpanan($angsuran);
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
        if ($angsuran_old->status > 0) {
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

            $q = '(select pa.* ,DATE_FORMAT(tanggal_proyeksi,"%d-%m-%Y" ) as tgl_proyeksi
                  from proyeksi_angsuran pa
                  where pa.peminjaman_id = '.$id_pinjaman.'
                  and pa.status != 1
                  and pa.tanggal_proyeksi < "'.$tanggal_transaksi.'" 
                  )
                  UNION
                  (select pa.* ,DATE_FORMAT(tanggal_proyeksi,"%d-%m-%Y" ) as tgl_proyeksi
                  from proyeksi_angsuran pa
                  left join angsuran an on (pa.id = an.id_proyeksi)
                  where pa.peminjaman_id = '.$id_pinjaman.'
                  and pa.status = 0
                  and pa.tanggal_proyeksi < "'.$tanggal_transaksi.'" 
                  and an.id_proyeksi is null)
                  UNION
                  (select pa.* , DATE_FORMAT(tanggal_proyeksi,"%d-%m-%Y" ) as tgl_proyeksi
                  from proyeksi_angsuran pa
                  left join angsuran an on (pa.id = an.id_proyeksi)
                  where pa.peminjaman_id = '.$id_pinjaman.'
                  and pa.status = 0
                  and pa.tanggal_proyeksi >= "'.$tanggal_transaksi.'" 
                  and an.id_proyeksi is null
                  limit 1)
                ';
            $proyeksi = \DB::select($q);
            $pinjaman =\DB::select('select p.nominal,(p.nominal-sum(ifnull(a.pokok,0) )) as saldo, p.tenor, p.cicilan
                                    from peminjaman p  
                                    left join angsuran a on (p.id = a.id_pinjaman )
                                    where p.id ='.$id_pinjaman.'
                                    group by p.id');

            $angsuran = Angsuran::where('id_pinjaman',$id_pinjaman)->get();

            $return =[
              'proyeksi' => $proyeksi,
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

    public function viewdetailproyeksi(Request $request){
        if($request->ajax()){
            $id_proyeksi = $request->id_proyeksi;
             $tanggal_transaksi =  date("Y-m-d", strtotime($request->tanggal_transaksi) );
            if ($id_proyeksi<1) {
                return response()->json([]);
            }
            $proyeksi  = ProyeksiAngsuran::find($id_proyeksi)->toArray();
            $getProyeksiNextMonth = $this->helper->getNextMonth($proyeksi['tanggal_proyeksi']);

            $d1 = new \DateTime($proyeksi['tanggal_proyeksi']);
            $d2 = new \DateTime($tanggal_transaksi);

            $interval = $d2->diff($d1);
            $telat = $interval->format('%m');
            $telat_year = $interval->format('%y');

            $telat = $telat + ($telat_year*12) ;

            //\Log::info($d1->format('d-m-Y').' '.$d2->format('d-m-Y').' '.$getProyeksiNextMonth);

            if ($tanggal_transaksi>=$getProyeksiNextMonth) {
                $proyeksi['denda'] = (10/100*$proyeksi['cicilan'])*$telat ;
            }else{
                $proyeksi['denda'] = 0;
            }

            if ($proyeksi['status']==2) {
              $proyeksi['cicilan'] = 0;
              $proyeksi['simpanan_wajib'] = 0;
              $proyeksi['denda']  = 0;
            }else if ($proyeksi['status']==3) {
              $proyeksi['bunga_nominal'] = 0;
              $proyeksi['simpanan_wajib'] = 0;
              //$proyeksi['denda'] = 0;
            }


            return response()->json($proyeksi );
        }
    }

    private function insertJournal(Angsuran $angsuran){
        $anggota = Anggota::find($angsuran->id_anggota);
        try {
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

           
            

            ## bunga ###
            if ($angsuran->bunga>0) {
              $return = $this->helper->insertJournalHeader(
                0, $angsuran->tanggal_validasi_original,  $angsuran->bunga ,  $angsuran->bunga, 'Bunga Angsuran ke '.(int)$angsuran->angsuran_ke.', Pinjaman '.$anggota->nama.'( '.$anggota->nik.' ), No. Angsuran : '.$angsuran->no_transaksi.', Peminjaman:'.$angsuran->peminjaman->no_transaksi
              );

              $bunga_debit  = Settingcoa::where('transaksi','bunga_debit')->select('id_coa')->first();
              $bunga_credit =  Settingcoa::where('transaksi','bunga_credit')->select('id_coa')->first() ;

              $this->helper->insertJournalDetail($return, $bunga_debit->id_coa, $angsuran->bunga, 'D' );
              $this->helper->insertJournalDetail($return, $bunga_credit->id_coa, $angsuran->bunga, 'C' );
            }
            ###

            ## denda ###
            if ($angsuran->denda>0) {
              $return = $this->helper->insertJournalHeader(
                0, $angsuran->tanggal_validasi_original,  $angsuran->denda ,  $angsuran->denda, 'Denda Angsuran ke '.(int)$angsuran->angsuran_ke.', Pinjaman '.$anggota->nama.'( '.$anggota->nik.' ), No. Angsuran : '.$angsuran->no_transaksi.', Peminjaman:'.$angsuran->peminjaman->no_transaksi
              );

              $denda_debit  = Settingcoa::where('transaksi','denda_debit')->select('id_coa')->first();
              $denda_credit =  Settingcoa::where('transaksi','denda_credit')->select('id_coa')->first() ;

              $this->helper->insertJournalDetail($return, $denda_debit->id_coa, $angsuran->denda, 'D' );
              $this->helper->insertJournalDetail($return, $denda_credit->id_coa, $angsuran->denda, 'C' );
            }
            ###



            
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

            $simpanan->jurnal_id = $return;
            $simpanan->save();
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
            $tanggal = date("Y-m-d", strtotime($request->tanggal) ); 

            $q = 'select p.id, p.no_transaksi , p.id_anggota, 
                  CONCAT(a.nik,"-",a.nama) AS nama_lengkap,
                  pa.id as id_proyeksi, CONCAT("(",pa.angsuran_ke,")","", DATE_FORMAT(tanggal_proyeksi,"%d-%m-%Y" )) as label_pa,
                  pa.angsuran_ke , pa.cicilan, pa.bunga_nominal, pa.simpanan_wajib, pa.tanggal_proyeksi,  DATE_FORMAT(tanggal_proyeksi,"%d-%m-%Y" ) as tgl_proyeksi, p.nominal,  p.nominal - IFNULL((select sum(pokok) from angsuran where id_pinjaman = p.id ),0) as saldopinjaman, pa.status  
                  from peminjaman p
                  join anggota a on (p.id_anggota = a.id)
                  join proyeksi_angsuran pa on (pa.peminjaman_id = p.id)
                  where p.status = 1 and a.unit_kerja = '.$id_unit.'
                  and pa.status != 1
                  and pa.tanggal_proyeksi < "'.$tanggal.'"
                  UNION
                  select p.id, p.no_transaksi , p.id_anggota, 
                  CONCAT(a.nik,"-",a.nama) AS nama_lengkap,
                  pa.id as id_proyeksi, CONCAT("(",pa.angsuran_ke,")","", DATE_FORMAT(tanggal_proyeksi,"%d-%m-%Y" )) as label_pa,
                  pa.angsuran_ke , pa.cicilan, pa.bunga_nominal, pa.simpanan_wajib, pa.tanggal_proyeksi,  DATE_FORMAT(tanggal_proyeksi,"%d-%m-%Y" ) as tgl_proyeksi, p.nominal,  p.nominal - IFNULL((select sum(pokok) from angsuran where id_pinjaman = p.id ),0) as saldopinjaman , pa.status
                  from peminjaman p
                  join anggota a on (p.id_anggota = a.id)
                  join proyeksi_angsuran pa on (pa.peminjaman_id = p.id)
                  left join angsuran an on (pa.id = an.id_proyeksi)
                  where p.status = 1 and a.unit_kerja = '.$id_unit.'
                  and pa.status = 0
                  and pa.tanggal_proyeksi < "'.$tanggal.'"
                  and an.id_proyeksi is null
                  UNION
                  select p.id, p.no_transaksi , p.id_anggota, 
                  CONCAT(a.nik,"-",a.nama) AS nama_lengkap,
                  pa.id as id_proyeksi, CONCAT("(",pa.angsuran_ke,")","", DATE_FORMAT(tanggal_proyeksi,"%d-%m-%Y" )) as label_pa,
                  pa.angsuran_ke , pa.cicilan, pa.bunga_nominal, pa.simpanan_wajib, pa.tanggal_proyeksi,  DATE_FORMAT(tanggal_proyeksi,"%d-%m-%Y" ) as tgl_proyeksi, p.nominal,  p.nominal - IFNULL((select sum(pokok) from angsuran where id_pinjaman = p.id ),0) as saldopinjaman, pa.status
                  from peminjaman p
                  join anggota a on (p.id_anggota = a.id)
                  join proyeksi_angsuran pa on (pa.peminjaman_id = p.id)
                  left join angsuran an on (pa.id = an.id_proyeksi)
                  where p.status = 1 and a.unit_kerja = '.$id_unit.'
                  and pa.status = 0
                  and pa.tanggal_proyeksi >= "'.$from.'" and pa.tanggal_proyeksi <= "'.$to.'"
                  and an.id_proyeksi is null
                ';
            $peminjaman = \DB::select($q);
            foreach ($peminjaman as $p ) {
                $p = (array) $p;
                $getProyeksiNextMonth = $this->helper->getNextMonth($p['tanggal_proyeksi']);

                $d1 = new \DateTime($p['tanggal_proyeksi']);
                $d2 = new \DateTime($tanggal);

                $interval = $d2->diff($d1);
                $telat = $interval->format('%m');

                //\Log::info($telat.' '.$getProyeksiNextMonth);

                if ($tanggal>=$getProyeksiNextMonth) {
                    $p['denda'] = (10/100*$p['cicilan'])*$telat ;
                }else{
                    $p['denda'] = 0;
                }


              if ($p['status']==2) {
                $p['cicilan'] = 0;
                $p['simpanan_wajib'] = 0;
                $p['denda']  = 0;
              }else if ($p['status']==3) {
                $p['bunga_nominal'] = 0;
                $p['simpanan_wajib'] = 0;
                //$p['denda'] = 0;
              }

              $p['total'] = $p['cicilan']+ $p['bunga_nominal'] + $p['simpanan_wajib'] + $p['denda'];
              array_push($return, $p);
            }

            
        }
        return response()->json($return );
    }

    public function printmassal(Request $request){
            $return = [];
            $id_unit = $request->id_unit;
            $unit = \App\Model\Unit::find($id_unit);
            $from = date("Y-m-d", strtotime($request->from) );
            $to = date("Y-m-d", strtotime($request->to) ); 
            $tanggal = date("Y-m-d", strtotime($request->tanggal) ); 

            $q = 'select p.id, p.no_transaksi , p.id_anggota, 
                  CONCAT(a.nik,"-",a.nama) AS nama_lengkap,
                  pa.id as id_proyeksi, CONCAT("(",pa.angsuran_ke,")","", DATE_FORMAT(tanggal_proyeksi,"%d-%m-%Y" )) as label_pa,
                  pa.angsuran_ke , pa.cicilan, pa.bunga_nominal, pa.simpanan_wajib, pa.tanggal_proyeksi,  DATE_FORMAT(tanggal_proyeksi,"%d-%m-%Y" ) as tgl_proyeksi, p.nominal,  p.nominal - IFNULL((select sum(pokok) from angsuran where id_pinjaman = p.id ),0) as saldopinjaman, pa.status  
                  from peminjaman p
                  join anggota a on (p.id_anggota = a.id)
                  join proyeksi_angsuran pa on (pa.peminjaman_id = p.id)
                  where p.status = 1 and a.unit_kerja = '.$id_unit.'
                  and pa.status != 1
                  and pa.tanggal_proyeksi < "'.$tanggal.'"
                  UNION
                  select p.id, p.no_transaksi , p.id_anggota, 
                  CONCAT(a.nik,"-",a.nama) AS nama_lengkap,
                  pa.id as id_proyeksi, CONCAT("(",pa.angsuran_ke,")","", DATE_FORMAT(tanggal_proyeksi,"%d-%m-%Y" )) as label_pa,
                  pa.angsuran_ke , pa.cicilan, pa.bunga_nominal, pa.simpanan_wajib, pa.tanggal_proyeksi,  DATE_FORMAT(tanggal_proyeksi,"%d-%m-%Y" ) as tgl_proyeksi, p.nominal,  p.nominal - IFNULL((select sum(pokok) from angsuran where id_pinjaman = p.id ),0) as saldopinjaman , pa.status
                  from peminjaman p
                  join anggota a on (p.id_anggota = a.id)
                  join proyeksi_angsuran pa on (pa.peminjaman_id = p.id)
                  left join angsuran an on (pa.id = an.id_proyeksi)
                  where p.status = 1 and a.unit_kerja = '.$id_unit.'
                  and pa.status = 0
                  and pa.tanggal_proyeksi < "'.$tanggal.'"
                  and an.id_proyeksi is null
                  UNION
                  select p.id, p.no_transaksi , p.id_anggota, 
                  CONCAT(a.nik,"-",a.nama) AS nama_lengkap,
                  pa.id as id_proyeksi, CONCAT("(",pa.angsuran_ke,")","", DATE_FORMAT(tanggal_proyeksi,"%d-%m-%Y" )) as label_pa,
                  pa.angsuran_ke , pa.cicilan, pa.bunga_nominal, pa.simpanan_wajib, pa.tanggal_proyeksi,  DATE_FORMAT(tanggal_proyeksi,"%d-%m-%Y" ) as tgl_proyeksi, p.nominal,  p.nominal - IFNULL((select sum(pokok) from angsuran where id_pinjaman = p.id ),0) as saldopinjaman, pa.status
                  from peminjaman p
                  join anggota a on (p.id_anggota = a.id)
                  join proyeksi_angsuran pa on (pa.peminjaman_id = p.id)
                  left join angsuran an on (pa.id = an.id_proyeksi)
                  where p.status = 1 and a.unit_kerja = '.$id_unit.'
                  and pa.status = 0
                  and pa.tanggal_proyeksi >= "'.$from.'" and pa.tanggal_proyeksi <= "'.$to.'"
                  and an.id_proyeksi is null
                ';
            $peminjaman = \DB::select($q);
            foreach ($peminjaman as $p ) {
                $p = (array) $p;
                $getProyeksiNextMonth = $this->helper->getNextMonth($p['tanggal_proyeksi']);

                $d1 = new \DateTime($p['tanggal_proyeksi']);
                $d2 = new \DateTime($tanggal);

                $interval = $d2->diff($d1);
                $telat = $interval->format('%m');

                //\Log::info($telat.' '.$getProyeksiNextMonth);

                if ($tanggal>=$getProyeksiNextMonth) {
                    $p['denda'] = (10/100*$p['cicilan'])*$telat ;
                }else{
                    $p['denda'] = 0;
                }


              if ($p['status']==2) {
                $p['cicilan'] = 0;
                $p['simpanan_wajib'] = 0;
                $p['denda']  = 0;
              }else if ($p['status']==3) {
                $p['bunga_nominal'] = 0;
                $p['simpanan_wajib'] = 0;
                //$p['denda'] = 0;
              }

              $p['total'] = $p['cicilan']+ $p['bunga_nominal'] + $p['simpanan_wajib'] + $p['denda'];
              array_push($return, $p);
            }

            //return response()->json($return );

            // return view('admin.pdf.neracabanding',compact('coa_asset', 'coa_l', 'coa_e', 'bulan_from', 'tahun_from', 'bulan_to', 'tahun_to', 'profit1', 'profit2'));
            return view('admin.angsuran.printmassal',compact('return', 'from','to', 'unit'));
            
    }



    public function storemasal(Request $request){
        if (!isset($_POST['id_pinjaman'])) {
          return redirect()->route('angsuran.index');
        }
        $rows = count($_POST['id_pinjaman']);

        $tanggal = date("Y-m-d", strtotime($request->tanggal) ); 
        for ($i=0; $i < $rows ; $i++) { 
            $data = [
                [
                    'no_transaksi' =>   \App\Helpers\Common::getNoTransaksi('angsuran'),
                    'tanggal_transaksi' => $tanggal,
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
                    'created_at'      => $tanggal,
                    'updated_at'      => $tanggal,
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
