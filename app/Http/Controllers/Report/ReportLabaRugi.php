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

class ReportLabaRugi extends Controller
{
    //
	protected $helper;

	public function __construct(\App\Helpers\Common $helper){
		$this->helper = $helper;
	}


    public function index(){
    	$bulan_option = $this->helper->getBulanOption();
    	$tahun_option = $this->helper->getTahunOption();
    	return view('admin.reportlabarugi.export', compact('bulan_option','tahun_option'));
    }

    public function post(Request $request){
    	$bulan_from = (int) $request->bulan_from;
    	$bulan_to = (int) $request->bulan_to;

    	$tahun_from = (int) $request->tahun_from;
    	$tahun_to = (int) $request->tahun_to;

    	
    	$result = COA::whereIn('group_id', array(4,5))->get();


        $coa = $this->createTree($result, $bulan_from, $bulan_to, $tahun_from, $tahun_to );
        //$coa = $this->createFlat($result, $bulan_from, $bulan_to, $tahun_from, $tahun_to );
        //$coa = $this->flatten($coa);

        $q1 = 'select sum(d. amount) as amount
	        	   from jurnal_header h
	        	   join jurnal_detail d on (h.id = d.jurnal_header_id)
	        	   join coa c on (d.coa_id = c.id)
	        	   where c.group_id = 4
	    	       and tanggal >= "'.$tahun_from.'-'.$bulan_from.'-01"
	    	       and tanggal <= "'.$tahun_to.'-'.$bulan_to.'-31" ';
	    $result_laba =  \DB::select($q1);       	       

	    $q2 = 'select sum(d. amount) as amount
	        	   from jurnal_header h
	        	   join jurnal_detail d on (h.id = d.jurnal_header_id)
	        	   join coa c on (d.coa_id = c.id)
	        	   where c.group_id = 5
	    	       and tanggal >= "'.$tahun_from.'-'.$bulan_from.'-01"
	    	       and tanggal <= "'.$tahun_to.'-'.$bulan_to.'-31" '; 	
	    $result_beban =  \DB::select($q2);      	              
        $profit = $result_laba[0]->amount - $result_beban[0]->amount;

       	return view('admin.pdf.reportlabarugi',compact('coa', 'bulan_from', 'bulan_to', 'tahun_from', 'tahun_to', 'profit'));
        
    }


    private function createTree($coa, $bulan_from, $bulan_to, $tahun_from, $tahun_to){
    	$refs = array();
		$list = array();

		foreach ($coa as $data) {
	        $thisref = &$refs[ $data->id ];
	        $thisref['sect_parent'] = $data->parent_id;
			 $thisref['sect_name'] = $data->code." - ".$data->nama."&nbsp;&nbsp;<a href ='".route('coa.edit', $data->id)."'><i class='fa fa-pencil'></a></i>";
	        $thisref['sect_id'] = $data->id;
	        $thisref['code'] = $data->code;
	   
	        $q = 'select sum(d. amount) as amount
	        	   from jurnal_header h
	        	   join jurnal_detail d on (h.id = d.jurnal_header_id)
	        	   where d.coa_id in  (
	        	   	select id from coa where code like "'.$data->code.'%"
	        	   )
	    	       and tanggal >= "'.$tahun_from.'-'.$bulan_from.'-01"
	    	       and tanggal <= "'.$tahun_to.'-'.$bulan_to.'-31" ';

	    	$result =  \DB::select($q);       

	        $thisref['total_amount'] = (float) $result[0]->amount;

	        if ($data->parent_id == 0) {
	            $list[ $data->id ] = &$thisref;
	        } else {
	            $refs[ $data->parent_id ]['children'][ $data->id  ] = &$thisref;
        	}
    	}	 


    	return $list;
    }


    private function flatten(array $array) {
	    $return = array();
	    array_walk_recursive($array, function($a) use (&$return) { $return[] = $a; });
	    return $return;
	}


    



}
