<?php

namespace App\Http\Controllers;

use App\Exports\AllExport;
use App\Models\All;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AdminBillperController extends Controller
{
    public function index()
    {
        $title = 'Dashboard';
        return view('admin-billper.index', compact('title'));
    }


    // Data All
    public function indexalladminbillper()
    {
        confirmDelete();
        $title = 'Data All';
        $alls = All::all();
        return view('admin-billper.data-all-adminbillper', compact('title', 'alls'));
    }

    public function getDataallsadminbillper(Request $request)
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

    public function exportbillper()
    {
        $allData = All::select('nama', 'no_inet', 'saldo', 'no_tlf', 'email', 'sto', 'umur_customer', 'produk', 'status_pembayaran', 'nper')->get();

        return Excel::download(new AllExport($allData), 'data-semua.xlsx');
    }

    public function downloadFilteredExcelbillper(Request $request)
    {
        $bulanTahun = $request->input('nper');

        // Format input nper ke format yang sesuai dengan kebutuhan database
        $formattedBulanTahun = Carbon::createFromFormat('Y-m', $bulanTahun)->format('Y-m-d');

        // Query untuk mengambil data berdasarkan rentang nper
        $filteredData = All::where('nper', 'like', substr($formattedBulanTahun, 0, 7) . '%')
            ->select('nama', 'no_inet', 'saldo', 'no_tlf', 'email', 'sto', 'umur_customer', 'produk', 'status_pembayaran', 'nper')
            ->get();

        // Export data menggunakan AllExport dengan data yang sudah difilter
        return Excel::download(new AllExport($filteredData), 'Data-Semua-' . $bulanTahun . '.xlsx');
    }
}
