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

        $jumlah_pinjaman =  \App\Model\Peminjaman::where('status','>=',1)
                                                  ->where('tanggal_disetujui','<', date($tahun.'-'.$bulan.'-01'))
                                                  ->sum('nominal');
        $angsuran = \App\Model\Angsuran::where('tanggal_validasi','<', date($tahun.'-'.$bulan.'-01'))
                                        ->where(function($q) {
                                              $q->where('status', 1)
                                                ->orWhere('status', 2);
                                          })
                                        ->sum('pokok');
        
        $saldo_awal = $jumlah_pinjaman - $angsuran;

	    $q = 'select t.tanggal,
	    		ifnull(x.nominal_pinjaman,0) as c_nominal_pinjaman,
    			ifnull(y.pokok,0) as d_pokok,
    			ifnull(y.bunga,0) as d_bunga,
    			ifnull(y.denda,0) as d_denda,
    			ifnull(pin.nominal,0) as d_pinalti,
    			ifnull(sw.nominal,0) as d_simpanan_wajib,
    			ifnull(sp.nominal,0) as d_simpanan_pokok,
    			ifnull(ss.nominal,0) as d_simpanan_sukarela,
    			ifnull(tp.nominal,0) as c_penarikan_pokok,
    			ifnull(tw.nominal,0) as c_penarikan_wajib,
    			ifnull(ts.nominal,0) as c_penarikan_sukarela,
    			0  as c_shu,
    			0 as d_shu,
    			(ifnull(x.nominal_pinjaman,0) + ifnull(tp.nominal,0)+ifnull(tw.nominal,0)+ifnull(ts.nominal,0) + 0) as c_total ,
    			(ifnull(y.pokok,0)+ifnull(y.bunga,0)+ ifnull(y.denda,0)+ ifnull(sw.nominal,0) + ifnull(sp.nominal,0) + ifnull(ss.nominal,0) + ifnull(pin.nominal,0) ) as d_total
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
                left join(
                    select tanggal_validasi,sum(nominal) as nominal
                    from pinalti 
                    group by tanggal_validasi
                ) pin on (pin.tanggal_validasi = t.tanggal)
	    	  where true
	    	  ';


	    $rekap_bulanan = \DB::select($q);
	    $drop = \DB::select(\DB::raw("drop table tanggalan"));
    	if ($request->type == 'html') {
    			return view('admin.pdf.reportrekapbulan',compact('rekap_bulanan','bulan','tahun', 'saldo_awal'));
    	}

    	$handler = 'export' . ucfirst($request->get('type'));

        return $this->$handler($rekap_bulanan, $bulan, $tahun,$saldo_awal);
    	
    }
    private function exportPdf($rekap_bulanan, $bulan, $tahun,$saldo_awal)
    {
       $pdf = PDF::loadview('admin.pdf.reportrekapbulan', compact('rekap_bulanan','bulan','tahun','saldo_awal'))->setPaper('A3', 'landscape');

       return $pdf->download('reportrekapbulanan'.date('Y-m-d H:i:s').'.pdf');
    }


    public function tanggal(Request $request){
        $tanggal = $request->tanggal;
        $saldo_awal   = $request->saldo;
    
        $q =    'select a.id as id_anggota, a.nia, a.nik , a.nama, u.name as unit, d.name as departemen,
                ifnull(x.nominal_pinjaman,0) as c_nominal_pinjaman,
                ifnull(y.pokok,0) as d_pokok,
                ifnull(y.bunga,0) as d_bunga,
                ifnull(y.denda,0) as d_denda,
                ifnull(pin.nominal,0) as d_pinalti,
                ifnull(sw.nominal,0) as d_simpanan_wajib,
                ifnull(sp.nominal,0) as d_simpanan_pokok,
                ifnull(ss.nominal,0) as d_simpanan_sukarela,
                ifnull(tp.nominal,0) as c_penarikan_pokok,
                ifnull(tw.nominal,0) as c_penarikan_wajib,
                ifnull(ts.nominal,0) as c_penarikan_sukarela,
                0  as c_shu,
                0 as d_shu,
                (ifnull(x.nominal_pinjaman,0) + ifnull(tp.nominal,0)+ifnull(tw.nominal,0)+ifnull(ts.nominal,0) + 0) as c_total ,
                (ifnull(y.pokok,0)+ifnull(y.bunga,0)+ ifnull(y.denda,0)+ ifnull(sw.nominal,0) + ifnull(sp.nominal,0) + ifnull(ss.nominal,0) + ifnull(pin.nominal,0)) as d_total
                from anggota a
                join units u on (a.unit_kerja = u.id)
                join departments d on (u.department_id = d.id)
                left join (
                    select a.id,sum(nominal) as nominal_pinjaman
                    from peminjaman p
                    join anggota a on (a.id = p.id_anggota)
                    where
                    tanggal_disetujui = "'.$tanggal.'"
                ) x  on (x.id = a.id)
                left join(
                    select a.id,sum(pokok) as pokok, sum(bunga) as bunga, sum(simpanan_wajib) as simpanan_wajib, sum(denda) as denda
                    from angsuran an
                    join anggota a on (a.id = an.id_anggota)
                    where tanggal_validasi = "'.$tanggal.'"
                ) y on (y.id = a.id)
                left join(
                    SELECT  a.id,sum(nominal) as nominal
                    FROM simpanan s
                    JOIN `jenis_simpanan` j ON (s.`id_simpanan` = j.id)
                    join anggota a on (a.id = s.id_anggota)
                    WHERE j.`nama_simpanan` like "%simpanan wajib%"
                    and s.tanggal_transaksi = "'.$tanggal.'"
                ) sw on (sw.id = a.id)
                left join(
                    SELECT  a.id,sum(nominal) as nominal
                    FROM simpanan s
                    JOIN `jenis_simpanan` j ON (s.`id_simpanan` = j.id)
                    join anggota a on (a.id = s.id_anggota)
                    WHERE j.`nama_simpanan` like "%simpanan pokok%"
                    and s.tanggal_transaksi = "'.$tanggal.'"
                ) sp on (sp.id = a.id)
                left join(
                    SELECT  a.id,sum(nominal) as nominal
                    FROM simpanan s
                    JOIN `jenis_simpanan` j ON (s.`id_simpanan` = j.id)
                    join anggota a on (a.id = s.id_anggota)
                    WHERE j.`nama_simpanan` like "%simpanan sukarela%"
                    and s.tanggal_transaksi = "'.$tanggal.'"
                ) ss on (ss.id = a.id)
                left join(
                    SELECT  a.id,sum(pen.nominal) as nominal
                    FROM penarikan pen
                    JOIN `jenis_simpanan` j ON (pen.`id_simpanan` = j.id)
                    join anggota a on (a.id = pen.id_anggota)
                    WHERE j.`nama_simpanan` like "%simpanan pokok%"
                    and pen.tanggal_transaksi = "'.$tanggal.'"
                ) tp on (tp.id = a.id)
                left join(
                    SELECT  a.id,sum(pen.nominal) as nominal
                    FROM penarikan pen
                    JOIN `jenis_simpanan` j ON (pen.`id_simpanan` = j.id)
                    join anggota a on (a.id = pen.id_anggota)
                    WHERE j.`nama_simpanan` like "%simpanan wajib%"
                    and pen.tanggal_transaksi = "'.$tanggal.'"
                ) tw on (tw.id = a.id)
                left join(
                    SELECT  a.id,sum(pen.nominal) as nominal
                    FROM penarikan pen
                    JOIN `jenis_simpanan` j ON (pen.`id_simpanan` = j.id)
                    join anggota a on (a.id = pen.id_anggota)
                    WHERE j.`nama_simpanan` like "%simpanan sukarela%"
                    and pen.tanggal_transaksi = "'.$tanggal.'"
                ) ts on (ts.id = a.id)
                left join(
                    select a.id,sum(p.nominal) as nominal
                    from pinalti p
                    join anggota a on (a.id = p.id_anggota)
                    where p.tanggal_validasi = "'.$tanggal.'"
                ) pin on (pin.id = a.id)
                where (x.nominal_pinjaman> 0
                      or y.pokok > 0
                      or y.bunga > 0
                      or y.denda > 0
                      or sw.nominal > 0
                      or sp.nominal > 0
                      or tp.nominal > 0
                      or tw.nominal > 0
                      or ts.nominal > 0
                      )  
                order by d.id, a.unit_kerja, a.id
            ';
            
            $rekapbulantanggal = \DB::select($q);
            foreach ($rekapbulantanggal as $r) {
                $jumlah_pinjaman =  \App\Model\Peminjaman::where('status','>=',1)
                                                  ->where('tanggal_disetujui','<', $tanggal)
                                                  ->where('id_anggota', $r->id_anggota  )
                                                  ->sum('nominal');
                $angsuran = \App\Model\Angsuran::where('tanggal_validasi','<', $tanggal)
                                                ->where(function($q) {
                                                      $q->where('status', 1)
                                                        ->orWhere('status', 2);
                                                  })
                                                ->where('id_anggota', $r->id_anggota )
                                                ->sum('pokok');
                
                $r->saldo_awal = $jumlah_pinjaman - $angsuran;
            }

           
            return view('admin.pdf.reportrekapbulantanggal',compact('tanggal','saldo_awal','rekapbulantanggal'));
    }
}
