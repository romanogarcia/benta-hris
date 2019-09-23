<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page_Role extends Model
{
    protected $fillable=['page_name','permissions','full','add','view','edit','delete','module_id'];
	protected $table='page_roles';
	
	/*public function module_table()
    {
        return $this->belongsTo('App\modules_table');
    }*/
}
