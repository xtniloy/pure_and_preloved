<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.sections.dashboard.index');
    }

    public function home()
    {
        return redirect()->route('admin.dashboard');
    }
}
