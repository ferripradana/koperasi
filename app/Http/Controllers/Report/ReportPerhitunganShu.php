<?php

namespace App\Http\Controllers\Report;

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

use Session;

class ReportPerhitunganShu extends Controller
{
    //
    protected $helper;

	public function __construct(\App\Helpers\Common $helper){
		$this->helper = $helper;
	}

	public function index(){
		$bulan_option = $this->helper->getBulanOption();
		$tahun_option = $this->helper->getTahunOption();
		return view('admin.reportperhitunganshu.export', compact('bulan_option','tahun_option'));
	}

	public function post(Request $request){
		$tahun = (int) $request->tahun;
		$bulan = date('m');
		$prev_year = $tahun - 1;
		$bulan_option = $this->helper->getBulanOptionInt();
		

		$modal_edy_id  = JenisTransaksi::where('nama_transaksi','like','%edy%' )->first();
		$modal_gamal_id = JenisTransaksi::where('nama_transaksi','like','%gamal%')->first();
		

		$modal[$prev_year]['gamal'] =  Transaksi::where('id_jenis_transaksi',$modal_gamal_id->id)
												->where('tanggal','<=', $prev_year.'-12-31')
												->sum('nominal');
		$modal[$prev_year]['edy'] =  Transaksi::where('id_jenis_transaksi', $modal_edy_id->id)
												->where('tanggal','<=', $prev_year.'-12-31')
												->sum('nominal');
		$modal[$prev_year]['anggota'] =  Simpanan::where('tanggal_transaksi','<=', $prev_year.'-12-31')
												   ->sum('nominal') - 
									     Penarikan::where('tanggal_transaksi','<=', $prev_year.'-12-31')
												   ->sum('nominal')
												   ;
		$total = [
			'gamal' => $modal[$prev_year]['gamal'],
			'edy'	=> $modal[$prev_year]['edy'],
			'anggota' => $modal[$prev_year]['anggota'],
		];

		$bunga_credit   =  Settingcoa::where('transaksi','bunga_credit')->select('id_coa')->first();
		$denda_credit   =  Settingcoa::where('transaksi','denda_credit')->select('id_coa')->first();
		$pinalti_credit =  Settingcoa::where('transaksi','pinalti_credit')->select('id_coa')->first();

		$laba_total = \DB::table('jurnal_detail as d')
					->join('jurnal_header as h', 'd.jurnal_header_id', '=', 'h.id')
					->whereRaw('YEAR(h.tanggal) = "'.$tahun.'"')
					->whereIn('d.coa_id',[$bunga_credit->id_coa , $denda_credit->id_coa, $pinalti_credit->id_coa ] )
					->sum('d.amount');

		$laba_rata = $laba_total/$bulan;    					

		$akumulasi = [];
		for($i=1 ; $i<= (int) $bulan; $i++ ) {
			$modal_gamal = Transaksi::where('id_jenis_transaksi',$modal_gamal_id->id)
												->where('tanggal','<=', $tahun.'-'.$i.'-31')
												->where('tanggal','>=', $tahun.'-'.$i.'-01')
												->sum('nominal');
			$modal_edy = Transaksi::where('id_jenis_transaksi', $modal_edy_id->id)
												->where('tanggal','<=', $tahun.'-'.$i.'-31')
												->where('tanggal','>=', $tahun.'-'.$i.'-01')
												->sum('nominal');
			$modal_anggota = Simpanan::where('tanggal_transaksi','<=', $tahun.'-'.$i.'-31')
										  ->where('tanggal_transaksi','>=', $tahun.'-'.$i.'-01')	
										  ->sum('nominal') - 
									     Penarikan::where('tanggal_transaksi','<=', $tahun.'-'.$i.'-31')
										  		   ->where('tanggal_transaksi','>=', $tahun.'-'.$i.'-01')
												   ->sum('nominal');
			$total['gamal']+=$modal_gamal;	
			$total['edy']+=$modal_edy;	
			$total['anggota']+=$modal_anggota;	
			$jumlah_modal =  $total['gamal']+  $total['edy'] +  $total['anggota'];	

			$andil_gamal = $jumlah_modal>0 ? sprintf ( '%.3f',$total['gamal']/$jumlah_modal*100):0;	
			$andil_edy = $jumlah_modal>0 ? sprintf ('%.3f', $total['edy']/$jumlah_modal*100):0;
			$andil_anggota = $jumlah_modal>0 ? sprintf ('%.3f', $total['anggota']/$jumlah_modal*100 ):0;

			$akumulasi[$i] = [
				'bulan' => $bulan_option[$i],
				'modal' => [
					'gamal' => $modal_gamal  ,
					'edy'   => $modal_edy,
					'anggota' => $modal_anggota,
				],
				'akumulasi' => [
					'gamal' 	=> $total['gamal'],
					'edy'		=> $total['edy'],
					'anggota'	=> $total['anggota'],
				],
				'jumlah_modal' => $jumlah_modal,
				'andil' => [
					'gamal' 	=> $andil_gamal,
					'edy'		=> $andil_edy,
					'anggota'	=> $andil_anggota,
				],
				'laba_rata' => $laba_rata,
				'shu' => [
					'gamal' => $andil_gamal * $laba_rata/100 ,
					'edy'	=>  $andil_edy * $laba_rata/100 ,
					'anggota' => $andil_anggota * $laba_rata/100,
				]
			]; 
		}


		if ($request->type == 'html') {
    			return view('admin.pdf.reportperhitunganshu',compact('modal', 'total', 'laba_total','akumulasi',
    				'bulan', 'tahun', 'prev_year', 'laba_rata', 'modal'));
    	}												

	}

}
