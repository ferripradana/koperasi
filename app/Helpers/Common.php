<?php 


namespace App\Helpers;

use App\Model\Acc\JournalHeader;
use App\Model\Acc\JournalDetail;
use App\Model\Acc\SaldoTabungan;

class Common {


	public function createTree($coa){
		$refs = array();
		$list = array();

		foreach ($coa as $data) {
	        $thisref = &$refs[ $data->id ];
	        $thisref['sect_parent'] = $data->parent_id;
			 $thisref['sect_name'] = $data->code." - ".$data->nama."&nbsp;&nbsp;<a href ='".route('coa.edit', $data->id)."'><i class='fa fa-pencil'></a></i>";
	        $thisref['sect_id'] = $data->id;
	    
	        if ($data->parent_id == 0) {
	            $list[ $data['id'] ] = &$thisref;
	        } else {
	            $refs[ $data['parent_id'] ]['children'][ $data->id  ] = &$thisref;
        	}
    	}	 


    	return $list;

	}

	public function printTree($arr) {
        if(!is_null($arr) && count($arr) > 0) {
            echo '<ul>';
            foreach($arr as $node) {
                echo "<li>".  $node['sect_name'] . "";
                if (array_key_exists('children', $node)) {
                    $this->printTree($node['children']);
                }
                echo '</li>';
            }
            echo '</ul>';
        }
    }


    public function insertJournalHeader($entry_type, $tanggal, $debit_total, $credit_total, $narasi  ){
    	 $header = JournalHeader::create(
               [
                    'entry_type' => $entry_type,
                    'jurnal_number' => 'JRN-'.sprintf("%07d", JournalHeader::count('id') + 1 ),
                    'tanggal'       => $tanggal,
                    'debit_total'   => $debit_total,
                    'credit_total'  =>  $credit_total,
                    'narasi'        => $narasi,
               ]
         );

         return $header->id;
    }

    public function updateJournalHeader($id,$entry_type, $tanggal, $debit_total, $credit_total, $narasi ){

    	$header = JournalHeader::find($id);
    	$header->update(
                [
                	'entry_type'	=> $entry_type,
                    'tanggal'       => $tanggal,
                    'debit_total'   => $debit_total,
                    'credit_total'  => $credit_total,
                    'narasi'        => $narasi
                ]
        );

        return $header->id;
    }

    public function insertJournalDetail( $jurnal_header_id, $coa_id , $amount , $dc){
    	$detail = JournalDetail::create(
                [
                    'jurnal_header_id' => $jurnal_header_id,
                    'coa_id'           => $coa_id,
                    'amount'           => $amount,
                    'dc'               => $dc,
                ]
        );
        return $detail->id;
    }

    public function updateJournalDetail(){
    	return true;
    }


    public function changeSaldo($id_anggota, $jenis_simpanan, $nominal){
    	return;
    }






}