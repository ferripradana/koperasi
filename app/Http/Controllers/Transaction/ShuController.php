<?php

namespace App\Http\Controllers\Transaction;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\File;
use App\Utilities\ImportFile;
use PDF;
use Excel;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;

use App\Model\Acc\CoaGroup;
use App\Model\Acc\Coa;
use App\Model\JenisTransaksi;
use App\Model\Transaksi;
use App\Model\Simpanan;
use App\Model\Penarikan;
use App\Model\Acc\JournalHeader;
use App\Model\Acc\JournalDetail;
use App\Model\Settingcoa;
use App\Model\Anggota;
use App\Model\Angsuran;
use App\Model\Department;
use App\Model\Unit;
use App\Model\Shu;

use Session;

class ShuController extends Controller
{

    protected $helper;

    public function __construct(\App\Helpers\Common $helper){
        $this->helper = $helper;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $bulan_option = $this->helper->getBulanOption();
        $tahun_option = $this->helper->getTahunOption();
        return view('admin.shu.index', compact('bulan_option','tahun_option'));
    }

    public function simpan(Request $request){
      if (isset($_POST['declare'])) {
        $this->declareSHU($request);
      }
      $rows = count($request->id_anggota);
      
      for ($i=0; $i < $rows ; $i++) { 
        if (!isset($request->chk[$i])) {
          continue;
        }

        $idx = $request->chk[$i];

        if (!isset($request->shu_tak_diambil[$idx]) or !isset($request->shu_diambil[$idx]) ) {
          continue;
        }

        if ($request->shu_tak_diambil[$idx]<1 && $request->shu_diambil[$idx]<1 ) {
          continue;
        }
        $shu = Shu::create([
                        'id_anggota' => $request->id_anggota[$idx] , 
                        'bulan' => $request->bulan, 
                        'tahun' => $request->tahun, 
                        'tiga_puluh' => $request->tigapuluh_shu[$idx], 
                        'tidak_diambil' => $request->shu_tak_diambil[$idx] , 
                        'diambil' => $request->shu_diambil[$idx] ,
                        'tujuh_puluh' => $request->shu_70[$idx] ,
                ]);
        $this->processingAfterSHU($shu, $request );

      }
      

      Session::flash(
            "flash_notification",
            [
                'level' => 'success',
                "icon" => "fa fa-check",
                'message' => 'Berhasil',
            ]
      );


      return redirect()->route('shu.index');

    }


    private function processingAfterSHU($shu, $request){
      $this->helper->createSimpananSukarela($shu->tujuh_puluh, $shu->id_anggota);
      $anggota = Anggota::find($shu->id_anggota);

      $return = $this->helper->insertJournalHeader(
                0, date('Y-m-d'),  $shu->diambil ,  $shu->diambil, 'PAY SHU Anggota ' . $anggota->nama . $request->bulan . '-' . $request->tahun
              );

      $shu_pay_debit  = Settingcoa::where('transaksi','shu_pay_debit')->select('id_coa')->first();
      $shu_pay_credit =  Settingcoa::where('transaksi','shu_pay_credit')->select('id_coa')->first() ;

      $this->helper->insertJournalDetail($return, $shu_pay_debit->id_coa, $shu->diambil, 'D' );
      $this->helper->insertJournalDetail($return, $shu_pay_credit->id_coa, $shu->diambil, 'C' );
    
    } 


    private function declareSHU($request){
      $amount = str_replace(',','', $request->gt_tigapuluh_shu);

      $sqldel = "DELETE h,d
                 FROM jurnal_header h
                 INNER JOIN jurnal_detail d ON h.id=d.jurnal_header_id
                 WHERE h.narasi = 'Deklarasi SHU Anggota " . $request->bulan . "-" . $request->tahun ."' ";
      $delete = \DB::delete($sqldel);

      $return = $this->helper->insertJournalHeader(
                0, date('Y-m-d'),  $amount ,  $amount, 'Deklarasi SHU Anggota ' . $request->bulan . '-' . $request->tahun
              );

      $shu_declare_debit  = Settingcoa::where('transaksi','shu_declare_debit')->select('id_coa')->first();
      $shu_declare_credit =  Settingcoa::where('transaksi','shu_declare_credit')->select('id_coa')->first() ;

      $this->helper->insertJournalDetail($return, $shu_declare_debit->id_coa, $amount, 'D' );
      $this->helper->insertJournalDetail($return, $shu_declare_credit->id_coa, $amount, 'C' );
    }


