<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportBIR1601CController extends Controller
{
    public function index(){
        return view('report.bir_1601c.index');
    }
}
