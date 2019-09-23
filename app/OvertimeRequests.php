<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class OvertimeRequests extends Model
{
    protected $fillable = ['user_id', 'time_start', 'time_end', 'date_start', 'date_end', 'type', 'reason', 'status'];

    /**
     * Get Available overtimes of given employee
     * @param query $query - model
     * @param string $employee - employee_id
     * @param array $date - beetwen two dates
     * @return query
     */
    public function scopeOvertime($query, $employee, $date=array())
    {
        return $query->select('date_start as date','user_id as employee_id','time_start', 'time_end')
                            ->where('user_id',$employee)
                            ->where('status','Approved')
                            ->whereBetween('date_start',$date)
                            ->get()->toArray();
    }
}
