<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function employment_status()
    {
        return $this->belongsTo(EmploymentStatus::class)->select(array('id', 'name'));
    }

    public function department()
    {
        return $this->belongsTo(Department::class)->select(array('id', 'name'));
    }

}
