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

class ReportEntryCoa extends Controller
{

	protected $helper;

	public function __construct(\App\Helpers\Common $helper){
		$this->helper = $helper;
	}	

	public function index(){
    	$bulan_option = $this->helper->getBulanOption();
    	$tahun_option = $this->helper->getTahunOption();
    	$coa = \App\Model\Acc\COA::select(
                    \DB::raw("CONCAT(code,'-',nama) AS name"),'id','parent_id')
                        ->orderBy('code','asc')
                        ->pluck('name', 'id');
    	return view('admin.reportentrycoa.export', compact('bulan_option','tahun_option', 'coa'));
    }

    public function post(Request $request){
    	$tanggal_from = date('Y-m-d', strtotime($request->tanggal_from))  ; 
    	$tanggal_to = date('Y-m-d', strtotime($request->tanggal_to))  ; 

    	$from = $request->tanggal_from;
    	$to = $request->tanggal_to;


    	$id_coa   = $request->coa ;

    	$coa = Coa::find($id_coa);
    	$code = $coa->code;
    	

    	$q = "
    	SELECT h.*,(
    	CASE  
		  WHEN d.dc ='D' THEN d.amount
		  ELSE 0 
		END) as dr,
		(
    	CASE  
		  WHEN d.dc ='C' THEN d.amount
		  ELSE 0
		END) as cr
    	FROM jurnal_header h
    	JOIN jurnal_detail d ON (h.id = d.jurnal_header_id)
    	WHERE  d.coa_id in  (
	        	   	select id from coa where code like '".$code."%'
	        	   )
	    AND 
	    	h.tanggal >= '".$tanggal_from."' AND h.tanggal <= '".$tanggal_to."' 
	    ORDER by h.id
    	";
    	$result =  \DB::select($q);  

    	$q_awal = 'Select  SUM(CASE When dc="D" then amount else 0 End ) as debit, 
	        	   SUM(CASE When dc="C" then amount else 0 End ) as credit	
	        	   from jurnal_header h
	        	   join jurnal_detail d on (h.id = d.jurnal_header_id)
    			   WHERE  d.coa_id in  (
	        	   	select id from coa where code like "'.$code.'%"
	        	   )
	        	   AND h.tanggal < "'.$tanggal_from.'"
    	';
    	
    	$result_saldo_awal =  \DB::select($q_awal);  

    	if ($coa->group_id == 1 || $coa->group_id == 5) {
    		$normal = "D";
    		$posisi = $normal;
    		$saldo_awal = $result_saldo_awal[0]->debit - $result_saldo_awal[0]->credit;
    		if ($saldo_awal < 0) {
    			$posisi = 'C';
    		}
    	} else if ( $coa->group_id == 2 || $coa->group_id == 3  ||  $coa->group_id == 4 ){
    		$normal = "C";
    		$posisi = $normal;
    	    $saldo_awal = $result_saldo_awal[0]->debit - $result_saldo_awal[0]->credit;
    	    if ($saldo_awal < 0) {
    			$posisi = 'D';
    		}
    	}	

    	return view('admin.pdf.entrycoa',compact('result', 'coa', 'from', 'to', 'saldo_awal', 'posisi', 'normal' ));
    	
    }

}