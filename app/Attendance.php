<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use \Carbon\Carbon;
class Attendance extends Model
{
    /**
     * Return all generated employees from attendances
     * @return void
     */
    public function scopeEmployees($query, $date=array()) 
    {
        return $query->groupBy('employee_id')->whereBetween('at_date',$date)->get();
    }

    /**
     * Get the the total hours of an employee
     * @return void
     */
    public function scopeHours($query, $employee, $date=array())
    {
		$dates = [];
		$from = new Carbon($date[0]);
		$to = new Carbon($date[1]);
		
		return $query->select('employee_id','name', DB::raw('SUM(total) as total_days'))
						->where('employee_id',$employee)
						->where(function($q) use ($from,$to)
						{ 
							$q->whereBetween('at_date',[$from,$to]);
						})
						->first()->toArray();
		       
    }

    // testing
}
