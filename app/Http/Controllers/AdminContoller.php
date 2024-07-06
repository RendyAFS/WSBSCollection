<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminContoller extends Controller
{
    public function index()
    {
        $title = 'Dashboard';
        return view('admin.index', compact('title'));
    }
}
