<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Companies extends Model
{
    protected $fillable = ['company_name', 'company_logo', 'email', 'address', 'website', 'city', 'zip_code', 'country_id', 'date_format', 'extra_address', 'phone', 'business_number', 'tax_number'];
}
