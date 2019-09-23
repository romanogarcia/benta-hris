<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name', 'page', 'role','action'];

    public function setNameAttribute($value)
	{
	    $this->attributes['name'] = strtoupper($value);
	}

	public function getNameAttribute($value)
	{
		return strtoupper($value);
	}

	public function department()
    {
        return $this->belongsTo(Department::class)->select(array('id', 'name'));
    }
}
