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

class ReportNeracaBandingUnit extends Controller
{
    //
    protected $helper;

	public function __construct(\App\Helpers\Common $helper){
		$this->helper = $helper;
	}

	public function index(){
    	$bulan_option = $this->helper->getBulanOption();
    	$tahun_option = $this->helper->getTahunOption();
    	return view('admin.reportneracabandingunit.export', compact('bulan_option','tahun_option'));
    }

    public function post(Request $request){
    	$bulan_from = (int) $request->bulan_from;
    	$tahun_from = (int) $request->tahun_from;
    	$id_unit = (int) $request->id_unit;

    	$bulan_to = (int) $request->bulan_to;
    	$tahun_to = (int) $request->tahun_to;

    	$unit   = \App\Model\Unit::find($id_unit);


    	$coa_asset = COA::where('group_id', 1 )->get();
    	$coa_asset = $this->createTree($coa_asset, $bulan_from, $tahun_from, $bulan_to , $tahun_to, $id_unit );

    	$coa_l = COA::where('group_id', array(2) )->get();
    	$coa_l = $this->createTree($coa_l, $bulan_from, $tahun_from, $bulan_to , $tahun_to, $id_unit);

    	$coa_e = COA::where('group_id', array(3) )->get();
    	$coa_e = $this->createTree($coa_e, $bulan_from, $tahun_from, $bulan_to , $tahun_to, $id_unit);

    	$coa_in = COA::where('group_id', array(4) )->get();
    	$coa_in = $this->createTree($coa_in, $bulan_from, $tahun_from, $bulan_to , $tahun_to, $id_unit);

    	$coa_ex = COA::where('group_id', array(5) )->get();
    	$coa_ex = $this->createTree($coa_ex, $bulan_from, $tahun_from, $bulan_to , $tahun_to, $id_unit);


    	$q1 = 'select sum(d. amount) as amount
	        	   from jurnal_header_unit h
	        	   join jurnal_detail_unit d on (h.id = d.jurnal_header_id)
	        	   join coa c on (d.coa_id = c.id)
	        	   where c.group_id = 4
	    	       and tanggal <= "'.$tahun_from.'-'.$bulan_from.'-31" and h.id_unit='.$id_unit;
	    $result_laba =  \DB::select($q1);       	       

	    $q2 = 'select sum(d. amount) as amount
	        	   from jurnal_header_unit h
	        	   join jurnal_detail_unit d on (h.id = d.jurnal_header_id)
	        	   join coa c on (d.coa_id = c.id)
	        	   where c.group_id = 5
	    	       and tanggal <= "'.$tahun_from.'-'.$bulan_from.'-31" and h.id_unit='.$id_unit; 	
	    $result_beban =  \DB::select($q2);      	              
        $profit1 = $result_laba[0]->amount - $result_beban[0]->amount;

        $q1 = 'select sum(d. amount) as amount
	        	   from jurnal_header_unit h
	        	   join jurnal_detail_unit d on (h.id = d.jurnal_header_id)
	        	   join coa c on (d.coa_id = c.id)
	        	   where c.group_id = 4
	    	       and tanggal <= "'.$tahun_to.'-'.$bulan_to.'-31" and h.id_unit='.$id_unit;
	    $result_laba =  \DB::select($q1);       	       

	    $q2 = 'select sum(d. amount) as amount
	        	   from jurnal_header_unit h
	        	   join jurnal_detail_unit d on (h.id = d.jurnal_header_id)
	        	   join coa c on (d.coa_id = c.id)
	        	   where c.group_id = 5
	    	       and tanggal <= "'.$tahun_to.'-'.$bulan_to.'-31" and h.id_unit='.$id_unit; 	
	    $result_beban =  \DB::select($q2);      	              
        $profit2 = $result_laba[0]->amount - $result_beban[0]->amount;


    	return view('admin.pdf.neracabandingunit',compact('coa_asset', 'coa_l', 'coa_e', 'bulan_from', 'tahun_from', 'bulan_to', 'tahun_to', 'profit1', 'profit2', 'coa_in', 'coa_ex', 'unit'));
    }

    private function createTree($coa, $bulan_from, $tahun_from, $bulan_to, $tahun_to, $id_unit){
    	$refs = array();
		$list = array();

		foreach ($coa as $data) {
	        $thisref = &$refs[ $data->id ];
	        $thisref['sect_parent'] = $data->parent_id;
			 $thisref['sect_name'] = $data->code." - ".$data->nama."&nbsp;&nbsp;<a href ='".route('coa.edit', $data->id)."'><i class='fa fa-pencil'></a></i>";
	        $thisref['sect_id'] = $data->id;
	        $thisref['code'] = $data->code;
	   
	        $q =   'select  SUM(CASE When dc="D" then amount else 0 End ) as debit, 
	        	   SUM(CASE When dc="C" then amount else 0 End ) as credit	
	        	   from jurnal_header_unit h
	        	   join jurnal_detail_unit d on (h.id = d.jurnal_header_id)
	        	   where d.coa_id in  (
	        	   	select id from coa where code like "'.$data->code.'%"
	        	   )
	    	       and tanggal <= "'.$tahun_from.'-'.$bulan_from.'-31"
	    	       and h.id_unit = '.$id_unit;

	    	$result =  \DB::select($q);       
	        if ($data->group_id == 1 or $data->group_id == 5 ) {
	        	$thisref['amount_from'] = (float) $result[0]->debit  - (float) $result[0]->credit ;
	        }else{
	        	$thisref['amount_from'] = (float) $result[0]->credit  - (float) $result[0]->debit ;
	        }

	        $q =   'select  SUM(CASE When dc="D" then amount else 0 End ) as debit, 
	        	   SUM(CASE When dc="C" then amount else 0 End ) as credit	
	        	   from jurnal_header_unit h
	        	   join jurnal_detail_unit d on (h.id = d.jurnal_header_id)
	        	   where d.coa_id in  (
	        	   	select id from coa where code like "'.$data->code.'%"
	        	   )
	    	       and tanggal <= "'.$tahun_to.'-'.$bulan_to.'-31"
	    	       and h.id_unit = '.$id_unit;

	    	$result =  \DB::select($q);       
	        if ($data->group_id == 1 or $data->group_id == 5 ) {
	        	$thisref['amount_to'] = (float) $result[0]->debit  - (float) $result[0]->credit ;
	        }else{
	        	$thisref['amount_to'] = (float) $result[0]->credit  - (float) $result[0]->debit ;
	        }

	        if ($thisref['amount_from'] == 0) {
	        	$thisref['persen'] = 0;
	        } else {
	        	$thisref['persen'] = (float) ($thisref['amount_from']-$thisref['amount_to'])/$thisref['amount_from'] * 100; 
	        }

	        if ($data->parent_id == 0) {
	            $list[ $data->id ] = &$thisref;
	        } else {
	            $refs[ $data->parent_id ]['children'][ $data->id  ] = &$thisref;
        	}
    	}	 


    	return $list;
    }
}
