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
use App\Model\Anggota;
use App\Model\Department;
use App\Model\Unit;

use Session;

class ReportPerhitunganShuDibagi extends Controller
{
    //
    protected $helper;

	public function __construct(\App\Helpers\Common $helper){
		$this->helper = $helper;
	}

	public function index(){
		$bulan_option = $this->helper->getBulanOption();
		$tahun_option = $this->helper->getTahunOption();
		return view('admin.reportshudibagi.export', compact('bulan_option','tahun_option'));
	}

	public function post(Request $request){
		$tahun = (int) $request->tahun;
		$bulan = date('m');
		$prev_year = $tahun - 1;
		$bulan_option = $this->helper->getBulanOptionInt();


		
		
	}
}
