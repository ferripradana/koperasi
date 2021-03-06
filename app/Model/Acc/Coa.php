<?php

namespace App\Model\Acc;

use Illuminate\Database\Eloquent\Model;

class Coa extends Model
{
    //
     protected $table = 'coa';
     protected $fillable = [
        'code' , 'nama', 'op_balance' , 'op_balance_dc', 'balance_sheet_group', 'balance_sheet_side',
		'pls_group', 'pls_side', 'group_id', 'parent_id'
     ];


     public function group(){
     	  return $this->belongsTo('App\Model\Acc\CoaGroup', 'group_id');
     }

     public function parent(){
     	  return $this->belongsTo('App\Model\Acc\Coa', 'parent_id');
     }
}
