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

use Session;

class ReportRekapBulanController extends Controller
{
    //
    protected $helper;

	public function __construct(\App\Helpers\Common $helper){
		$this->helper = $helper;
	}


    public function index(){
    	$bulan_option = $this->helper->getBulanOption();
    	$tahun_option = $this->helper->getTahunOption();
    	return view('admin.reportrekapbulan.export', compact('bulan_option','tahun_option'));
    }

    public function post(Request $request){
    	$bulan = $request->bulan;
    	$tahun = $request->tahun;

    	$create = \DB::select(\DB::raw("create table if not exists tanggalan (id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                            tanggal date )"));

		$date = $tahun.'-'.$bulan.'-01';
		$end =  $tahun.'-'.$bulan.'-'. date('t', strtotime($date)); //get end date of month
		while(strtotime($date) <= strtotime($end)) {
			\DB::select(\DB::raw("insert into tanggalan(tanggal) values ('".$date."') "));
	        $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
	    }

	    $q = 'select t.tanggal,
	    		ifnull(x.nominal_pinjaman,0) as c_nominal_pinjaman,
    			ifnull(y.pokok,0) as d_pokok,
    			ifnull(y.bunga,0) as d_bunga,
    			ifnull(y.denda,0) as d_denda,
    			0 as d_pinalti,
    			ifnull(sw.nominal,0) as d_simpanan_wajib,
    			ifnull(sp.nominal,0) as d_simpanan_pokok,
    			ifnull(ss.nominal,0) as d_simpanan_sukarela,
    			ifnull(tp.nominal,0) as c_penarikan_pokok,
    			ifnull(tw.nominal,0) as c_penarikan_wajib,
    			ifnull(ts.nominal,0) as c_penarikan_sukarela,
    			0  as c_shu,
    			0 as d_shu,
    			(ifnull(x.nominal_pinjaman,0) + ifnull(tp.nominal,0)+ifnull(tw.nominal,0)+ifnull(ts.nominal,0) + 0) as c_total ,
    			(ifnull(y.pokok,0)+ifnull(y.bunga,0)+ ifnull(y.denda,0)+ ifnull(sw.nominal,0) + ifnull(sp.nominal,0) + ifnull(ss.nominal,0) + 0) as d_total
	    	  from tanggalan t
	    	  left join (
    				select tanggal_disetujui,sum(nominal) as nominal_pinjaman
    				from peminjaman p
    				group by tanggal_disetujui
    			) x  on (x.tanggal_disetujui = t.tanggal)
    			left join(
    				select tanggal_validasi,sum(pokok) as pokok, sum(bunga) as bunga, sum(simpanan_wajib) as simpanan_wajib, sum(denda) as denda
    				from angsuran an
    				group by tanggal_validasi
    			) y on (y.tanggal_validasi = t.tanggal)
    			left join(
    				SELECT  tanggal_transaksi,sum(nominal) as nominal
					FROM simpanan s
					JOIN `jenis_simpanan` j ON (s.`id_simpanan` = j.id)
					WHERE j.`nama_simpanan` like "%simpanan wajib%"
    				group by tanggal_transaksi
    			) sw on (sw.tanggal_transaksi = t.tanggal)
    			left join(
    				SELECT  tanggal_transaksi,sum(nominal) as nominal
					FROM simpanan s
					JOIN `jenis_simpanan` j ON (s.`id_simpanan` = j.id)
					WHERE j.`nama_simpanan` like "%simpanan pokok%"
    				group by tanggal_transaksi
    			) sp on (sp.tanggal_transaksi = t.tanggal)
    			left join(
    				SELECT  tanggal_transaksi,sum(nominal) as nominal
					FROM simpanan s
					JOIN `jenis_simpanan` j ON (s.`id_simpanan` = j.id)
					WHERE j.`nama_simpanan` like "%simpanan sukarela%"
    				group by tanggal_transaksi
    			) ss on (ss.tanggal_transaksi = t.tanggal)
    			left join(
    				SELECT  tanggal_transaksi,sum(pen.nominal) as nominal
					FROM penarikan pen
					JOIN `jenis_simpanan` j ON (pen.`id_simpanan` = j.id)
					WHERE j.`nama_simpanan` like "%simpanan pokok%"
    				group by tanggal_transaksi
    			) tp on (tp.tanggal_transaksi = t.tanggal)
    			left join(
    				SELECT  tanggal_transaksi,sum(pen.nominal) as nominal
					FROM penarikan pen
					JOIN `jenis_simpanan` j ON (pen.`id_simpanan` = j.id)
					WHERE j.`nama_simpanan` like "%simpanan wajib%"
    				group by tanggal_transaksi
    			) tw on (tw.tanggal_transaksi = t.tanggal)
    			left join(
    				SELECT  tanggal_transaksi,sum(pen.nominal) as nominal
					FROM penarikan pen
					JOIN `jenis_simpanan` j ON (pen.`id_simpanan` = j.id)
					WHERE j.`nama_simpanan` like "%simpanan sukarela%"
    				group by tanggal_transaksi
    			) ts on (ts.tanggal_transaksi = t.tanggal)
	    	  where true
	    	  ';


	    $rekap_bulanan = \DB::select($q);
	    $drop = \DB::select(\DB::raw("drop table tanggalan"));
    	if ($request->type == 'html') {
    			return view('admin.pdf.reportrekapbulan',compact('rekap_bulanan','bulan','tahun'));
    	}

    	$handler = 'export' . ucfirst($request->get('type'));

        return $this->$handler($rekap_bulanan, $bulan, $tahun);
    	
    }
    private function exportPdf($rekap_bulanan, $bulan, $tahun)
    {
       $pdf = PDF::loadview('admin.pdf.reportrekapbulan', compact('rekap_bulanan','bulan','tahun'))->setPaper('A3', 'landscape');

       return $pdf->download('reportrekapbulanan'.date('Y-m-d H:i:s').'.pdf');
    }
}
