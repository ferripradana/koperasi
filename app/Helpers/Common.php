<?php 


namespace App\Helpers;


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


}