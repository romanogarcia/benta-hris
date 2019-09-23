<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    //
    protected $fillable = ['user_id', 'state_status', 'date_filed', 'date_start', 'date_end', 'type', 'reason'];

    public function setNameAttribute($value)
	{
	    $this->attributes['state_status'] = strtoupper($value);
	}

	public function getNameAttribute($value)
	{
		return strtoupper($value);
	}
}
