<?php

namespace App\Http\Controllers;

use App\Exports\AllExport;
use App\Imports\DataMasterImport;
use App\Models\All;
use App\Models\DataMaster;
use App\Models\Riwayat;
use App\Models\TempAll;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SuperAdminController extends Controller
{
    // DASHBOARD
    public function index()
    {
        $title = 'Dashboard';
        return view('super-admin.index', compact('title'));
    }


    // TOOL
    public function indextool()
    {
        confirmDelete();
        $title = 'Tool';
        $temp_alls = TempAll::all();
        return view('super-admin.tools', compact('title', 'temp_alls'));
    }

    public function checkFile1(Request $request)
    {
        $request->validate(['file1' => 'required|file|mimes:xlsx']);
        $file1 = $request->file('file1');
        $fileName1 = $file1->getClientOriginalName();
        $file1Path = $file1->getRealPath();

        // Load spreadsheet and get first row as array
        $spreadsheet1 = IOFactory::load($file1Path);
        $sheet1 = $spreadsheet1->getActiveSheet();
        $firstRow1 = $sheet1->rangeToArray('A1:Z1', null, true, false, true)[1]; // Get only the first row

        if (in_array('SND', $firstRow1)) {
            return response()->json(['status' => 'success', 'message' => "*Kolom SND ditemukan dalam file $fileName1"]);
        } else {
            return response()->json(['status' => 'error', 'message' => "*Kolom SND tidak ditemukan dalam file $fileName1"]);
        }
    }

    public function checkFile2(Request $request)
    {
        $request->validate(['file2' => 'required|file|mimes:xlsx']);
        $file2 = $request->file('file2');
        $fileName2 = $file2->getClientOriginalName();
        $file2Path = $file2->getRealPath();

        // Load spreadsheet and get first row as array
        $spreadsheet2 = IOFactory::load($file2Path);
        $sheet2 = $spreadsheet2->getActiveSheet();
        $firstRow2 = $sheet2->rangeToArray('A1:Z1', null, true, false, true)[1]; // Get only the first row

        if (in_array('EVENT_SOURCE', $firstRow2)) {
            return response()->json(['status' => 'success', 'message' => "*Kolom EVENT_SOURCE ditemukan dalam file $fileName2"]);
        } else {
            return response()->json(['status' => 'error', 'message' => "*Kolom EVENT_SOURCE tidak ditemukan dalam file $fileName2"]);
        }
    }

    public function vlookup(Request $request)
    {
        ini_set('memory_limit', '2048M');  // Increase memory limit
        set_time_limit(300);  // max_execution_time=300

        $request->validate([
            'file1' => 'required|file|mimes:xlsx',
        ]);

        $file1 = $request->file('file1')->getRealPath();

        $spreadsheet1 = IOFactory::load($file1);
        $sheet1 = $spreadsheet1->getActiveSheet();
        $data1 = $sheet1->toArray();

        $result = [];

        foreach ($data1 as $index1 => $row1) {
            if ($index1 == 0) continue; // Skip header row

            $lookupValue = $row1[array_search('SND', $data1[0])];

            // Fetch from database
            $dataMaster = DB::table('data_masters')->where('event_source', $lookupValue)->first();

            if ($dataMaster) {
                $nper = $row1[array_search('NPER', $data1[0])];
                $nperFormatted = date('Y-m', strtotime(substr($nper, 0, 4) . '-' . substr($nper, 4, 2) . '-01'));

                $result[] = [
                    'nama' => $dataMaster->pelanggan,
                    'no_inet' => $dataMaster->event_source,
                    'saldo' => $row1[array_search('SALDO', $data1[0])],
                    'no_tlf' => $dataMaster->mobile_contact_tel,
                    'email' => $dataMaster->email_address,
                    'sto' => $dataMaster->csto,
                    'umur_customer' => $row1[array_search('UMUR_CUSTOMER', $data1[0])],
                    'produk' => $row1[array_search('INDIHOME', $data1[0])],
                    'nper' => $nperFormatted,
                ];
            }
        }

        // Insert the result into the database
        foreach ($result as $row) {
            DB::table('temp_alls')->insert([
                'nama' => $row['nama'] ?: '-',
                'no_inet' => $row['no_inet'] ?: '-',
                'saldo' => $row['saldo'] ?: '-',
                'no_tlf' => $row['no_tlf'] ?: '-',
                'email' => $row['email'] ?: '-',
                'sto' => $row['sto'] ?: '-',
                'umur_customer' => $row['umur_customer'] ?: '-',
                'produk' => $row['produk'] ?: '-',
                'status_pembayaran' => 'Unpaid', // Set all to 'Unpaid'
                'nper' => $row['nper'] ?: '-',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('tools.index');
    }


    public function getDataTempalls(Request $request)
    {
        if ($request->ajax()) {
            $data_tempalls = TempAll::all();
            return datatables()->of($data_tempalls)
                ->addIndexColumn()
                ->addColumn('opsi-tabel-datatempall', function ($tempall) {
                    return view('components.opsi-tabel-tempall', compact('tempall'));
                })
                ->toJson();
        }
    }

    public function savetempalls()
    {
        // Ambil data dari temp_alls
        $tempAlls = TempAll::all();


        // Insert data ke alls
        foreach ($tempAlls as $row) {
            DB::table('alls')->insert([
                'nama' => $row->nama ?: 'N/A',
                'no_inet' => $row->no_inet ?: 'N/A',
                'saldo' => $row->saldo ?: 'N/A',
                'no_tlf' => $row->no_tlf ?: 'N/A',
                'email' => $row->email ?: 'N/A',
                'sto' => $row->sto ?: 'N/A',
                'umur_customer' => $row->umur_customer ?: 'N/A',
                'produk' => $row->produk ?: 'N/A',
                'status_pembayaran' => 'Unpaid', // Set all to 'Unpaid'
                'nper' => $row->nper ?: 'N/A',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Kosongkan table temp_alls
        TempAll::truncate();


        // Redirect ke halaman tools.index atau halaman lainnya
        Alert::success('Data Berhasil Tersimpan');
        return redirect()->route('tools.index')->with('success', 'Data Berhasil Tersimpan');
    }


    public function deleteAllTempalls()
    {
        // Kosongkan table temp_alls
        TempAll::truncate();

        Alert::success('Data Berhasil Terhapus');
        return redirect()->route('tools.index');
    }

    public function destroyTempalls($id)
    {
        $data = TempAll::findOrFail($id);
        $data->delete();
        Alert::success('Data Berhasil Terhapus');
        return redirect()->route('tools.index');
    }

    // Data Master
    public function indexdatamaster()
    {
        confirmDelete();
        $title = 'Data Master';
        $data_masters = DataMaster::all();
        return view('super-admin.data-master', compact('title', 'data_masters'));
    }

    public function getDatamasters(Request $request)
    {
        if ($request->ajax()) {
            $data_masters = DataMaster::all();
            return datatables()->of($data_masters)
                ->addIndexColumn()
                ->addColumn('opsi-tabel-datamaster', function ($masters) {
                    return view('components.opsi-tabel-datamaster', compact('masters'));
                })
                ->toJson();
        }
    }


    public function tambahPelanggan(Request $request)
    {
        ini_set('memory_limit', '2048M');  // Increase memory limit
        set_time_limit(300);  // Increase max execution time

        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:xlsx',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $file = $request->file('file');

        // Tambahkan log untuk debugging
        // \Log::info('Uploaded file:', ['file' => $file]);

        // Import the data with chunking and without events
        DataMaster::withoutEvents(function () use ($file) {
            Excel::import(new DataMasterImport, $file);
        });

        Alert::success('Data Berhasil Terinput');

        return redirect()->route('datamaster.index');
    }

    public function cekFileDataMaster(Request $request)
    {
        ini_set('memory_limit', '2048M');  // Increase memory limit
        set_time_limit(300);  // Increase max execution time

        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:xlsx',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => 'File validation failed.']);
        }

        $file = $request->file('file')->getRealPath();
        $spreadsheet = IOFactory::load($file);
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray();

        $headerRow = $data[0];
        $requiredHeaders = ['EVENT_SOURCE', 'KWADRAN', 'CSTO', 'MOBILE_CONTACT_TEL', 'EMAIL_ADDRESS', 'PELANGGAN', 'ALAMAT_PELANGGAN'];
        $missingHeaders = array_diff($requiredHeaders, $headerRow);

        if (!empty($missingHeaders)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Missing headers: ' . implode(', ', $missingHeaders),
            ]);
        }

        return response()->json(['status' => 'success', 'message' => '*Kolom Sesuai dengan database.']);
    }



    public function editdatamasters($id)
    {
        $title = 'Edit Data Master';
        $data_master = DataMaster::findOrFail($id);
        return view('super-admin.edit-datamaster', compact('title', 'data_master'));
    }

    public function updatedatamasters(Request $request, $id)
    {
        $data_master = DataMaster::findOrFail($id);
        $data_master->event_source = $request->input('event_source');
        $data_master->kwadran = $request->input('kwadran');
        $data_master->csto = $request->input('csto');
        $data_master->mobile_contact_tel = $request->input('mobile_contact_tel');
        $data_master->email_address = $request->input('email_address');
        $data_master->pelanggan = $request->input('pelanggan');
        $data_master->alamat_pelanggan = $request->input('alamat_pelanggan');
        $data_master->save();

        Alert::success('Data Berhasil Diperbarui');
        return redirect()->route('datamaster.index');
    }


    public function destroydatamasters($id)
    {
        $data_master = DataMaster::findOrFail($id);
        $data_master->delete();
        Alert::success('Data Berhasil Terhapus');
        return redirect()->route('datamaster.index');
    }



    // Data All
    public function indexall()
    {
        confirmDelete();
        $title = 'Data All';
        $alls = All::all();
        return view('super-admin.data-all', compact('title', 'alls'));
    }

    public function getDataalls(Request $request)
    {
        if ($request->ajax()) {
            $query = All::query();

            if ($request->has('filter_type')) {
                $filterType = $request->input('filter_type');
                $currentMonth = Carbon::now()->format('Y-m');

                if ($filterType == 'bilper') {
                    $query->where('nper', '=', $currentMonth);
                } elseif ($filterType == 'existing') {
                    $query->where('nper', '<', $currentMonth);
                } 
            }

            $data_alls = $query->get();
            return datatables()->of($data_alls)
                ->addIndexColumn()
                ->addColumn('opsi-tabel-dataall', function ($all) {
                    return view('components.opsi-tabel-dataall', compact('all'));
                })
                ->toJson();
        }
    }


    public function editalls($id)
    {
        $title = 'Edit Data All';
        $all = All::findOrFail($id);
        return view('super-admin.edit-all', compact('title', 'all'));
    }


    public function checkFilePembayaran(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:xlsx']);
        $file = $request->file('file')->getRealPath();
        $spreadsheet = IOFactory::load($file);
        $sheet = $spreadsheet->getActiveSheet();
        $firstRow = $sheet->rangeToArray('A1:Z1', null, true, false, true)[1]; // Get only the first row

        if (in_array('SND', $firstRow)) {
            return response()->json(['status' => 'success', 'message' => '*Kolom SND ditemukan dalam file.']);
        } else {
            return response()->json(['status' => 'error', 'message' => '*Kolom SND tidak ditemukan dalam file.']);
        }
    }

    public function cekPembayaran(Request $request)
    {
        ini_set('memory_limit', '2048M');  // Increase memory limit
        set_time_limit(300);  // Increase max execution time

        // Validate the request
        $request->validate([
            'nper' => 'required|date_format:Y-m',
            'file' => 'required|file|mimes:xlsx'
        ]);

        $nper = $request->input('nper');
        $file = $request->file('file')->getRealPath();

        // Load the Excel file
        $spreadsheet = IOFactory::load($file);
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray();

        $sndList = [];
        foreach ($data as $index => $row) {
            if ($index == 0) continue; // Skip header row
            $sndList[] = $row[array_search('SND', $data[0])];
        }

        // Fetch records from the database
        $records = All::where('nper', $nper)->get();

        // Save riwayat entry
        $riwayat = new Riwayat();
        $riwayat->deskripsi_riwayat = $request->file('file')->getClientOriginalName();
        $riwayat->tanggal_riwayat = $nper;
        $riwayat->save();

        // Process each record
        foreach ($records as $record) {
            if (in_array($record->no_inet, $sndList)) {
                $record->status_pembayaran = 'Unpaid';
            } else {
                $record->status_pembayaran = 'Paid';
            }
            $record->save();
        }

        Alert::success('Data Berhasil Terupdate');
        return redirect()->route('all.index');
    }

    public function updatealls(Request $request, $id)
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
        return redirect()->route('all.index');
    }


    public function export()
    {
        $allData = All::select('nama', 'no_inet', 'saldo', 'no_tlf', 'email', 'sto', 'umur_customer', 'produk', 'status_pembayaran', 'nper')->get();

        return Excel::download(new AllExport($allData), 'data-semua.xlsx');
    }

    public function downloadFilteredExcel(Request $request)
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


    public function destroyalls($id)
    {
        $all = All::findOrFail($id);
        $all->delete();
        Alert::success('Data Berhasil Terhapus');
        return redirect()->route('all.index');
    }


    // Report Data
    public function indexreport(Request $request)
    {
        $title = 'Report All';

        // History
        $riwayats = Riwayat::where('created_at', '>=', Carbon::now()->subWeek())
            ->orderBy('id', 'desc')
            ->get();

        // Fetch filter values
        $filter_type = $request->input('filter_type', 'sto');
        $nper = $request->input('nper');

        // Determine the column to group by based on the filter type
        $group_column = $filter_type === 'umur_customer' ? 'umur_customer' : 'sto';

        // Query with optional filter
        $query = All::select(
            $group_column,
            DB::raw('COUNT(*) as total_ssl'),
            DB::raw('SUM(CAST(REPLACE(REPLACE(REPLACE(saldo, "Rp", ""), ".", ""), ",", "") AS UNSIGNED)) as total_saldo'),
            DB::raw('SUM(CASE WHEN status_pembayaran = "Paid" THEN CAST(REPLACE(REPLACE(REPLACE(saldo, "Rp", ""), ".", ""), ",", "") AS UNSIGNED) ELSE 0 END) as total_paid'),
            DB::raw('SUM(CASE WHEN status_pembayaran = "Unpaid" THEN CAST(REPLACE(REPLACE(REPLACE(saldo, "Rp", ""), ".", ""), ",", "") AS UNSIGNED) ELSE 0 END) as total_unpaid')
        );

        if ($nper) {
            $query->where('nper', $nper);
        }

        $reports = $query->groupBy($group_column)->get();

        $total_ssl = $reports->sum('total_ssl');
        $total_saldo = $reports->sum('total_saldo');
        $total_paid = $reports->sum('total_paid');
        $total_unpaid = $reports->sum('total_unpaid');

        return view('super-admin.report-data', compact('title', 'reports', 'total_ssl', 'total_saldo', 'total_paid', 'total_unpaid', 'nper', 'filter_type', 'riwayats'));
    }



    // AKUN
    public function indexdataakun()
    {
        $title = 'Data Akun';
        $users = User::where('level', '!=', 'Super Admin')
            ->orderBy('created_at', 'asc') // Sort by created_at in ascending order
            ->get();
        return view(
            'super-admin.data-akun',
            compact('title', 'users')
        );
    }

    public function updatestatus(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'status' => 'required|string'
        ]);

        $user = User::find($request->id);
        if ($user) {
            $user->status = $request->status;
            $user->save();
            return response()->json(['message' => 'Status updated successfully']);
        } else {
            return response()->json(['message' => 'User not found'], 404);
        }
    }

    public function destroyakun(string $id)
    {
        // Temukan data bulanan berdasarkan ID
        $user = User::find($id);

        // Periksa apakah data bulanan ditemukan
        if ($user) {
            // Hapus data dari database
            $user->delete();
            Alert::success('Berhasil Terhapus', 'Akun Berhasil Terhapus.');
        } else {
            Alert::success('Berhasil Terhapus', 'Akun Berhasil Terhapus.');
        }

        return redirect()->route('data-akun.index');
    }
}
