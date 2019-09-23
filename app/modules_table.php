<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class modules_table extends Model
{
   protected $fillable=['module_name','module_link','parent','priority','menu_icon','route_name'];
	
	/*public function page_role()
    {
        return $this->hasOne('App\Page_Role');
    }*/
}



