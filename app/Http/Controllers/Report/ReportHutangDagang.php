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

use Session;

class ReportHutangDagang extends Controller
{
    //

	protected $helper;

	public function __construct(\App\Helpers\Common $helper){
		$this->helper = $helper;
	}

	public function index(){
    	$bulan_option = $this->helper->getBulanOption();
    	$tahun_option = $this->helper->getTahunOption();
    	return view('admin.reporthutangdatang.export', compact('bulan_option','tahun_option'));
    }

    public function post(Request $request){

    	$bulan_from = (int) $request->bulan_from;
    	$tahun_from = (int) $request->tahun_from;
    	$last_year = $tahun_from-1;

    	$where = ' and month(tanggal)='.$bulan_from.' and year(tanggal)='.$tahun_from;
    	$id_unit = (int) $request->id_unit;

    	$unit_name = 'ALL';

    	if ($id_unit>0) {
    		$whereunit = ' and id_unit='.$id_unit;
    		$where .= $whereunit;

    		$unit   = \App\Model\Unit::find($id_unit);
    		$unit_name = $unit->name;
    	}else{
    		$id_unit = 0;
    	}

    	$q = '
    	SELECT s.id,s.nama,s.status , pbl.nominal as pembelian, pby.nominal as pembayaran, ret.nominal as retur,tat.saldo as saldo_akhir_tahun
    	FROM supplier s
    	LEFT JOIN (
    		select sum(nominal) as nominal,id_supplier
    		from transaksi_unit
    		where type in (3,4)
    		'.$where.'
    		group by  id_supplier
    	) as pbl on (pbl.id_supplier=s.id ) 
    	LEFT JOIN (
    		select sum(nominal) as nominal,id_supplier
    		from transaksi_unit
    		where type in (3,5)
    		'.$where.'
    		group by  id_supplier
    	) as pby on (pby.id_supplier=s.id )
    	LEFT JOIN (
    		select sum(nominal) as nominal,id_supplier
    		from transaksi_unit
    		where type in (6,7)
    		'.$where.'
    		group by  id_supplier
    	) as ret on (ret.id_supplier=s.id)
    	LEFT JOIN (
    		SELECT s2.id, (pbl2.nominal-pby2.nominal-ret2.nominal) as saldo 
	    	FROM supplier s2
	    	LEFT JOIN (
	    		select sum(nominal) as nominal,id_supplier
	    		from transaksi_unit
	    		where type in (3,4)
	    		and tanggal < "'.$last_year.'-12-31"
	    		group by  id_supplier
	    	) as pbl2 on (pbl2.id_supplier=s2.id ) 
	    	LEFT JOIN (
	    		select sum(nominal) as nominal,id_supplier
	    		from transaksi_unit
	    		where type in (3,5)
	    		and tanggal < "'.$last_year.'-12-31"
	    		group by  id_supplier
	    	) as pby2 on (pby2.id_supplier=s2.id )
	    	LEFT JOIN (
	    		select sum(nominal) as nominal,id_supplier
	    		from transaksi_unit
	    		where type in (6,7)
	    		and tanggal < "'.$last_year.'-12-31"
	    		group by  id_supplier
	    	) as ret2 on (ret2.id_supplier=s2.id)
    	) as tat on (tat.id = s.id)
    	where (pbl.nominal > 0 or pby.nominal>0 or ret.nominal>0 or tat.saldo!=0)
    	';

    	$hutangdagang = \DB::select($q);

    	return view('admin.pdf.hutangdagang',compact('bulan_from','tahun_from', 'hutangdagang', 'unit_name', 'last_year', 'id_unit'));
    }


    public function hdDetail(Request $request){
    	$type = $request->type;
    	$id_supplier = $request->id_supplier;
    	$id_unit = $request->id_unit;
    	$bulan_from = $request->bulan_from;
    	$tahun_from = $request->tahun_from;

    	$where = '';
    	switch ($type) {
    		case 'pembelian':
    			$where = ' type in (3,4)';
    			break;
    		case 'pembayaran':
    			$where = ' type in (3,5)';
    			break;
    		case 'retur':
    			$where =  ' type in (6,7)';
    			break;
    		default:
    			break;
    	}

    	$where .= ' and month(tanggal) ='.$bulan_from. ' and year(tanggal) = '.$tahun_from;
    	$where .= ' and id_supplier='.$id_supplier;
    	if ($id_unit>0) {
    		$where .= ' and id_unit='.$id_supplier;
    	}
    	$q = '
    	select s.nama,u.name,t.tanggal,t.no_transaksi,t.nominal,t.keterangan
    	from transaksi_unit t
    	join supplier s on (t.id_supplier = s.id)
    	join units u on (t.id_unit = u.id)
    	where '.$where.'
    	order by s.nama asc, u.name asc , t.tanggal asc
    	';

    	$hddetail = \DB::select($q);

    	return view('admin.pdf.hddetail',compact('hddetail','bulan_from','tahun_from', 'id_unit', 'id_supplier','type' ));
    }

}
