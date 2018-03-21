<?php

namespace App\Model\Acc;

use Illuminate\Database\Eloquent\Model;

class CoaGroup extends Model
{
    //
     protected $table = 'coa_groups';
     protected $fillable = [
        'code', 'nama' , 'description','balance_sheet_group',
        'balance_sheet_side', 'pls_group', 'pls_side'
     ];
     
}
