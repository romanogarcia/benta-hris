<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = ['name'];

    public function setNameAttribute($value)
	{
	    $this->attributes['name'] = strtoupper($value);
	}

	public function getNameAttribute($value)
	{
		    return strtoupper($value);
	}

}
