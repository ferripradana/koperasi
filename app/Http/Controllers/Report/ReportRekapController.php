<?php

namespace App\Http\Controllers\Report;

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

use Illuminate\Support\Facades\File;
use App\Utilities\ImportFile;
use PDF;
use Excel;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;

use Session;


class ReportRekapController extends Controller
{
    //
    public function index(){
    	return view('admin.reportrekap.export');
    }

    public function post(Request $request){
    	if ($request->tanggal_from != '' && $request->tanggal_to!='') {
    		$tanggal_from =date('Y-m-d', strtotime($request->tanggal_from) ); 
    		$tanggal_to = date('Y-m-d', strtotime($request->tanggal_to)) ;

    		$q = '
    			select d.name as departemen , u.name unit, u.id as unit_id,
    			ifnull(x.nominal_pinjaman,0) as c_nominal_pinjaman,
    			ifnull(y.pokok,0) as d_pokok,
    			ifnull(y.bunga,0) as d_bunga,
    			ifnull(y.denda,0) as d_denda,
    			ifnull(pin.nominal,0)  as d_pinalti,
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
    			from departments d
    			left join units u on (u.department_id = d.id)
    			left join (
    				select a.unit_kerja,sum(nominal) as nominal_pinjaman
    				from peminjaman p
    				join anggota a on (a.id = p.id_anggota)
    				where
    				tanggal_disetujui >= "'.$tanggal_from.'"
    				and tanggal_disetujui <=  "'.$tanggal_to.'"
    				group by unit_kerja
    			) x  on (x.unit_kerja = u.id)
    			left join(
    				select a.unit_kerja,sum(pokok) as pokok, sum(bunga) as bunga, sum(simpanan_wajib) as simpanan_wajib, sum(denda) as denda
    				from angsuran an
    				join anggota a on (a.id = an.id_anggota)
    				where tanggal_validasi >= "'.$tanggal_from.'"
    				and tanggal_validasi <=  "'.$tanggal_to.'"
    				group by unit_kerja
    			) y on (y.unit_kerja = u.id)
    			left join(
    				SELECT  a.unit_kerja,sum(nominal) as nominal
					FROM simpanan s
					JOIN `jenis_simpanan` j ON (s.`id_simpanan` = j.id)
					join anggota a on (a.id = s.id_anggota)
					WHERE j.`nama_simpanan` like "%simpanan wajib%"
					and s.tanggal_transaksi >= "'.$tanggal_from.'"
    				and s.tanggal_transaksi <=  "'.$tanggal_to.'"
    				group by unit_kerja
    			) sw on (sw.unit_kerja = u.id)
    			left join(
    				SELECT  a.unit_kerja,sum(nominal) as nominal
					FROM simpanan s
					JOIN `jenis_simpanan` j ON (s.`id_simpanan` = j.id)
					join anggota a on (a.id = s.id_anggota)
					WHERE j.`nama_simpanan` like "%simpanan pokok%"
					and s.tanggal_transaksi >= "'.$tanggal_from.'"
    				and s.tanggal_transaksi <=  "'.$tanggal_to.'"
    				group by unit_kerja
    			) sp on (sp.unit_kerja = u.id)
    			left join(
    				SELECT  a.unit_kerja,sum(nominal) as nominal
					FROM simpanan s
					JOIN `jenis_simpanan` j ON (s.`id_simpanan` = j.id)
					join anggota a on (a.id = s.id_anggota)
					WHERE j.`nama_simpanan` like "%simpanan sukarela%"
					and s.tanggal_transaksi >= "'.$tanggal_from.'"
    				and s.tanggal_transaksi <=  "'.$tanggal_to.'"
    				group by unit_kerja
    			) ss on (ss.unit_kerja = u.id)
    			left join(
    				SELECT  a.unit_kerja,sum(pen.nominal) as nominal
					FROM penarikan pen
					JOIN `jenis_simpanan` j ON (pen.`id_simpanan` = j.id)
					join anggota a on (a.id = pen.id_anggota)
					WHERE j.`nama_simpanan` like "%simpanan pokok%"
					and pen.tanggal_transaksi >= "'.$tanggal_from.'"
    				and pen.tanggal_transaksi <=  "'.$tanggal_to.'"
    				group by unit_kerja
    			) tp on (tp.unit_kerja = u.id)
    			left join(
    				SELECT  a.unit_kerja,sum(pen.nominal) as nominal
					FROM penarikan pen
					JOIN `jenis_simpanan` j ON (pen.`id_simpanan` = j.id)
					join anggota a on (a.id = pen.id_anggota)
					WHERE j.`nama_simpanan` like "%simpanan wajib%"
					and pen.tanggal_transaksi >= "'.$tanggal_from.'"
    				and pen.tanggal_transaksi <=  "'.$tanggal_to.'"
    				group by unit_kerja
    			) tw on (tw.unit_kerja = u.id)
    			left join(
    				SELECT  a.unit_kerja,sum(pen.nominal) as nominal
					FROM penarikan pen
					JOIN `jenis_simpanan` j ON (pen.`id_simpanan` = j.id)
					join anggota a on (a.id = pen.id_anggota)
					WHERE j.`nama_simpanan` like "%simpanan sukarela%"
					and pen.tanggal_transaksi >= "'.$tanggal_from.'"
    				and pen.tanggal_transaksi <=  "'.$tanggal_to.'"
    				group by unit_kerja
    			) ts on (ts.unit_kerja = u.id)
                left join(
                    select a.unit_kerja,sum(p.nominal) as nominal
                    from pinalti p
                    join anggota a on (a.id = p.id_anggota)
                    where p.tanggal_validasi >= "'.$tanggal_from.'"
                    and p.tanggal_validasi <=  "'.$tanggal_to.'"
                    group by a.unit_kerja
                ) pin on (pin.unit_kerja = u.id)
    			group by d.id, u.id
    		';

    		$rekap = \DB::select($q);
    		if ($request->type == 'html') {
    			return view('admin.pdf.reportrekap',compact('rekap','tanggal_from','tanggal_to'));
    		}

    		$handler = 'export' . ucfirst($request->get('type'));

        	return $this->$handler($rekap, $tanggal_from, $tanggal_to);
    		
    	}
    	
    }

