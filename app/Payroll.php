<?php

namespace App; 

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class Payroll extends Model
{
    protected $fillable = [];
    /**
     * Return all generated payroll
     * @return query
     */
    public function scopeList($query, $value=null) 
    {
        return $query->groupBy('billing_number')->orderBy('id', 'desc')->paginate(5);
    }

    /**
     * 
     */
    public function scopeSummary($query, $employee_id, $billing_number)
    {
        
        try 
        {
            $id = Crypt::decryptString($employee_id);
        }
        catch (DecryptException $e) 
        {
            $id = null;
        }
        
        return $query->where('employee_id', $id)
                        ->where('billing_number', $billing_number)
                        ->join('employees', 'payrolls.employee_id', '=' , 'employees.user_id')
                        ->first();
    }
}
