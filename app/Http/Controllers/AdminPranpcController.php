<?php

namespace App\Http\Controllers;

use App\Exports\AllExport;
use App\Exports\PranpcExport;
use App\Models\All;
use App\Models\Pranpc;
use App\Models\SalesReport;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class AdminPranpcController extends Controller
{
    public function index()
    {
        $title = 'Dashboard';
        return view('admin-pranpc.index', compact('title'));
    }


    // Data Pranpc
    public function indexpranpcadminpranpc()
    {
        confirmDelete();
        $title = 'Data Pranpc';
        $pranpcs = Pranpc::all();
        $users = User::where('level', 'User')->get();
        return view('admin-pranpc.data-pranpc-adminpranpc', compact('title', 'pranpcs', 'users'));
    }

    public function getDatapranpcsadminpranpc(Request $request)
    {
        if ($request->ajax()) {
            $query = Pranpc::query()->with('user');

            // Filter by year and month
            if ($request->year && $request->bulan) {
                $year = $request->year;
                $bulanRange = explode('-', $request->bulan);
                $startMonth = $bulanRange[0];
                $endMonth = $bulanRange[1];

                $startMintgk = $year . '-' . $startMonth;
                $endMaxtgk = $year . '-' . $endMonth;

                $query->where('mintgk', '>=', $startMintgk)
                    ->where('maxtgk', '<=', $endMaxtgk);
            }

            // Filter by status pembayaran
            if ($request->status_pembayaran && $request->status_pembayaran != 'Semua') {
                $query->where('status_pembayaran', $request->status_pembayaran);
            } else {
            }

            $pranpcs = $query->get();

            return datatables()->of($pranpcs)
                ->addIndexColumn()
                ->addColumn('opsi-tabel-datapranpcadminpranpc', function ($pranpc) {
                    return view('components.opsi-tabel-datapranpcadminpranpc', compact('pranpc'));
                })
                ->addColumn('nama_user', function ($all) {
                    return $all->user ? $all->user->name : 'Tidak Ada'; // Mengakses nama pengguna atau teks "Tidak Ada" jika relasi user null
                })
                ->toJson();
        }
    }

    public function exportpranpc()
    {
        $pranpcData = Pranpc::select('nama', 'snd', 'alamat', 'bill_bln', 'bill_bln1', 'mintgk', 'maxtgk', 'multi_kontak1', 'email', 'status_pembayaran')->get();

        return Excel::download(new PranpcExport($pranpcData), 'Data-Pranpc-Semua.xlsx');
    }

    public function downloadFilteredExcelpranpc(Request $request)
    {
        $year = $request->input('year');
        $bulanRange = $request->input('bulan');
        $statusPembayaran = $request->input('status_pembayaran');

        // Split bulanRange untuk mendapatkan bulan awal dan bulan akhir
        list($bulanAwal, $bulanAkhir) = explode('-', $bulanRange);

        // Format tahun dan bulan ke format yang sesuai dengan kebutuhan database
        $formattedBulanAwal = $year . '-' . substr($bulanAwal, 0, 2);
        $formattedBulanAkhir = $year . '-' . substr($bulanAkhir, 0, 2); // Ambil bulan kedua dari rentang


        // Query untuk mengambil data berdasarkan rentang bulan dan tahun
        $query = Pranpc::where('mintgk', '>=', $formattedBulanAwal)
            ->where('maxtgk', '<=', $formattedBulanAkhir);

        // Filter berdasarkan status_pembayaran jika tidak "Semua"
        if ($statusPembayaran && $statusPembayaran !== 'Semua') {
            $query->where('status_pembayaran', $statusPembayaran);
        }

        // Ambil data yang sudah difilter
        $filteredData = $query->select('nama', 'snd', 'alamat', 'bill_bln', 'bill_bln1', 'mintgk', 'maxtgk', 'multi_kontak1', 'email', 'status_pembayaran')->get();

        // Export data menggunakan PranpcExport dengan data yang sudah difilter
        return Excel::download(new PranpcExport($filteredData), 'Data-Pranpc-' . $statusPembayaran . '-' . $year . '-' . $bulanRange . '.xlsx');
    }

    public function editpranpcsadminpranpc($id)
    {
        $title = 'Edit Data Plotting PraNPC';
        $pranpc = Pranpc::with('user')->findOrFail($id);
        $user = $pranpc->user ? $pranpc->user->name : 'Tidak ada'; // Ambil name atau 'Tidak ada'
        $sales_report = SalesReport::where('pranpc_id', $id)->first(); // Ambil entri yang relevan
        return view('admin-pranpc.edit-pranpcadminpranpc', compact('title', 'pranpc', 'user', 'sales_report'));
    }


    public function updatepranpcsadminpranpc(Request $request, $id)
    {
        $pranpc = Pranpc::findOrFail($id);
        $pranpc->nama = $request->input('nama');
        $pranpc->status_pembayaran = $request->input('status_pembayaran');
        $pranpc->snd = $request->input('snd');
        $pranpc->sto = $request->input('sto');
        $pranpc->bill_bln = $request->input('bill_bln');
        $pranpc->bill_bln1 = $request->input('bill_bln1');
        $pranpc->mintgk = $request->input('mintgk');
        $pranpc->maxtgk = $request->input('maxtgk');
        $pranpc->multi_kontak1 = $request->input('multi_kontak1');
        $pranpc->email = $request->input('email');
        $pranpc->alamat = $request->input('alamat');
        $pranpc->save();

        Alert::success('Data Berhasil Diperbarui');
        return redirect()->route('pranpc-adminpranpc.index');
    }

    public function savePlotting(Request $request)
    {
        $ids = $request->input('ids');
        $userId = $request->input('user_id');

        // Update data dengan user_id yang dipilih
        Pranpc::whereIn('id', $ids)->update(['users_id' => $userId]);

        return response()->json(['success' => true]);
    }




    // Data Existing Pranpc
    public function indexexistingadminpranpc()
    {
        confirmDelete();
        $title = 'Data Existing';
        $existings = All::all();
        $users = User::where('level', 'User')->get();
        return view('admin-pranpc.data-existing-adminpranpc', compact('title', 'existings', 'users'));
    }

    public function getDataexistingsadminpranpc(Request $request)
    {
        if ($request->ajax()) {
            $query = All::query()->with('user'); // Menambahkan eager loading untuk relasi 'user'

            $currentMonth = Carbon::now()->format('Y-m');

            // Filter untuk hanya menampilkan data existing saja
            $query->where('nper', '<', $currentMonth);

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
                ->addColumn('opsi-tabel-dataexistingadminpranpc', function ($all) {
                    return view('components.opsi-tabel-dataexistingadminpranpc', compact('all'));
                })
                ->addColumn('nama_user', function ($all) {
                    return $all->user ? $all->user->name : 'Tidak Ada'; // Mengakses nama pengguna atau teks "Tidak Ada" jika relasi user null
                })
                ->rawColumns(['opsi-tabel-dataexistingadminpranpc']) // Menandai kolom sebagai raw HTML
                ->toJson();
        }
    }


    public function exportexisting()
    {
        $currentMonth = Carbon::now()->format('Y-m');
        $allData = All::select('nama', 'no_inet', 'saldo', 'no_tlf', 'email', 'sto', 'umur_customer', 'produk', 'status_pembayaran', 'nper')
            ->where('nper', '<', $currentMonth)
            ->get();

        return Excel::download(new AllExport($allData), 'Data-Existing.xlsx');
    }


    public function downloadFilteredExcelexisting(Request $request)
    {
        $bulanTahun = $request->input('nper');
        $statusPembayaran = $request->input('status_pembayaran');

        // Format input nper ke format yang sesuai dengan kebutuhan database
        $formattedBulanTahun = Carbon::createFromFormat('Y-m', $bulanTahun)->format('Y-m-d');

        // Get current month
        $currentMonth = Carbon::now()->format('Y-m');

        // Query untuk mengambil data berdasarkan rentang nper
        $query = All::where('nper', 'like', substr($formattedBulanTahun, 0, 7) . '%')
            ->where('nper', '<', $currentMonth); // Filter data with nper < current month

        // Filter berdasarkan status_pembayaran jika tidak "Semua"
        if ($statusPembayaran && $statusPembayaran !== 'Semua') {
            $query->where('status_pembayaran', $statusPembayaran);
        }

        // Ambil data yang sudah difilter
        $filteredData = $query->select('nama', 'no_inet', 'saldo', 'no_tlf', 'email', 'sto', 'umur_customer', 'produk', 'status_pembayaran', 'nper')->get();

        // Export data menggunakan AllExport dengan data yang sudah difilter
        return Excel::download(new AllExport($filteredData), 'Data-Semua-' . $bulanTahun . '-' . $statusPembayaran . '.xlsx');
    }


    public function editexistingsadminpranpc($id)
    {
        $title = 'Edit Data Plotting Existing';
        $all = All::with('user')->findOrFail($id);
        $user = $all->user ? $all->user : 'Tidak ada';
        return view('admin-pranpc.edit-existingadminpranpc', compact('title', 'all', 'user'));
    }


    public function updateexistingsadminpranpc(Request $request, $id)
    {
        $all = All::findOrFail($id);
        $all->nama = $request->input('nama');
        $all->no_inet = $request->input('no_inet');
        $all->saldo = $request->input('saldo');
        $all->no_tlf = $request->input('no_tlf');
        $all->email = $request->input('email');
        $all->sto = $request->input('sto');
        $all->produk = $request->input('produk');
        $all->umur_customer = $request->input('umur_customer');
        $all->status_pembayaran = $request->input('status_pembayaran');
        $all->save();

        Alert::success('Data Berhasil Diperbarui');
        return redirect()->route('existing-adminpranpc.index');
    }

    public function savePlottingexisting(Request $request)
    {
        $ids = $request->input('ids');
        $userId = $request->input('user_id');

        // Update data dengan user_id yang dipilih
        All::whereIn('id', $ids)->update(['users_id' => $userId]);

        return response()->json(['success' => true]);
    }
}
