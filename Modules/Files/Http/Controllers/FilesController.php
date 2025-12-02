<?php

namespace Modules\Files\Http\Controllers;

use App\Http\Controllers\Controller;

class FilesController extends Controller
{
    public function index()
    {

        return view('files::index');

        //return response()->json(['message' => 'Files module working!']);
    }
}