    private function exportPdf($rekap, $tanggal_from, $tanggal_to)
    {
       $pdf = PDF::loadview('admin.pdf.reportrekap', compact('rekap','tanggal_from','tanggal_to'))->setPaper('A3', 'landscape');

       return $pdf->download('reportrekap'.date('Y-m-d H:i:s').'.pdf');
    }


    public function unit(Request $request){
        $unit_id = $request->unit_id;
        $from = $request->from;
        $to = $request->to;


           
        $q =    'select a.nia, a.nik , a.nama, u.name as unit,
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
                left join (
                    select a.id,sum(nominal) as nominal_pinjaman
                    from peminjaman p
                    join anggota a on (a.id = p.id_anggota)
                    where
                    tanggal_disetujui >= "'.$from.'"
                    and tanggal_disetujui <=  "'.$to.'"
                    group by a.id
                ) x  on (x.id = a.id)
                left join(
                    select a.id,sum(pokok) as pokok, sum(bunga) as bunga, sum(simpanan_wajib) as simpanan_wajib, sum(denda) as denda
                    from angsuran an
                    join anggota a on (a.id = an.id_anggota)
                    where tanggal_validasi >= "'.$from.'"
                    and tanggal_validasi <=  "'.$to.'"
                    group by a.id
                ) y on (y.id = a.id)
                left join(
                    SELECT  a.id,sum(nominal) as nominal
                    FROM simpanan s
                    JOIN `jenis_simpanan` j ON (s.`id_simpanan` = j.id)
                    join anggota a on (a.id = s.id_anggota)
                    WHERE j.`nama_simpanan` like "%simpanan wajib%"
                    and s.tanggal_transaksi >= "'.$from.'"
                    and s.tanggal_transaksi <=  "'.$to.'"
                    group by a.id
                ) sw on (sw.id = a.id)
                left join(
                    SELECT  a.id,sum(nominal) as nominal
                    FROM simpanan s
                    JOIN `jenis_simpanan` j ON (s.`id_simpanan` = j.id)
                    join anggota a on (a.id = s.id_anggota)
                    WHERE j.`nama_simpanan` like "%simpanan pokok%"
                    and s.tanggal_transaksi >= "'.$from.'"
                    and s.tanggal_transaksi <=  "'.$to.'"
                    group by a.id
                ) sp on (sp.id = a.id)
                left join(
                    SELECT  a.id,sum(nominal) as nominal
                    FROM simpanan s
                    JOIN `jenis_simpanan` j ON (s.`id_simpanan` = j.id)
                    join anggota a on (a.id = s.id_anggota)
                    WHERE j.`nama_simpanan` like "%simpanan sukarela%"
                    and s.tanggal_transaksi >= "'.$from.'"
                    and s.tanggal_transaksi <=  "'.$to.'"
                    group by a.id
                ) ss on (ss.id = a.id)
                left join(
                    SELECT  a.id,sum(pen.nominal) as nominal
                    FROM penarikan pen
                    JOIN `jenis_simpanan` j ON (pen.`id_simpanan` = j.id)
                    join anggota a on (a.id = pen.id_anggota)
                    WHERE j.`nama_simpanan` like "%simpanan pokok%"
                    and pen.tanggal_transaksi >= "'.$from.'"
                    and pen.tanggal_transaksi <=  "'.$to.'"
                    group by a.id
                ) tp on (tp.id = a.id)
                left join(
                    SELECT  a.id,sum(pen.nominal) as nominal
                    FROM penarikan pen
                    JOIN `jenis_simpanan` j ON (pen.`id_simpanan` = j.id)
                    join anggota a on (a.id = pen.id_anggota)
                    WHERE j.`nama_simpanan` like "%simpanan wajib%"
                    and pen.tanggal_transaksi >= "'.$from.'"
                    and pen.tanggal_transaksi <=  "'.$to.'"
                    group by a.id
                ) tw on (tw.id = a.id)
                left join(
                    SELECT  a.id,sum(pen.nominal) as nominal
                    FROM penarikan pen
                    JOIN `jenis_simpanan` j ON (pen.`id_simpanan` = j.id)
                    join anggota a on (a.id = pen.id_anggota)
                    WHERE j.`nama_simpanan` like "%simpanan sukarela%"
                    and pen.tanggal_transaksi >= "'.$from.'"
                    and pen.tanggal_transaksi <=  "'.$to.'"
                    group by a.id
                ) ts on (ts.id = a.id)
                left join(
                    select a.id, sum(p.nominal) as nominal
                    from pinalti p
                    join anggota a on (a.id = p.id_anggota)
                    where p.tanggal_validasi >= "'.$from.'"
                    and p.tanggal_validasi <=  "'.$to.'"
                    group by a.id
                ) pin on (pin.id = a.id)
                where a.unit_kerja = '.$unit_id.'
            ';

            $rekap = \DB::select($q);
            //echo '<pre>'; 
            //print_r($rekap);
            //echo '</pre>';
            return view('admin.pdf.reportrekapunit',compact('rekap','from','to'));
         
    }


}
