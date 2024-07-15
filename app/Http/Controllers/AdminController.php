<?php

namespace App\Http\Controllers;

use App\Models\All;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $title = 'Dashboard';
        return view('admin.index', compact('title'));
    }


    // Data All
    public function indexalladmin()
    {
        confirmDelete();
        $title = 'Data All';
        $alls = All::all();
        return view('admin.data-all-admin', compact('title', 'alls'));
    }

    public function getDataallsadmin(Request $request)
    {
        if ($request->ajax()) {
            $query = All::query();

            if ($request->has('filter_type')) {
                $filterType = $request->input('filter_type');
                $currentMonth = Carbon::now()->format('Y-m');

                if ($filterType == 'billper') {
                    $query->where('nper', '=', $currentMonth);
                } elseif ($filterType == 'existing') {
                    $query->where('nper', '<', $currentMonth);
                }
            }

            $data_alls = $query->get();
            return datatables()->of($data_alls)
                ->addIndexColumn()
                ->toJson();
        }
    }
}
