<?php

namespace App\Http\Controllers;

use App\Models\All;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $title = 'WSBS Collection';
        return view('user.index', compact('title'));
    }

    // Assignment Billper
    public function indexassignmentbillper()
    {
        $title = 'Assignment Billper';
        return view('user.assignmentbillper', compact('title'));
    }

    public function getDataassignmentbillper(Request $request)
    {
        if ($request->ajax()) {
            $data_alls = All::all(); // Eager loading for the 'user' relationship

            return datatables()->of($data_alls)
                ->addIndexColumn()
                ->addColumn('opsi-tabel-dataalladminbillper', function ($all) {
                    return view('components.opsi-tabel-dataalladminbillper', compact('all'))->render();
                })
                ->rawColumns(['opsi-tabel-dataalladminbillper']) // Mark column as raw HTML
                ->toJson();
        }

    }





    // Assignment Billper
    public function indexassignmentpranpc()
    {
        $title = 'Assignment Pranpc';
        return view('user.assignmentpranpc', compact('title'));
    }




    // Report Assignment Billper
    public function indexreportassignmentbillper()
    {
        $title = 'Report Assignment Billper';
        return view('user.reportassignmentbillper', compact('title'));
    }


    // Report Assignment Pranpc
    public function indexreportassignmentpranpc()
    {
        $title = 'Report Assignment Pranpc';
        return view('user.reportassignmentpranpc', compact('title'));
    }
}
