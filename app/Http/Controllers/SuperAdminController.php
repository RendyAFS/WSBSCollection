<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;

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
        return view('super-admin.tools', compact('title'));
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
        set_time_limit(300);

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
                        'NAMA' => $row2[array_search('PELANGGAN', $data2[0])],
                        'No. Inet' => $row2[array_search('EVENT_SOURCE', $data2[0])],
                        'SALDO' => $row1[array_search('SALDO', $data1[0])],
                        'No. Tlf' => $row2[array_search('MOBILE_CONTACT_TEL', $data2[0])],
                        'Email' => $row2[array_search('EMAIL_ADDRESS', $data2[0])],
                        'STO' => $row2[array_search('CSTO', $data2[0])],
                        'UMUR_CUSTOMER' => $row1[array_search('UMUR_CUSTOMER', $data1[0])],
                    ];
                    break;
                }
            }
        }

        $newSpreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $newSheet = $newSpreadsheet->getActiveSheet();
        $newSheet->fromArray(array_merge([['NAMA', 'No. Inet', 'SALDO', 'No. Tlf', 'Email', 'STO', 'UMUR_CUSTOMER']], $result));

        $resultFilePath = storage_path('app/public/result.xlsx');
        $writer = IOFactory::createWriter($newSpreadsheet, 'Xlsx');
        $writer->save($resultFilePath);

        return response()->download($resultFilePath);
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
