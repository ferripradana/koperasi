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


    public function getNextMonth($param){
        $monthToAdd = 1;

        $d1 = \DateTime::createFromFormat('Y-m-d', $param);

        $year = $d1->format('Y');
        $month = $d1->format('n');
        $day = $d1->format('d');

        $year += floor($monthToAdd/12);
        $monthToAdd = $monthToAdd%12;
        $month += $monthToAdd;
        if($month > 12) {
            $year ++;
            $month = $month % 12;
            if($month === 0)
                $month = 12;
        }

        if(!checkdate($month, $day, $year)) {
            $d2 = \DateTime::createFromFormat('Y-n-j', $year.'-'.$month.'-1');
            $d2->modify('last day of');
        }else {
            $d2 = \DateTime::createFromFormat('Y-n-d', $year.'-'.$month.'-'.$day);
        }
        $d2->setTime($d1->format('H'), $d1->format('i'), $d1->format('s'));
        return $d2->format('Y-m-d');
    }

    public function getBulanBerikutnya($param, $first_date){
        $monthToAdd = 1;

        $d1 = \DateTime::createFromFormat('Y-m-d', $param);

        $year = $d1->format('Y');
        $month = $d1->format('n');
        $day = $d1->format('d');

        $year += floor($monthToAdd/12);
        $monthToAdd = $monthToAdd%12;
        $month += $monthToAdd;
        if($month > 12) {
            $year ++;
            $month = $month % 12;
            if($month === 0)
                $month = 12;
        }

        if(!checkdate($month, $first_date, $year)) {
            $d2 = \DateTime::createFromFormat('Y-n-j', $year.'-'.$month.'-1');
            $d2->modify('last day of');
        }else {
            $d2 = \DateTime::createFromFormat('Y-n-d', $year.'-'.$month.'-'.$first_date);
        }
        $d2->setTime($d1->format('H'), $d1->format('i'), $d1->format('s'));
        return $d2->format('Y-m-d');
    }

    public function getBulanOption(){
        return [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
        ];
    }


    public function getTahunOption(){
        $current = date('Y');
        $from = $current-5;
        $to = $current+5;
        $return = [];
        for ($i=$from; $i <= $to ; $i++) { 
            $return[$i] = $i;
        }
        return $return;
    }


    public static function getNoTransaksi($type){
        if ($type=='simpanan') {
            $last_no = substr(\App\Model\Simpanan::max('id'),12) ;      
            return "PINJ".date("dmY").sprintf("%07d", $last_no + 1 );
        } else if($type=='penarikan'){
            $last_no = substr(\App\Model\Penarikan::max('id'),12) ;      
            return  "PENR".date("dmY").sprintf("%07d", $last_no + 1 );
        }
        else if($type=='peminjaman'){
            $last_no = substr(\App\Model\Peminjaman::max('id'),12) ;      
            return  "PINJ".date("dmY").sprintf("%07d", $last_no + 1 );
        }
        else if($type=='angsuran'){
            $last_no = substr(\App\Model\Angsuran::max('id'),12) ;      
            return  "ANGS".date("dmY").sprintf("%07d", $last_no + 1 );
        }
        else if($type=='pinalti'){
            $last_no = substr(\App\Model\Pinalti::max('id'),11) ;      
            return  "PIN".date("dmY").sprintf("%07d", $last_no + 1 );
        }
        else if($type=='transaksi'){
            $last_no = substr(\App\Model\Transaksi::max('id'),11) ;      
            return  "TRL".date("dmY").sprintf("%07d", $last_no + 1 );
        }



    }






}