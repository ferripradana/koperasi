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

class ReportRLUnit extends Controller
{

	protected $helper;

	public function __construct(\App\Helpers\Common $helper){
		$this->helper = $helper;
	}

    //
    public function index(){
    	$bulan_option = $this->helper->getBulanOption();
    	$tahun_option = $this->helper->getTahunOption();
    	return view('admin.reportrlunit.export', compact('bulan_option','tahun_option'));
    }

     public function post(Request $request){
     	$tanggal_from = date('Y-m-d', strtotime($request->tanggal_from))  ; 
    	$tanggal_to = date('Y-m-d', strtotime($request->tanggal_to))  ; 
    	$id_unit = (int) $request->id_unit;

    	$unit   = \App\Model\Unit::find($id_unit);

    	$coa_in = COA::where('group_id', array(4) )->get();
    	$coa_in = $this->createTree($coa_in, $tanggal_from, $tanggal_to, $id_unit);

    	$coa_ex = COA::where('group_id', array(5) )->get();
    	$coa_ex = $this->createTree($coa_ex, $tanggal_from, $tanggal_to, $id_unit);


    	$q1 = 'select sum(d. amount) as amount
	        	   from jurnal_header_unit h
	        	   join jurnal_detail_unit d on (h.id = d.jurnal_header_id)
	        	   join coa c on (d.coa_id = c.id)
	        	   where c.group_id = 4
	    	       and tanggal <= "'.$tanggal_to.'"
	    	       and tanggal >= "'.$tanggal_from.'" 
	    	       and h.id_unit='.$id_unit;
	    $result_laba =  \DB::select($q1);       	       

	    $q2 = 'select sum(d. amount) as amount
	        	   from jurnal_header_unit h
	        	   join jurnal_detail_unit d on (h.id = d.jurnal_header_id)
	        	   join coa c on (d.coa_id = c.id)
	        	   where c.group_id = 5
	    	       and tanggal <= "'.$tanggal_to.'"
	    	       and tanggal >= "'.$tanggal_from.'" 
	    	       and h.id_unit='.$id_unit; 	
	    $result_beban =  \DB::select($q2);      	              
        $profit = $result_laba[0]->amount - $result_beban[0]->amount;

        $q =   'select  SUM(CASE When dc="D" then amount else 0 End ) as debit, 
	        	   SUM(CASE When dc="C" then amount else 0 End ) as credit	
	        	   from jurnal_header_unit h
	        	   join jurnal_detail_unit d on (h.id = d.jurnal_header_id)
	        	   join coa c on (c.id = d.coa_id)
	        	   where 
	        	   c.group_id = 5
	        	   and tanggal <= "'.$tanggal_to.'"
	    	       and tanggal >= "'.$tanggal_from.'" 
	        	   and h.id_unit='.$id_unit;

	   	$result_gtd =  \DB::select($q);      

	   	$q =   'select  SUM(CASE When dc="D" then amount else 0 End ) as debit, 
	        	   SUM(CASE When dc="C" then amount else 0 End ) as credit	
	        	   from jurnal_header_unit h
	        	   join jurnal_detail_unit d on (h.id = d.jurnal_header_id)
	        	   join coa c on (c.id = d.coa_id)
	        	   where 
	        	   c.group_id = 4
	        	   and tanggal <= "'.$tanggal_to.'"
	    	       and tanggal >= "'.$tanggal_from.'" 
	        	   and h.id_unit='.$id_unit;

	   	$result_gtk =  \DB::select($q);       

        $gt_d = $result_gtd[0]->debit - $result_gtd[0]->credit;
        $gt_c = ($result_gtk[0]->credit - $result_gtk[0]->debit) ;

    	return view('admin.pdf.reportrlunit',compact('coa_in', 'coa_ex', 'unit','result_gtk','result_gtd','profit','tanggal_from','tanggal_to','gt_d','gt_c', 'profit'));
     }

     private function createTree($coa, $tanggal_from, $tanggal_to, $id_unit){
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
	    	       and tanggal <= "'.$tanggal_to.'"
	    	       and tanggal >= "'.$tanggal_from.'" 
	    	       and h.id_unit='.$id_unit;

	    	$result =  \DB::select($q);       

	        $thisref['debit'] = (float) $result[0]->debit;
	        $thisref['credit'] = (float) $result[0]->credit;
	        if ($data->group_id == 1 OR $data->group_id == 5 ) {
	        	$thisref['amount'] = $thisref['debit']  - $thisref['credit'] ;
	        }else{
	        	$thisref['amount'] = $thisref['credit']  - $thisref['debit'] ;
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
