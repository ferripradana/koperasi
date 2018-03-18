<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    //

	protected $fillable = [
        'name', 'department_id'
    ];

    public function department()
    {
        return $this->belongsTo('App\Model\Department', 'department_id');
    }
}
