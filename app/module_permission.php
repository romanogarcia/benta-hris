<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class module_permission extends Model
{
    protected $fillable=['module_id','department_id','full','add','view','edit','delete'];
}



