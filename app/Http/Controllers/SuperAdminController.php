<?php

namespace App\Http\Controllers;

use App\Exports\AllExport;
use App\Exports\PranpcExport;
use App\Exports\SalesReportBillperExisting;
use App\Exports\SalesReportPranpc;
use App\Imports\DataMasterImport;
use App\Imports\PranpcImport;
use App\Models\All;
use App\Models\DataMaster;
use App\Models\Pranpc;
use App\Models\Riwayat;
use App\Models\RiwayatAll;
use App\Models\RiwayatPranpc;
use App\Models\SalesReport;
use App\Models\TempAll;
use App\Models\TempDataMaster;
use App\Models\TempPranpc;
use App\Models\User;
use App\Models\VocKendala;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use PDF;

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
        ini_set('memory_limit', '2048M');  // Increase memory limit
        set_time_limit(300);  // Increase max execution time

        // Validate the uploaded file
        $validator = Validator::make($request->all(), [
            'file1' => 'required|file|mimes:xlsx',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => 'File validation failed.']);
        }

        try {
            // Load the spreadsheet
            $file1 = $request->file('file1')->getRealPath();
            $fileName1 = $request->file('file1')->getClientOriginalName();
            $spreadsheet1 = IOFactory::load($file1);
            $sheet1 = $spreadsheet1->getActiveSheet();
            $firstRow1 = $sheet1->rangeToArray('A1:Z1', null, true, false, true)[1]; // Get only the first row

            // Check if 'SND' column is present
            if (in_array('SND', $firstRow1)) {
                return response()->json(['status' => 'success', 'message' => "*Kolom SND ditemukan dalam file $fileName1"]);
            } else {
                return response()->json(['status' => 'error', 'message' => "*Kolom SND tidak ditemukan dalam file $fileName1"]);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error processing file: ' . $e->getMessage()]);
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
        $notFound = [];

        foreach ($data1 as $index1 => $row1) {
            if ($index1 == 0) continue; // Skip header row

            $lookupValue = $row1[array_search('SND', $data1[0])];

            // New logic for checking the first 4 digits
            if (substr($lookupValue, 0, 4) !== '1523') {
                $lookupValue = $row1[array_search('SND_GROUP', $data1[0])];
                if (!$lookupValue) {
                    $lookupValue = $row1[array_search('SND', $data1[0])];
                }
            }

            // Fetch from database
            $dataMaster = DB::table('data_masters')->where('event_source', $lookupValue)->first();

            if ($dataMaster) {
                $nper = $row1[array_search('NPER', $data1[0])];
                $nperFormatted = date('Y-m', strtotime(substr($nper, 0, 4) . '-' . substr($nper, 4, 2) . '-01'));

                // Process UMUR_CUSTOMER
                $umurCustomerRaw = $row1[array_search('DATMS', $data1[0])];
                $umurCustomerFormatted = date('Y-m-d', strtotime(substr($umurCustomerRaw, 0, 4) . '-' . substr($umurCustomerRaw, 4, 2) . '-' . substr($umurCustomerRaw, 6, 2)));

                // Calculate age in full months including days
                $dateNow = \Carbon\Carbon::now();
                $umurCustomerDate = \Carbon\Carbon::createFromFormat('Y-m-d', $umurCustomerFormatted);
                $ageInYears = $dateNow->diffInYears($umurCustomerDate);
                $ageInMonths = $dateNow->diffInMonths($umurCustomerDate) % 12;
                $ageInDays = $dateNow->diffInDays($umurCustomerDate) % 30;
                $totalMonths = ($ageInYears * 12) + $ageInMonths;

                // Adjust total months for days
                if ($ageInDays >= 30) {
                    $totalMonths++;
                }

                // Determine age range
                if ($totalMonths <= 3) {
                    $ageRange = '00-03 Bln';
                } elseif ($totalMonths <= 6) {
                    $ageRange = '04-06 Bln';
                } elseif ($totalMonths <= 9) {
                    $ageRange = '07-09 Bln';
                } elseif ($totalMonths <= 12) {
                    $ageRange = '10-12 Bln';
                } else {
                    $ageRange = '>12 Bln';
                }

                // Remove non-numeric characters from saldo
                $saldoRaw = $row1[array_search('SALDO', $data1[0])];
                $saldoFormatted = preg_replace('/[^0-9]/', '', $saldoRaw);

                $result[] = [
                    'nama' => $dataMaster->pelanggan,
                    'no_inet' => $dataMaster->event_source,
                    'saldo' => $saldoFormatted,
                    'no_tlf' => $dataMaster->mobile_contact_tel,
                    'email' => $dataMaster->email_address,
                    'sto' => $dataMaster->csto,
                    'umur_customer' => $ageRange,
                    'produk' => $row1[array_search('PRODUK', $data1[0])],
                    'nper' => $nperFormatted,
                ];
            } else {
                $nper = $row1[array_search('NPER', $data1[0])];
                $nperFormatted = date('Y-m', strtotime(substr($nper, 0, 4) . '-' . substr($nper, 4, 2) . '-01'));
                $notFound[] = [
                    'nama' => 'N/A',
                    'no_inet' => $lookupValue,
                    'saldo' => '0',
                    'no_tlf' => 'N/A',
                    'email' => 'N/A',
                    'sto' => 'N/A',
                    'umur_customer' => 'N/A',
                    'produk' => 'N/A',
                    'nper' => $nperFormatted,
                ];
            }
        }

        // Insert the result into the database
        foreach ($result as $row) {
            DB::table('temp_alls')->insert([
                'nama' => $row['nama'] ?: 'N/A',
                'no_inet' => $row['no_inet'] ?: 'N/A',
                'saldo' => $row['saldo'] ?: 'N/A',
                'no_tlf' => $row['no_tlf'] ?: 'N/A',
                'email' => $row['email'] ?: 'N/A',
                'sto' => $row['sto'] ?: 'N/A',
                'umur_customer' => $row['umur_customer'] ?: 'N/A',
                'produk' => $row['produk'] ?: 'N/A',
                'status_pembayaran' => 'Unpaid', // Set all to 'Unpaid'
                'nper' => $row['nper'] ?: 'N/A',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
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
                'saldo' => $row->saldo ?: '0',
                'no_tlf' => $row->no_tlf ?: 'N/A',
                'email' => $row->email ?: 'N/A',
                'sto' => $row->sto ?: 'N/A',
                'umur_customer' => $row->umur_customer ?: 'N/A',
                'produk' => $row->produk ?: 'N/A',
                'status_pembayaran' => 'Unpaid', // Set all to 'Unpaid'
                'nper' => $row->nper ?: 'N/A',
                'users_id' => $row->users_id ?: null,
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
        $requiredHeaders = ['EVENT_SOURCE', 'CSTO', 'MOBILE_CONTACT_TEL', 'EMAIL_ADDRESS', 'PELANGGAN', 'ALAMAT_PELANGGAN'];
        $missingHeaders = array_diff($requiredHeaders, $headerRow);

        if (!empty($missingHeaders)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kolom: ' . implode(', ', $missingHeaders) . 'Tidak Ditemukan',
            ]);
        }

        return response()->json(['status' => 'success', 'message' => '*Kolom Sesuai dengan database.']);
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

        // Import the data with chunking and without events
        TempDataMaster::withoutEvents(function () use ($file) {
            Excel::import(new DataMasterImport, $file);
        });

        Alert::success('Data Berhasil Terinput');

        return redirect()->route('previewdatamaster.index');
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


    // Preview Data Master
    public function indexpreviewdatamaster()
    {
        confirmDelete();
        $title = 'Preview Data Master';
        $temp_data_masters = TempDataMaster::all();
        return view('super-admin.preview-data-master', compact('title', 'temp_data_masters'));
    }

    public function getPreviewDatamasters(Request $request)
    {
        if ($request->ajax()) {
            $temp_data_masters = TempDataMaster::all();
            return datatables()->of($temp_data_masters)
                ->addIndexColumn()
                ->addColumn('opsi-tabel-previewdatamaster', function ($masters) {
                    return view('components.opsi-tabel-previewdatamaster', compact('masters'));
                })
                ->toJson();
        }
    }

    public function editpreviewdatamasters($id)
    {
        $title = 'Edit Preview Data Master';
        $preview_data_master = TempDataMaster::findOrFail($id);
        return view('super-admin.edit-previewdatamaster', compact('title', 'preview_data_master'));
    }

    public function updatepreviewdatamasters(Request $request, $id)
    {
        $preview_data_master = TempDataMaster::findOrFail($id);
        $preview_data_master->event_source = $request->input('event_source');
        $preview_data_master->csto = $request->input('csto');
        $preview_data_master->mobile_contact_tel = $request->input('mobile_contact_tel');
        $preview_data_master->email_address = $request->input('email_address');
        $preview_data_master->pelanggan = $request->input('pelanggan');
        $preview_data_master->alamat_pelanggan = $request->input('alamat_pelanggan');
        $preview_data_master->save();

        Alert::success('Data Berhasil Diperbarui');
        return redirect()->route('previewdatamaster.index');
    }

    public function savetempdatamasters()
    {
        // Ambil data dari temp_alls
        $tempDataMaster = TempDataMaster::all();


        // Insert data ke alls
        foreach ($tempDataMaster as $row) {
            DB::table('data_masters')->insert([
                'event_source' => $row->event_source ?: 'N/A',
                'csto' => $row->csto ?: 'N/A',
                'mobile_contact_tel' => $row->mobile_contact_tel ?: 'N/A',
                'email_address' => $row->email_address ?: 'N/A',
                'pelanggan' => $row->pelanggan ?: 'N/A',
                'alamat_pelanggan' => $row->alamat_pelanggan ?: 'N/A',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Kosongkan table temp_alls
        TempDataMaster::truncate();


        // Redirect ke halaman tools.index atau halaman lainnya
        Alert::success('Data Berhasil Tersimpan');
        return redirect()->route('datamaster.index')->with('success', 'Data Berhasil Tersimpan');
    }


    public function deleteAllTempdatamasters()
    {
        // Kosongkan table temp_alls
        TempDataMaster::truncate();

        Alert::success('Data Berhasil Terhapus');
        return redirect()->route('datamaster.index');
    }

    public function destroytempdatamasters($id)
    {
        $data_master = TempDataMaster::findOrFail($id);
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


    public function updatealls(Request $request, $id)
    {
        $all = All::findOrFail($id);

        // Update data dengan data baru
        $all->nama = $request->input('nama');
        $all->no_inet = $request->input('no_inet');
        $all->saldo = $request->input('saldo');
        $all->no_tlf = $request->input('no_tlf');
        $all->email = $request->input('email');
        $all->sto = $request->input('sto');
        $all->produk = $request->input('produk');
        $all->umur_customer = $request->input('umur_customer');
        $all->status_pembayaran = $request->input('status_pembayaran');
        $all->nper = $request->input('nper');
        $all->save();

        // Simpan data yang sudah diperbarui ke tabel riwayat
        RiwayatAll::create([
            'nama' => $all->nama,
            'no_inet' => $all->no_inet,
            'saldo' => $all->saldo,
            'no_tlf' => $all->no_tlf,
            'email' => $all->email,
            'sto' => $all->sto,
            'umur_customer' => $all->umur_customer,
            'produk' => $all->produk,
            'status_pembayaran' => $all->status_pembayaran,
            'nper' => $all->nper,
        ]);

        Alert::success('Data Berhasil Diperbarui');
        return redirect()->route('all.index');
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

        Log::info('Total items in $sndList: ' . count($sndList));
        Log::info('Content of $sndList:', $sndList);

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




    public function export()
    {
        $allData = All::select('nama', 'no_inet', 'saldo', 'no_tlf', 'email', 'sto', 'umur_customer', 'produk', 'status_pembayaran', 'nper')->get();

        return Excel::download(new AllExport($allData), 'Data-Billper-Existing.xlsx');
    }

    public function downloadFilteredExcel(Request $request)
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



    public function destroyalls($id)
    {
        $all = All::findOrFail($id);
        $all->delete();
        Alert::success('Data Berhasil Terhapus');
        return redirect()->route('all.index');
    }


    public function indexallriwayat()
    {
        $title = 'Data Riwayat All';
        return view('super-admin.riwayat-data-all', compact('title'));
    }

    public function getDataallsriwayat(Request $request)
    {
        if ($request->ajax()) {
            $data_alls = RiwayatAll::query(); // atau gunakan paginate jika perlu
            return datatables()->of($data_alls)->toJson();
        }
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
        $show_all = $request->input('show_all');

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

        // Exclude rows where sto or umur_customer is 'N/A'
        if ($group_column === 'sto') {
            $query->where('sto', '!=', 'N/A');
        } elseif ($group_column === 'umur_customer') {
            $query->where('umur_customer', '!=', 'N/A');
        }

        // Apply filter by nper if show_all is not checked
        if (!$show_all && $nper) {
            $query->where('nper', $nper);
        }

        $reports = $query->groupBy($group_column)->get();

        $total_ssl = $reports->sum('total_ssl');
        $total_saldo = $reports->sum('total_saldo');
        $total_paid = $reports->sum('total_paid');
        $total_unpaid = $reports->sum('total_unpaid');

        return view('super-admin.report-data', compact('title', 'reports', 'total_ssl', 'total_saldo', 'total_paid', 'total_unpaid', 'nper', 'filter_type', 'show_all', 'riwayats'));
    }



    // TOOL Pranpc
    public function indextoolpranpc()
    {
        confirmDelete();
        $title = 'Tool Pranpc';
        $temp_pranpcs = TempPranpc::all();
        return view('super-admin.tools-pranc', compact('title', 'temp_pranpcs'));
    }

    public function getDataTemppranpcs(Request $request)
    {
        if ($request->ajax()) {
            $data_tempalls = TempPranpc::all();
            return datatables()->of($data_tempalls)
                ->addIndexColumn()
                ->addColumn('opsi-tabel-datatemppranpc', function ($temppranpc) {
                    return view('components.opsi-tabel-temppranpc', compact('temppranpc'));
                })
                ->toJson();
        }
    }

    public function checkFile1pranpc(Request $request)
    {
        ini_set('memory_limit', '2048M');  // Increase memory limit
        set_time_limit(300);  // Increase max execution time

        // Validate the uploaded file
        $validator = Validator::make($request->all(), [
            'file1' => 'required|file|mimes:xlsx',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => 'File validation failed.']);
        }

        try {
            // Load the spreadsheet
            $file1 = $request->file('file1')->getRealPath();
            $fileName1 = $request->file('file1')->getClientOriginalName();
            $spreadsheet1 = IOFactory::load($file1);
            $sheet1 = $spreadsheet1->getActiveSheet();
            $firstRow1 = $sheet1->rangeToArray('A1:AZ1', null, true, false, true)[1]; // Get only the first row

            // Define required columns
            $requiredColumns = ['SND', 'NAMA', 'ALAMAT', 'BILL_BLN', 'BILL_BLN1', 'MULTI_KONTAK1', 'NO_HP', 'EMAIL', 'MINTGK', 'MAXTGK'];
            $missingColumns = [];

            // Check for missing columns
            foreach ($requiredColumns as $column) {
                if (!in_array($column, $firstRow1)) {
                    $missingColumns[] = $column;
                }
            }

            // Return appropriate response
            if (!empty($missingColumns)) {
                $missingColumnsString = implode(', ', $missingColumns);
                return response()->json(['status' => 'error', 'message' => "*Kolom $missingColumnsString tidak ditemukan dalam file $fileName1"]);
            } else {
                return response()->json(['status' => 'success', 'message' => "*Semua kolom yang diperlukan ditemukan dalam file $fileName1"]);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error processing file: ' . $e->getMessage()]);
        }
    }


    public function upload(Request $request)
    {
        ini_set('memory_limit', '2048M');  // Increase memory limit
        set_time_limit(300);  // Increase max execution time

        $validator = Validator::make($request->all(), [
            'file1' => 'required|file|mimes:xlsx',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $file = $request->file('file1');

        // Import the data with chunking and without events
        TempPranpc::withoutEvents(function () use ($file) {
            Excel::import(new PranpcImport, $file);
        });

        Alert::success('Data Berhasil Terinput');

        return redirect()->route('toolspranpc.index');
    }

    public function savetemppranpcs()
    {
        // Ambil data dari temp_pranpcs
        $tempPranpcs = TempPranpc::all();

        // Insert data ke pranpcs
        foreach ($tempPranpcs as $row) {
            DB::table('pranpcs')->insert([
                'nama' => $row->nama ?: 'N/A',
                'snd' => $row->snd ?: 'N/A',
                'sto' => $row->sto ?: 'N/A',
                'alamat' => $row->alamat ?: 'N/A',
                'multi_kontak1' => $row->multi_kontak1 ?: 'N/A',
                'email' => $row->email ?: 'N/A',
                'bill_bln' => $row->bill_bln ?: 'N/A',
                'bill_bln1' => $row->bill_bln1 ?: 'N/A',
                'mintgk' => $row->mintgk ?: 'N/A',
                'maxtgk' => $row->maxtgk ?: 'N/A',
                'status_pembayaran' => $row->status_pembayaran,
                'users_id' => $row->users_id ?: null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Kosongkan table temp_pranpcs
        TempPranpc::truncate();


        // Redirect ke halaman tools.index atau halaman lainnya
        Alert::success('Data Berhasil Tersimpan');
        return redirect()->route('toolspranpc.index')->with('success', 'Data Berhasil Tersimpan');
    }

    public function deleteAllTemppranpcs()
    {
        // Kosongkan table temp_pranpcs
        TempPranpc::truncate();

        Alert::success('Data Berhasil Terhapus');
        return redirect()->route('toolspranpc.index');
    }

    public function destroyTemppranpcs($id)
    {
        $data = TempPranpc::findOrFail($id);
        $data->delete();
        Alert::success('Data Berhasil Terhapus');
        return redirect()->route('toolspranpc.index');
    }


    // Data PraNPC
    public function indexpranpc()
    {
        confirmDelete();
        $title = 'Data PraNPC';
        $pranpcs = Pranpc::all();
        return view('super-admin.data-pranpc', compact('title', 'pranpcs'));
    }

    public function getDatapranpcs(Request $request)
    {
        if ($request->ajax()) {
            $query = Pranpc::query();

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
                ->addColumn('opsi-tabel-datapranpc', function ($pranpc) {
                    return view('components.opsi-tabel-datapranpc', compact('pranpc'));
                })
                ->toJson();
        }
    }

    public function viewgeneratePDFpranpc(Request $request, $id)
    {
        $pranpc = Pranpc::findOrFail($id);
        $total_tagihan = 'RP. ' . number_format($pranpc->bill_bln + $pranpc->bill_bln1, 2, ',', '.');

        $mintgkDate = \Carbon\Carbon::parse($pranpc->mintgk);
        $maxtgkDate = \Carbon\Carbon::parse($pranpc->maxtgk);

        // Mendapatkan path gambar dan mengubahnya menjadi format base64
        $imagePath = public_path('storage/file_assets/logo-telkom.png');
        $imageData = base64_encode(file_get_contents($imagePath));
        $imageSrc = 'data:image/png;base64,' . $imageData;

        $data = [
            'pranpc' => $pranpc,
            'total_tagihan' => $total_tagihan,
            'date' => now()->format('d/m/Y'),
            'nomor_surat' => $request->nomor_surat,
            'mintgk_bulan' => $mintgkDate->translatedFormat('F Y'),
            'maxtgk_bulan' => $maxtgkDate->translatedFormat('F Y'),
            'image_src' => $imageSrc,  // Menyertakan gambar sebagai data base64
        ];

        return view('components.generate-pdf-pranpc', $data);
    }




    public function generatePDFpranpc(Request $request, $id)
    {
        $pranpc = Pranpc::findOrFail($id);
        $total_tagihan = 'RP. ' . number_format($pranpc->bill_bln + $pranpc->bill_bln1, 2, ',', '.');

        $mintgkDate = \Carbon\Carbon::parse($pranpc->mintgk);
        $maxtgkDate = \Carbon\Carbon::parse($pranpc->maxtgk);

        $imagePath = public_path('storage/file_assets/logo-telkom.png');
        $imageData = base64_encode(file_get_contents($imagePath));
        $src = 'data:image/png;base64,' . $imageData;

        $data = [
            'pranpc' => $pranpc,
            'total_tagihan' => $total_tagihan,
            'date' => now()->format('d/m/Y'),
            'nomor_surat' => $request->nomor_surat,
            'mintgk_bulan' => $mintgkDate->translatedFormat('F Y'),
            'maxtgk_bulan' => $maxtgkDate->translatedFormat('F Y'),
            'image_src' => $src,
        ];

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('components.generate-pdf-pranpc', $data);
        return $pdf->download('invoice.pdf');
    }



    public function editpranpcs($id)
    {
        $title = 'Edit Data PraNPC';
        $pranpc = Pranpc::findOrFail($id);
        return view('super-admin.edit-pranpc', compact('title', 'pranpc'));
    }
    public function updatepranpcs(Request $request, $id)
    {
        $pranpc = Pranpc::findOrFail($id);

        // Update data dengan data baru
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

        // Simpan data yang sudah diperbarui ke tabel riwayat
        RiwayatPranpc::create([
            'nama' => $pranpc->nama,
            'status_pembayaran' => $pranpc->status_pembayaran,
            'snd' => $pranpc->snd,
            'sto' => $pranpc->sto,
            'bill_bln' => $pranpc->bill_bln,
            'bill_bln1' => $pranpc->bill_bln1,
            'mintgk' => $pranpc->mintgk,
            'maxtgk' => $pranpc->maxtgk,
            'multi_kontak1' => $pranpc->multi_kontak1,
            'email' => $pranpc->email,
            'alamat' => $pranpc->alamat,
        ]);

        Alert::success('Data Berhasil Diperbarui');
        return redirect()->route('pranpc.index');
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


    public function destroypranpcs($id)
    {
        $pranpc = Pranpc::findOrFail($id);
        $pranpc->delete();
        Alert::success('Data Berhasil Terhapus');
        return redirect()->route('pranpc.index');
    }

    public function indexpranpcriwayat()
    {
        confirmDelete();
        $title = 'Data Riwayat PraNPC';
        return view('super-admin.riwayat-data-pranpc', compact('title'));
    }

    public function getDatapranpcsriwayat(Request $request)
    {
        if ($request->ajax()) {
            $data_pranpcs = RiwayatPranpc::query(); // atau gunakan paginate jika perlu
            return datatables()->of($data_pranpcs)->toJson();
        }
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


    // report sales BIllper Existing
    public function indexreportsalesbillperexisting(Request $request)
    {
        $title = 'Report Sales Billper Existing';
        confirmDelete();

        // Get filter values from request
        $filterMonth = $request->input('month', now()->format('m'));
        $filterYear = $request->input('year', now()->format('Y'));
        $filterSales = $request->input('filter_sales', '');
        $jenisBiling = $request->input('jenis_biling', ''); // New filter

        // Calculate the current date in 'Y-m' format
        $currentMonth = Carbon::now()->format('Y-m');

        // Retrieve all voc_kendalas and their related report counts for the specified month, year, and sales
        $voc_kendalas = VocKendala::withCount(['salesReports' => function ($query) use ($filterMonth, $filterYear, $filterSales, $jenisBiling, $currentMonth) {
            $query->whereYear('created_at', $filterYear)
                ->whereMonth('created_at', $filterMonth)
                ->whereNotNull('all_id'); // Ensure only records with all_id are included

            // Apply sales filter if provided
            if ($filterSales) {
                $query->whereHas('user', function ($q) use ($filterSales) {
                    $q->where('name', $filterSales);
                });
            }

            // Apply jenis_biling filter if provided
            if ($jenisBiling === 'Billper') {
                $query->whereHas('alls', function ($q) use ($currentMonth) {
                    $q->where('nper', $currentMonth);
                });
            } elseif ($jenisBiling === 'Existing') {
                $query->whereHas('alls', function ($q) use ($currentMonth) {
                    $q->where('nper', '<', $currentMonth);
                });
            }
        }])->get();

        // Retrieve all sales with total assignments and total visits
        $sales = User::where('level', 'user')
            ->withCount([
                'alls as total_assignment' => function ($query) use ($filterMonth, $filterYear, $currentMonth, $jenisBiling) {
                    $query->whereYear('created_at', $filterYear)
                        ->whereMonth('created_at', $filterMonth);

                    // Apply jenis_biling filter if provided
                    if ($jenisBiling === 'Billper') {
                        $query->where('nper', $currentMonth);
                    } elseif ($jenisBiling === 'Existing') {
                        $query->where('nper', '<', $currentMonth);
                    }
                },
                'salesReports as total_visit' => function ($query) use ($filterMonth, $filterYear) {
                    $query->whereYear('created_at', $filterYear)
                        ->whereMonth('created_at', $filterMonth)
                        ->whereNotNull('all_id');
                }
            ])
            ->get();

        // Calculate wo_sudah_visit and wo_belum_visit manually
        foreach ($sales as $sale) {
            $wo_sudah_visit = DB::table('sales_reports')
                ->whereYear('created_at', $filterYear)
                ->whereMonth('created_at', $filterMonth)
                ->where('users_id', $sale->id)
                ->whereNotNull('all_id')
                ->distinct('all_id')
                ->count('all_id');

            $sale->wo_sudah_visit = $wo_sudah_visit;
            $sale->wo_belum_visit = $sale->total_assignment - $wo_sudah_visit;
        }

        return view('super-admin.report-salesbillperexisting', compact('title', 'voc_kendalas', 'filterMonth', 'filterYear', 'sales', 'filterSales', 'jenisBiling'));
    }

    public function getDatareportbillpersuperadmin(Request $request)
    {
        if ($request->ajax()) {
            $filterMonth = $request->input('month', now()->format('m'));
            $filterYear = $request->input('year', now()->format('Y'));
            $filterSales = $request->input('filter_sales', '');
            $jenisBiling = $request->input('jenis_biling', ''); // New filter

            // Calculate the current date in 'Y-m' format
            $currentMonth = Carbon::now()->format('Y-m');

            $data_report_billper = SalesReport::with('alls', 'user', 'vockendals')
                ->whereNotNull('all_id') // Ensure only records with all_id are included
                ->whereYear('created_at', $filterYear)
                ->whereMonth('created_at', $filterMonth);

            // Apply sales filter if provided
            if ($filterSales) {
                $data_report_billper->whereHas('user', function ($query) use ($filterSales) {
                    $query->where('name', $filterSales);
                });
            }

            // Apply jenis_biling filter if provided
            if ($jenisBiling === 'Billper') {
                $data_report_billper->whereHas('alls', function ($query) use ($currentMonth) {
                    $query->where('nper', $currentMonth);
                });
            } elseif ($jenisBiling === 'Existing') {
                $data_report_billper->whereHas('alls', function ($query) use ($currentMonth) {
                    $query->where('nper', '<', $currentMonth);
                });
            }

            $data_report_billper = $data_report_billper->get();

            return datatables()->of($data_report_billper)
                ->addIndexColumn()
                ->addColumn('evidence', function ($row) {
                    return view('components.evidence-buttons-adminbillper', compact('row'));
                })
                ->toJson();
        }
    }

    public function downloadAllExcelreportbillpersuperadmin()
    {
        $reports = SalesReport::with('alls', 'user', 'vockendals')
            ->whereNotNull('all_id') // Ensure only records with all_id are included
            ->get();

        return Excel::download(new SalesReportBillperExisting($reports), 'Report_Billper-Existing_Semua.xlsx');
    }

    public function downloadFilteredExcelreportbillpersuperadmin(Request $request)
    {
        $reports = SalesReport::with('alls', 'user', 'vockendals')
            ->whereNotNull('all_id') // Ensure only records with all_id are included
            ->when($request->tahun_bulan, function ($query) use ($request) {
                $query->whereMonth('created_at', Carbon::parse($request->tahun_bulan)->month)
                    ->whereYear('created_at', Carbon::parse($request->tahun_bulan)->year);
            })
            ->when($request->nama_sales, function ($query) use ($request) {
                $query->whereHas('user', function ($q) use ($request) {
                    $q->where('name', $request->nama_sales);
                });
            })
            ->when($request->voc_kendala, function ($query) use ($request) {
                $query->whereHas('vockendals', function ($q) use ($request) {
                    $q->where('voc_kendala', $request->voc_kendala);
                });
            })
            ->get();

        // Buat nama file dinamis berdasarkan filter yang dipilih
        $fileName = 'filtered_reports';

        if ($request->tahun_bulan) {
            $fileName .= '_' . str_replace('-', '', $request->tahun_bulan);
        }

        if ($request->nama_sales) {
            $fileName .= '_' . str_replace(' ', '_', $request->nama_sales);
        }

        if ($request->voc_kendala) {
            $fileName .= '_' . str_replace(' ', '_', $request->voc_kendala);
        }

        $fileName .= '.xlsx';

        return Excel::download(new SalesReportBillperExisting($reports), $fileName);
    }



    // report sales Pranpc
    public function indexreportsalespranpc(Request $request)
    {
        $title = 'Report Sales Pranpc';
        // Get filter values from request
        $filterMonth = $request->input('month', now()->format('m'));
        $filterYear = $request->input('year', now()->format('Y'));
        $filterSales = $request->input('filter_sales', ''); // New filter

        // Retrieve all voc_kendalas and their related report counts for the specified month and year
        // and include reports with a non-null pranpc_id
        $voc_kendalas = VocKendala::withCount(['salesReports' => function ($query) use ($filterMonth, $filterYear, $filterSales) {
            $query->whereYear('created_at', $filterYear)
                ->whereMonth('created_at', $filterMonth)
                ->whereNotNull('pranpc_id'); // Ensure only records with pranpc_id are included

            // Apply sales filter if provided
            if ($filterSales) {
                $query->whereHas('user', function ($q) use ($filterSales) {
                    $q->where('name', $filterSales);
                });
            }
        }])->get();

        // Retrieve all sales with total assignments and total visits
        $sales = User::where('level', 'user')
            ->withCount(['pranpcs as total_assignment' => function ($query) use ($filterMonth, $filterYear) {
                $query->whereYear('created_at', $filterYear)
                    ->whereMonth('created_at', $filterMonth);
            }, 'salesReports as total_visit' => function ($query) use ($filterMonth, $filterYear) {
                $query->whereYear('created_at', $filterYear)
                    ->whereMonth('created_at', $filterMonth)
                    ->whereNotNull('pranpc_id');
            }])
            ->get();

        // Calculate wo_sudah_visit and wo_belum_visit manually
        foreach ($sales as $sale) {
            $wo_sudah_visit = DB::table('sales_reports')
                ->whereYear('created_at', $filterYear)
                ->whereMonth('created_at', $filterMonth)
                ->where('users_id', $sale->id)
                ->distinct('pranpc_id')
                ->count('pranpc_id');

            $sale->wo_sudah_visit = $wo_sudah_visit;
            $sale->wo_belum_visit = $sale->total_assignment - $wo_sudah_visit;
        }

        return view('super-admin.report-salespranpc', compact('title', 'voc_kendalas', 'filterMonth', 'filterYear', 'sales', 'filterSales'));
    }

    public function getDatareportpranpcsuperadmin(Request $request)
    {
        if ($request->ajax()) {
            // Get filter values from request
            $filterMonth = $request->input('month', now()->format('m'));
            $filterYear = $request->input('year', now()->format('Y'));
            $filterSales = $request->input('filter_sales', ''); // New filter

            // Build the query with filters
            $data_report_pranpc = SalesReport::with('pranpcs', 'user', 'vockendals')
                ->whereNotNull('pranpc_id') // Ensure only records with pranpc_id are included
                ->whereYear('created_at', $filterYear)
                ->whereMonth('created_at', $filterMonth);

            // Apply sales filter if provided
            if ($filterSales) {
                $data_report_pranpc->whereHas('user', function ($query) use ($filterSales) {
                    $query->where('name', $filterSales);
                });
            }

            $data_report_pranpc = $data_report_pranpc->get();

            return datatables()->of($data_report_pranpc)
                ->addIndexColumn()
                ->addColumn('evidence', function ($row) {
                    return view('components.evidence-pranpc-buttons-adminpranpc', compact('row'));
                })
                ->toJson();
        }
    }



    public function downloadAllExcelreportpranpcsuperadmin()
    {
        $reports = SalesReport::with('pranpcs', 'user', 'vockendals')
            ->whereNotNull('pranpc_id') // Ensure only records with pranpc_id are included
            ->get();

        return Excel::download(new SalesReportPranpc($reports), 'Report_Pranpc_Semua.xlsx');
    }

    public function downloadFilteredExcelreportpranpcsuperadmin(Request $request)
    {
        $reports = SalesReport::with('pranpcs', 'user', 'vockendals')
            ->whereNotNull('pranpc_id') // Ensure only records with pranpc_id are included
            ->when($request->tahun_bulan, function ($query) use ($request) {
                $query->whereMonth('created_at', Carbon::parse($request->tahun_bulan)->month)
                    ->whereYear('created_at', Carbon::parse($request->tahun_bulan)->year);
            })
            ->when($request->nama_sales, function ($query) use ($request) {
                $query->whereHas('user', function ($q) use ($request) {
                    $q->where('name', $request->nama_sales);
                });
            })
            ->when($request->voc_kendala, function ($query) use ($request) {
                $query->whereHas('vockendals', function ($q) use ($request) {
                    $q->where('voc_kendala', $request->voc_kendala);
                });
            })
            ->get();

        // Buat nama file dinamis berdasarkan filter yang dipilih
        $fileName = 'filtered_reports';

        if ($request->tahun_bulan) {
            $fileName .= '_' . str_replace('-', '', $request->tahun_bulan);
        }

        if ($request->nama_sales) {
            $fileName .= '_' . str_replace(' ', '_', $request->nama_sales);
        }

        if ($request->voc_kendala) {
            $fileName .= '_' . str_replace(' ', '_', $request->voc_kendala);
        }

        $fileName .= '.xlsx';

        return Excel::download(new SalesReportPranpc($reports), $fileName);
    }
}
