<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SittingsController extends Controller
{
    public $glob_company_id = 1;
    public $glob_company_name;
    public $glob_financial_year;

    public function about(){
        return view('sys.about');
    }
}
