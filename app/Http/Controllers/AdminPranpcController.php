<?php

namespace App\Http\Controllers;

use App\Exports\AllExport;
use App\Exports\PranpcExport;
use App\Models\All;
use App\Models\Pranpc;
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


    // Data All
    public function indexpranpcadminpranpc()
    {
        confirmDelete();
        $title = 'Data All';
        $alls = All::all();
        $users = User::where('level', 'User')->get();
        return view('admin-pranpc.data-pranpc-adminpranpc', compact('title', 'alls', 'users'));
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
        $title = 'Edit Data PraNPC';
        $pranpc = Pranpc::findOrFail($id);
        return view('admin-pranpc.edit-pranpcadminpranpc', compact('title', 'pranpc'));
    }

    public function updatepranpcsadminpranpc(Request $request, $id)
    {
        $pranpc = Pranpc::findOrFail($id);
        $pranpc->nama = $request->input('nama');
        $pranpc->status_pembayaran = $request->input('status_pembayaran');
        $pranpc->snd = $request->input('snd');
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
}
