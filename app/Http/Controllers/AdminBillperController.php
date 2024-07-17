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

            if ($request->has('jenis_data')) {
                $jenisData = $request->input('jenis_data');
                $currentMonth = Carbon::now()->format('Y-m');

                if ($jenisData == 'Billper') {
                    $query->where('nper', '=', $currentMonth);
                } elseif ($jenisData == 'Existing') {
                    $query->where('nper', '<', $currentMonth);
                }
            }

            if ($request->has('nper')) {
                $nper = $request->input('nper');
                $query->where('nper', 'LIKE', "%$nper%");
            }

            if ($request->has('status_pembayaran')) {
                $statusPembayaran = $request->input('status_pembayaran');
                if ($statusPembayaran != 'Semua') {
                    $query->where('status_pembayaran', '=', $statusPembayaran);
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

        return Excel::download(new AllExport($allData), 'Data-Billper-Existing.xlsx');
    }

    public function downloadFilteredExcelbillper(Request $request)
    {
        $bulanTahun = $request->input('nper');
        $statusPembayaran = $request->input('status_pembayaran');

        // Format input nper ke format yang sesuai dengan kebutuhan database
        $formattedBulanTahun = Carbon::createFromFormat('Y-m', $bulanTahun)->format('Y-m-d');

        // Query untuk mengambil data berdasarkan rentang nper
        $query = All::where('nper', 'like', substr($formattedBulanTahun, 0, 7) . '%');

        // Filter berdasarkan status_pembayaran jika tidak "Semua"
        if ($statusPembayaran && $statusPembayaran !== 'Semua') {
            $query->where('status_pembayaran', $statusPembayaran);
        } else {
        }

        // Ambil data yang sudah difilter
        $filteredData = $query->select('nama', 'no_inet', 'saldo', 'no_tlf', 'email', 'sto', 'umur_customer', 'produk', 'status_pembayaran', 'nper')->get();

        // Export data menggunakan AllExport dengan data yang sudah difilter
        return Excel::download(new AllExport($filteredData), 'Data-Semua-' . $bulanTahun . '-' . $statusPembayaran . '.xlsx');
    }
}
