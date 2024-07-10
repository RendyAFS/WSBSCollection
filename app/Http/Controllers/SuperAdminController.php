<?php

namespace App\Http\Controllers;

use App\Exports\BillperExport;
use App\Models\Billpers;
use App\Models\TempBillpers;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Log;

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
        $title = 'Tool';
        $temp_billpers = TempBillpers::all();
        return view('super-admin.tools', compact('title', 'temp_billpers'));
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
            'file2' => 'required|file|mimes:xlsx',
        ]);

        $file1 = $request->file('file1')->getRealPath();
        $file2 = $request->file('file2')->getRealPath();

        $spreadsheet1 = IOFactory::load($file1);
        $spreadsheet2 = IOFactory::load($file2);

        $sheet1 = $spreadsheet1->getActiveSheet();
        $sheet2 = $spreadsheet2->getActiveSheet();

        $data1 = $sheet1->toArray();
        $data2 = $sheet2->toArray();


        $result = [];

        foreach ($data2 as $index2 => $row2) {
            if ($index2 == 0) continue; // Skip header row
            $lookupValue = $row2[array_search('EVENT_SOURCE', $data2[0])];
            foreach ($data1 as $index1 => $row1) {
                if ($index1 == 0) continue; // Skip header row
                if ($row1[array_search('SND', $data1[0])] == $lookupValue) {
                    $result[] = [
                        'nama' => $row2[array_search('PELANGGAN', $data2[0])],
                        'no_inet' => $row2[array_search('EVENT_SOURCE', $data2[0])],
                        'saldo' => $row1[array_search('SALDO', $data1[0])],
                        'no_tlf' => $row2[array_search('MOBILE_CONTACT_TEL', $data2[0])],
                        'email' => $row2[array_search('EMAIL_ADDRESS', $data2[0])],
                        'sto' => $row2[array_search('CSTO', $data2[0])],
                        'umur_customer' => $row1[array_search('UMUR_CUSTOMER', $data1[0])],
                        'produk' => $row1[array_search('INDIHOME', $data1[0])],
                    ];
                    break;
                }
            }
        }
        // Insert the result into the database
        foreach ($result as $row) {
            DB::table('temp_billpers')->insert([
                'nama' => $row['nama'] ?: 'N/A',
                'no_inet' => $row['no_inet'] ?: 'N/A',
                'saldo' => $row['saldo'] ?: 'N/A',
                'no_tlf' => $row['no_tlf'] ?: 'N/A',
                'email' => $row['email'] ?: 'N/A',
                'sto' => $row['sto'] ?: 'N/A',
                'umur_customer' => $row['umur_customer'] ?: 'N/A',
                'produk' => $row['produk'] ?: 'N/A',
                'status_pembayaran' => 'Unpaid', // Set all to 'Unpaid'
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('tools.index');
    }

    public function getDataTempBillpers(Request $request)
    {
        if ($request->ajax()) {
            $data_tempbillpers = TempBillpers::all();
            return datatables()->of($data_tempbillpers)
                ->addIndexColumn()
                ->addColumn('opsi-tabel-datatempbillper', function ($tempbillper) {
                    return view('components.opsi-tabel-tempbilper', compact('tempbillper'));
                })
                ->toJson();
        }
    }

    public function savetempbillpers(Request $request)
    {
        // Ambil data dari temp_billpers
        $tempBillpers = TempBillpers::all();

        // Ambil bulan dan tahun dari request
        $bulanTahun = $request->input('bulan_tahun');

        // Insert data ke billpers
        foreach ($tempBillpers as $row) {
            DB::table('billpers')->insert([
                'nama' => $row->nama ?: 'N/A',
                'no_inet' => $row->no_inet ?: 'N/A',
                'saldo' => $row->saldo ?: 'N/A',
                'no_tlf' => $row->no_tlf ?: 'N/A',
                'email' => $row->email ?: 'N/A',
                'sto' => $row->sto ?: 'N/A',
                'umur_customer' => $row->umur_customer ?: 'N/A',
                'produk' => $row->produk ?: 'N/A',
                'status_pembayaran' => 'Unpaid', // Set all to 'Unpaid'
                'bulan_tahun' => $bulanTahun, // Set date month/year
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Kosongkan table temp_billpers
        TempBillpers::truncate();


        // Redirect ke halaman tools.index atau halaman lainnya
        Alert::success('Data Berhasil Tersimpan');
        return redirect()->route('tools.index')->with('success', 'Data Berhasil Tersimpan');
    }


    public function deleteAllTempBillpers()
    {
        // Kosongkan table temp_billpers
        TempBillpers::truncate();

        Alert::success('Data Berhasil Terhapus');
        return redirect()->route('tools.index');
    }

    public function destroyTempBillpers($id)
    {
        $data = TempBillpers::findOrFail($id);
        $data->delete();
        Alert::success('Data Berhasil Terhapus');
        return redirect()->route('tools.index');
    }



    // Billpers
    public function indexbillper()
    {
        $title = 'Data Billper';
        $billpers = Billpers::all();
        return view('super-admin.data-billper', compact('title', 'billpers'));
    }

    public function getDataBillpers(Request $request)
    {
        if ($request->ajax()) {
            $data_billpers = Billpers::all();
            return datatables()->of($data_billpers)
                ->addIndexColumn()
                ->addColumn('opsi-tabel-databillper', function ($billper) {
                    return view('components.opsi-tabel-databillper', compact('billper'));
                })
                ->toJson();
        }
    }

    public function editBillpers($id)
    {
        $title = 'Edit Data Billper';
        $billper = Billpers::findOrFail($id);
        return view('super-admin.edit-billper', compact('title', 'billper'));
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
            'bulan_tahun' => 'required|date_format:Y-m',
            'file' => 'required|file|mimes:xlsx'
        ]);

        $bulan_tahun = $request->input('bulan_tahun');
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
        $records = Billpers::where('bulan_tahun', $bulan_tahun)->get();

        foreach ($records as $record) {
            if (in_array($record->no_inet, $sndList)) {
                $record->status_pembayaran = 'Unpaid';
            } else {
                $record->status_pembayaran = 'Paid';
            }
            $record->save();
        }

        return redirect()->back()->with('success', 'Data berhasil diperbarui.');
    }

    public function updateBillpers(Request $request, $id)
    {
        $billper = Billpers::findOrFail($id);
        $billper->nama = $request->input('nama');
        $billper->no_inet = $request->input('no_inet');
        $billper->saldo = $request->input('saldo');
        $billper->no_tlf = $request->input('no_tlf');
        $billper->email = $request->input('email');
        $billper->sto = $request->input('sto');
        $billper->produk = $request->input('produk');
        $billper->umur_customer = $request->input('umur_customer');
        $billper->status_pembayaran = $request->input('status_pembayaran');
        $billper->save();

        Alert::success('Data Berhasil Diperbarui');
        return redirect()->route('billper.index');
    }


    public function export()
    {
        $allData = Billpers::select('nama', 'no_inet', 'saldo', 'no_tlf', 'email', 'sto', 'umur_customer', 'produk', 'status_pembayaran', 'bulan_tahun')->get();

        return Excel::download(new BillperExport($allData), 'data-billpers-all.xlsx');
    }

    public function downloadFilteredExcel(Request $request)
    {
        $bulanTahun = $request->input('bulan_tahun');

        // Format input bulan_tahun ke format yang sesuai dengan kebutuhan database
        $formattedBulanTahun = Carbon::createFromFormat('Y-m', $bulanTahun)->format('Y-m-d');

        // Query untuk mengambil data berdasarkan rentang bulan_tahun
        $filteredData = Billpers::where('bulan_tahun', 'like', substr($formattedBulanTahun, 0, 7) . '%')
            ->select('nama', 'no_inet', 'saldo', 'no_tlf', 'email', 'sto', 'umur_customer', 'produk', 'status_pembayaran', 'bulan_tahun')
            ->get();

        // Export data menggunakan BillperExport dengan data yang sudah difilter
        return Excel::download(new BillperExport($filteredData), 'billpers_filtered_' . $bulanTahun . '.xlsx');
    }


    public function destroyBillpers($id)
    {
        $billper = Billpers::findOrFail($id);
        $billper->delete();
        Alert::success('Data Berhasil Terhapus');
        return redirect()->route('billper.index');
    }


    // Report Billper
    public function indexreport()
    {
        $title = 'Report Billper';
        return view('super-admin.report-billper', compact('title'));
    }


    // Report Billper
    public function indexriwayat()
    {
        $title = 'Riwayat Billper';
        return view('super-admin.riwayat-billper', compact('title'));
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
