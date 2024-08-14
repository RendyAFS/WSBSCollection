<?php

namespace App\Http\Controllers;

use App\Models\OtpCode;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $otpcodes= OtpCode::all();

        return view('home', compact('otpcodes'));
    }
}
