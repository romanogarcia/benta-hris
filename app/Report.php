<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable =['report_name','view','department_id'];
	protected $table="reports";
}
