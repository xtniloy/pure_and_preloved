<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index(){
        return view('public.home.index');
    }

    public function product(){
        return view('public.home.product');
    }
}