    public function create(Request $request){
        $tahun = (int) $request->tahun;
        $bulan = date('m');
        $prev_year = $tahun - 1;
        $bulan_option = $this->helper->getBulanOptionInt();


        $q1 = 'select sum(d. amount) as amount
                   from jurnal_header h
                   join jurnal_detail d on (h.id = d.jurnal_header_id)
                   join coa c on (d.coa_id = c.id)
                   where c.group_id = 4
                   and year(tanggal) = "'.$tahun.'"';
        $result_laba =  \DB::select($q1);       


        $q2 = 'select sum(d. amount) as amount
                   from jurnal_header h
                   join jurnal_detail d on (h.id = d.jurnal_header_id)
                   join coa c on (d.coa_id = c.id)
                   where c.group_id = 5
                   and year(tanggal) = "'.$tahun.'"';
        $result_beban =  \DB::select($q2);     

        $laba_total = $result_laba[0]->amount - $result_beban[0]->amount;

        $laba_rata = $laba_total/$bulan; 

        $q_anggota = 'select d.name as departemen,  u.name as unit, j.nama_jabatan , a.id as id_anggota, a.nik, a.nama , a.alamat, a.phone
              from departments d 
              join units u  on (u.department_id = d.id)
              join anggota a on (a.unit_kerja = u.id)
              join jabatan j on (a.`jabatan` = j.id) 
              order by d.name, u.name, j.nama_jabatan , a.nik, a.nama
        ';

        $result_anggota = \DB::select($q_anggota);
        $return = [];

        $modal_edy_id  = JenisTransaksi::where('nama_transaksi','like','%Modal Edy Sulistyanto bp%' )->first();
        $modal_gamal_id = JenisTransaksi::where('nama_transaksi','like','%Modal Gamal Haryo Putro bp%')->first();

        $penarikan_edy_id = JenisTransaksi::where('nama_transaksi','like','%penarikan modal pak Edy%' )->first();
        $penarikan_gamal_id = JenisTransaksi::where('nama_transaksi','like','%penarikan Modal pak Gamal%' )->first();

        $modal = [];
        for ($j=1; $j <= (int) $bulan ; $j++) { 
            $modal_gamal = Transaksi::where('id_jenis_transaksi',$modal_gamal_id->id)
                                                ->where('tanggal','<=', $tahun.'-'.$j.'-31')
                                                ->sum('nominal')
                          -
                          Transaksi::where('id_jenis_transaksi',$penarikan_gamal_id->id)
                                                ->where('tanggal','<=', $tahun.'-'.$j.'-31')
                                                ->sum('nominal')
                          ;
            $modal_edy = Transaksi::where('id_jenis_transaksi', $modal_edy_id->id)
                                                ->where('tanggal','<=', $tahun.'-'.$j.'-31')
                                                ->sum('nominal')
                          -
                          Transaksi::where('id_jenis_transaksi',$penarikan_edy_id->id)
                                                ->where('tanggal','<=', $tahun.'-'.$j.'-31')
                                                ->sum('nominal')
                          ;

            $modal_anggota = Simpanan::where('tanggal_transaksi','<=', $tahun.'-'.$j.'-31')
                                          ->sum('nominal') - 
                             Penarikan::where('tanggal_transaksi','<=', $tahun.'-'.$j.'-31')
                                          ->sum('nominal');
            $jumlah_modal =  $modal_gamal+  $modal_edy +  $modal_anggota;   

            $andil_gamal = $jumlah_modal>0 ? sprintf ( '%.3f',$modal_gamal/$jumlah_modal*100):0;    
            $andil_edy = $jumlah_modal>0 ? sprintf ('%.3f', $modal_edy /$jumlah_modal*100):0;
            $andil_anggota = $jumlah_modal>0 ? sprintf ('%.3f',$modal_anggota/$jumlah_modal*100 ):0;

            $total_angsuran = Angsuran::where('tanggal_validasi', '<=', $tahun.'-'.$j.'-31')
                                            ->where('tanggal_validasi', '>=', $tahun.'-'.$j.'-01')
                                            ->where('status','>',0)
                                            ->sum(\DB::raw('bunga + denda'));
            
            $modal[$j] =  [
                'gamal' => $modal_gamal,
                'edy'   => $modal_edy,
                'anggota' => $modal_anggota,
                'andil_gamal' => $andil_gamal,
                'andil_edy' => $andil_edy,
                'andil_anggota' => $andil_anggota,
                'total_angsuran' =>  !empty($total_angsuran) ? $total_angsuran:0,
            ];
        }

        foreach ($result_anggota as $anggota) { 

            $anggota->simpanan_pokok = 0;
            $anggota->simpanan_wajib = 0;
            $anggota->simpanan_sukarela = 0;
            $anggota->jumlah_simpanan = 0;
            $anggota->total_angsuran = 0;
            $anggota->shu_simpanan = 0;
            $anggota->shu_angsuran = 0;
            $anggota->jumlah_shu = 0;
            $anggota->tigapuluh_shu = 0;
            $anggota->akumulasi_shu = 0;
            
            for ($i=1; $i <= (int) $bulan ; $i++) { 
                $simpanan_pokok = Simpanan::where('tanggal_transaksi','<=', $tahun.'-'.$i.'-31')
                                                   ->where('id_simpanan',1)
                                                   ->where('id_anggota',$anggota->id_anggota)
                                                   ->sum('nominal') - 
                                         Penarikan::where('tanggal_transaksi','<=',$tahun.'-'.$i.'-31')
                                                   ->where('id_simpanan',1)
                                                   ->where('id_anggota',$anggota->id_anggota)
                                                   ->sum('nominal')
                                                   ;
                $simpanan_wajib = Simpanan::where('tanggal_transaksi','<=', $tahun.'-'.$i.'-31')
                                                       ->where('id_simpanan',2)
                                                       ->where('id_anggota',$anggota->id_anggota)
                                                       ->sum('nominal') - 
                                             Penarikan::where('tanggal_transaksi','<=',$tahun.'-'.$i.'-31')
                                                       ->where('id_simpanan',2)
                                                       ->where('id_anggota',$anggota->id_anggota)
                                                       ->sum('nominal')
                                                   ;
                $simpanan_sukarela  = Simpanan::where('tanggal_transaksi','<=', $tahun.'-'.$i.'-31')
                                                       ->where('id_simpanan',3)
                                                       ->where('id_anggota',$anggota->id_anggota)
                                                       ->sum('nominal') - 
                                             Penarikan::where('tanggal_transaksi','<=',$tahun.'-'.$i.'-31')
                                                       ->where('id_simpanan',3) 
                                                       ->where('id_anggota',$anggota->id_anggota)
                                                       ->sum('nominal')
                                                       ;    
                $total_angsuran = Angsuran::where('id_anggota',$anggota->id_anggota)
                                            ->where('tanggal_validasi', '<=', $tahun.'-'.$i.'-31')
                                            ->where('tanggal_validasi', '>=', $tahun.'-'.$i.'-01')
                                            ->where('status','>',0)
                                            ->sum(\DB::raw('bunga + denda'));

                $jumlah_simpanan = $simpanan_pokok+$simpanan_wajib+$simpanan_sukarela;


                $laba_perhitungan_shu = ($laba_rata * $modal[$i]['andil_anggota'] /100)/2;

                $tot_angs = !empty($total_angsuran) ?$total_angsuran:0;

                if ($modal[$i]['total_angsuran']>0 ) {
                    $shu_angsuran = ($tot_angs/$modal[$i]['total_angsuran'] ) * $laba_perhitungan_shu  ;
                }else{
                    $shu_angsuran = 0;
                }

                if ($modal[$i]['anggota']>0 ) {
                    $shu_simpanan = ($jumlah_simpanan/$modal[$i]['anggota'] ) * $laba_perhitungan_shu ;
                }else{
                    $shu_simpanan = 0;
                }

                $akumulasi_pre = 0;
                $jumlah_shu = $shu_simpanan+$shu_angsuran;
                $tigapuluh_shu = $jumlah_shu*30/100;
                $akumulasi_shu = $akumulasi_pre+($jumlah_shu-$tigapuluh_shu);

                $anggota->simpanan_pokok += $simpanan_pokok;
                $anggota->simpanan_wajib += $simpanan_wajib;
                $anggota->simpanan_sukarela += $simpanan_sukarela;
                $anggota->jumlah_simpanan += $jumlah_simpanan;
                $anggota->total_angsuran += $tot_angs  ;
                $anggota->shu_simpanan += $shu_simpanan;
                $anggota->shu_angsuran += $shu_angsuran;
                $anggota->jumlah_shu += $jumlah_shu;
                $anggota->tigapuluh_shu += $tigapuluh_shu;
                $anggota->akumulasi_shu += $akumulasi_shu;

            } 

            $isset_shu = Shu::where('id_anggota', $anggota->id_anggota)
                                  ->where('tahun',$tahun)->first();

            if ( isset($isset_shu->id ) && $isset_shu->id > 0 ) {
                  $anggota->telah_input = true;
                  $anggota->shu_diambil = $isset_shu->diambil;
                  $anggota->shu_tak_diambil = $isset_shu->tidak_diambil;
            }else{
              $anggota->telah_input = false;
            }
            $return[] =$anggota;    
        }


        if ($request->type == 'html') {
                return view('admin.shu.form',compact('return', 'bulan', 'tahun', 'bulan_option'));
        }       










    }

}
