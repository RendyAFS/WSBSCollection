<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserContoller extends Controller
{
    public function index()
    {
        $title = 'WSBS Collection';
        return view('user.index', compact('title'));
    }
}
