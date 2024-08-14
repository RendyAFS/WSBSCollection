<?php

namespace App\Http\Controllers;

use App\Exports\AllExport;
use App\Exports\PranpcExport;
use App\Exports\SalesReportBillperExisting;
use App\Exports\SalesReportPranpc;
use App\Imports\DataMasterImport;
use App\Imports\PranpcImport;
use App\Models\Billper;
use App\Models\DataMaster;
use App\Models\Existing;
use App\Models\Pranpc;
use App\Models\Riwayat;
use App\Models\RiwayatBillper;
use App\Models\RiwayatExisting;
use App\Models\RiwayatPranpc;
use App\Models\SalesReport;
use App\Models\TempBillper;
use App\Models\TempDataMaster;
use App\Models\TempExisting;
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
use Illuminate\Validation\Rules\Exists;
use PDF;

class SuperAdminController extends Controller
{
    // DASHBOARD
    public function index()
    {
        $title = 'Dashboard';

        // Menghitung jumlah data dari setiap model
        $billperCount = Billper::count();
        $existingCount = Existing::count();
        $dataMasterCount = DataMaster::count();

        // Menghitung jumlah data untuk bulan ini
        $currentMonthYear = date('Y-m');
        $lastMonth = date('Y-m', strtotime('-1 month'));

        // Untuk tipe created_at dan update_at
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;



        // Billper
        $billperCurrentMonthCount = Billper::where('nper', $currentMonthYear)->count();
        $billperLastMonthCount = Billper::where('nper', $lastMonth)->count();
        $percentBillper = $billperLastMonthCount > 0 ? (($billperCurrentMonthCount - $billperLastMonthCount) / $billperLastMonthCount) * 100 : 0;

        // Existing
        $existingCurrentMonthCount = Existing::where('nper', $currentMonthYear)->count();
        $existingLastMonthCount = Existing::where('nper', $lastMonth)->count();
        $percentExisting = $existingLastMonthCount > 0 ? (($existingCurrentMonthCount - $existingLastMonthCount) / $existingLastMonthCount) * 100 : 0;

        // Data Master
        $dataMasterCurrentMonthCount = DataMaster::whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->count();
        $dataMasterLastMonthCount = DataMaster::whereMonth('created_at', date('m', strtotime('-1 month')))->whereYear('created_at', date('Y', strtotime('-1 month')))->count();
        $percentDataMaster = $dataMasterLastMonthCount > 0 ? (($dataMasterCurrentMonthCount - $dataMasterLastMonthCount) / $dataMasterLastMonthCount) * 100 : 0;


        // Mengambil 3 kendala teratas dengan mengabaikan kendala dengan data "Tidak Ada"
        $topKendalabillper = SalesReport::select('voc_kendalas.voc_kendala', DB::raw('COUNT(sales_reports.id) as count'))
            ->join('voc_kendalas', 'sales_reports.voc_kendalas_id', '=', 'voc_kendalas.id')
            ->whereNotNull('sales_reports.billper_id')
            ->where('voc_kendalas.voc_kendala', '!=', 'Tidak Ada')
            ->whereMonth('sales_reports.updated_at', $currentMonth)
            ->whereYear('sales_reports.updated_at', $currentYear)
            ->groupBy('voc_kendalas.voc_kendala')
            ->orderBy('count', 'desc')
            ->limit(3)
            ->get();

        // Menyiapkan data untuk chart
        $labelbillper = $topKendalabillper->pluck('voc_kendala');
        $databillper = $topKendalabillper->pluck('count');


        // Mengambil 3 kendala teratas dengan mengabaikan kendala dengan data "Tidak Ada"
        $topKendalaexisting = SalesReport::select('voc_kendalas.voc_kendala', DB::raw('COUNT(sales_reports.id) as count'))
            ->join('voc_kendalas', 'sales_reports.voc_kendalas_id', '=', 'voc_kendalas.id')
            ->whereNotNull('sales_reports.existing_id')
            ->where('voc_kendalas.voc_kendala', '!=', 'Tidak Ada')
            ->whereMonth('sales_reports.updated_at', $currentMonth)
            ->whereYear('sales_reports.updated_at', $currentYear)
            ->groupBy('voc_kendalas.voc_kendala')
            ->orderBy('count', 'desc')
            ->limit(3)
            ->get();

        // Menyiapkan data untuk chart
        $labelexisting = $topKendalaexisting->pluck('voc_kendala');
        $dataexisting = $topKendalaexisting->pluck('count');


        // Mengambil 3 kendala teratas dengan mengabaikan kendala dengan data "Tidak Ada"
        $topKendalapranpc = SalesReport::select('voc_kendalas.voc_kendala', DB::raw('COUNT(sales_reports.id) as count'))
            ->join('voc_kendalas', 'sales_reports.voc_kendalas_id', '=', 'voc_kendalas.id')
            ->whereNotNull('sales_reports.pranpc_id')
            ->where('voc_kendalas.voc_kendala', '!=', 'Tidak Ada')
            ->whereMonth('sales_reports.updated_at', $currentMonth)
            ->whereYear('sales_reports.updated_at', $currentYear)
            ->groupBy('voc_kendalas.voc_kendala')
            ->orderBy('count', 'desc')
            ->limit(3)
            ->get();

        // Menyiapkan data untuk chart
        $labelpranpc = $topKendalapranpc->pluck('voc_kendala');
        $datapranpc = $topKendalapranpc->pluck('count');



        // Menghitung jumlah produk berdasarkan jenis dari tabel Billpers
        $billperProdukCounts = DB::table('billpers')
            ->select('produk', DB::raw('count(*) as count'))
            ->where('nper', $currentMonthYear) // Menambahkan kondisi untuk bulan saat ini
            ->groupBy('produk')
            ->pluck('count', 'produk')
            ->toArray();

        // Menghitung jumlah produk berdasarkan jenis dari tabel Existing
        $existingProdukCounts = DB::table('existings')
            ->select('produk', DB::raw('count(*) as count'))
            // Menambahkan kondisi untuk bulan saat ini
            ->groupBy('produk')
            ->pluck('count', 'produk')
            ->toArray();

        // Daftar jenis produk yang ingin ditampilkan
        $jenisProduk = ['Internet', 'Telepon', 'Wifi Manage Service'];

        // Menambahkan nilai default 0 untuk jenis produk yang tidak ada di Billpers
        foreach ($jenisProduk as $jenis) {
            if (!isset($billperProdukCounts[$jenis])) {
                $billperProdukCounts[$jenis] = 0;
            }
        }

        // Menambahkan nilai default 0 untuk jenis produk yang tidak ada di Existing
        foreach ($jenisProduk as $jenis) {
            if (!isset($existingProdukCounts[$jenis])) {
                $existingProdukCounts[$jenis] = 0;
            }
        }

        // Mengurutkan array agar sesuai dengan urutan jenis produk yang diinginkan
        $billperProdukCounts = array_merge(array_flip($jenisProduk), $billperProdukCounts);
        $existingProdukCounts = array_merge(array_flip($jenisProduk), $existingProdukCounts);

        // Menghitung jumlah total produk dari tabel Billpers
        $totalProdukBillper = DB::table('billpers')
            ->where('nper', $currentMonthYear)
            ->count();

        // Menghitung jumlah total produk dari tabel Existing
        $totalProdukExisting = DB::table('existings')
            ->where('nper', $currentMonthYear)
            ->count();

        // Menjumlahkan total produk dari Billpers dan Existing
        $produkCountBillperExisting = $totalProdukBillper + $totalProdukExisting;


        // Progress Sales
        $totalVisitSales = DB::table('sales_reports')
            ->whereMonth('sales_reports.created_at', $currentMonth)
            ->whereYear('sales_reports.created_at', $currentYear)
            ->count();



        // Progress bar sales billper
        $salesbillpertertinggi = User::where('level', 'Sales')
            ->withCount([
                'billpers as total_assignment' => function ($query) use ($currentMonth, $currentYear) {
                    $query->whereYear('created_at', $currentYear)
                        ->whereMonth('created_at', $currentMonth);
                },
                'salesReports as total_visit' => function ($query) use ($currentMonth, $currentYear) {
                    $query->whereYear('created_at', $currentYear)
                        ->whereMonth('created_at', $currentMonth)
                        ->whereNotNull('billper_id');
                }
            ])
            ->orderByDesc('total_visit')
            ->limit(5)
            ->get();
        // dd($salesbillpertertinggi);

        // Calculate wo_sudah_visit and wo_belum_visit manually
        foreach ($salesbillpertertinggi as $salebillper) {
            $wo_sudah_visit = DB::table('sales_reports')
                ->whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $currentMonth)
                ->where('users_id', $salebillper->id)
                ->whereNotNull('billper_id')
                ->distinct('billper_id')
                ->count('billper_id');

            $salebillper->wo_sudah_visit = $wo_sudah_visit;
            $salebillper->wo_belum_visit = $salebillper->total_assignment - $wo_sudah_visit;
        }

        $salesbillperterendah = User::where('level', 'Sales')
            ->withCount([
                'billpers as total_assignment' => function ($query) use ($currentMonth, $currentYear) {
                    $query->whereYear('created_at', $currentYear)
                        ->whereMonth('created_at', $currentMonth);
                },
                'salesReports as total_visit' => function ($query) use ($currentMonth, $currentYear) {
                    $query->whereYear('created_at', $currentYear)
                        ->whereMonth('created_at', $currentMonth)
                        ->whereNotNull('billper_id');
                }
            ])
            ->orderBy('total_visit')  // Ascending order to get the fewest visits
            ->limit(5)
            ->get();

        // Calculate wo_sudah_visit and wo_belum_visit manually for bottom 5 sales
        foreach ($salesbillperterendah as $salebillper) {
            $wo_sudah_visit = DB::table('sales_reports')
                ->whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $currentMonth)
                ->where('users_id', $salebillper->id)
                ->whereNotNull('billper_id')
                ->distinct('billper_id')
                ->count('billper_id');

            $salebillper->wo_sudah_visit = $wo_sudah_visit;
            $salebillper->wo_belum_visit = $salebillper->total_assignment - $wo_sudah_visit;
        }
        // dd($salesbillperterendah);

        // Progress bar sales existing
        $salesexistingtertinggi = User::where('level', 'Sales')
            ->withCount([
                'existings as total_assignment' => function ($query) use ($currentMonth, $currentYear) {
                    $query->whereYear('created_at', $currentYear)
                        ->whereMonth('created_at', $currentMonth);
                },
                'salesReports as total_visit' => function ($query) use ($currentMonth, $currentYear) {
                    $query->whereYear('created_at', $currentYear)
                        ->whereMonth('created_at', $currentMonth)
                        ->whereNotNull('existing_id');
                }
            ])
            ->orderByDesc('total_visit')
            ->limit(5)
            ->get();

        // Calculate wo_sudah_visit and wo_belum_visit manually
        foreach ($salesexistingtertinggi as $saleexisting) {
            $wo_sudah_visit = DB::table('sales_reports')
                ->whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $currentMonth)
                ->where('users_id', $saleexisting->id)
                ->whereNotNull('existing_id')
                ->distinct('existing_id')
                ->count('existing_id');

            $saleexisting->wo_sudah_visit = $wo_sudah_visit;
            $saleexisting->wo_belum_visit = $saleexisting->total_assignment - $wo_sudah_visit;
        }

        $salesexistingterendah = User::where('level', 'Sales')
            ->withCount([
                'existings as total_assignment' => function ($query) use ($currentMonth, $currentYear) {
                    $query->whereYear('created_at', $currentYear)
                        ->whereMonth('created_at', $currentMonth);
                },
                'salesReports as total_visit' => function ($query) use ($currentMonth, $currentYear) {
                    $query->whereYear('created_at', $currentYear)
                        ->whereMonth('created_at', $currentMonth)
                        ->whereNotNull('existing_id');
                }
            ])
            ->orderBy('total_visit')  // Ascending order to get the fewest visits
            ->limit(5)
            ->get();

        // Calculate wo_sudah_visit and wo_belum_visit manually for bottom 5 sales
        foreach ($salesexistingterendah as $saleexisting) {
            $wo_sudah_visit = DB::table('sales_reports')
                ->whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $currentMonth)
                ->where('users_id', $saleexisting->id)
                ->whereNotNull('existing_id')
                ->distinct('existing_id')
                ->count('existing_id');

            $saleexisting->wo_sudah_visit = $wo_sudah_visit;
            $saleexisting->wo_belum_visit = $saleexisting->total_assignment - $wo_sudah_visit;
        }

        // Progress bar sales pranpc
        $salespranpctertinggi = User::where('level', 'Sales')
            ->withCount([
                'pranpcs as total_assignment' => function ($query) use ($currentMonth, $currentYear) {
                    $query->whereYear('created_at', $currentYear)
                        ->whereMonth('created_at', $currentMonth);
                },
                'salesReports as total_visit' => function ($query) use ($currentMonth, $currentYear) {
                    $query->whereYear('created_at', $currentYear)
                        ->whereMonth('created_at', $currentMonth)
                        ->whereNotNull('pranpc_id');
                }
            ])
            ->orderByDesc('total_visit')
            ->limit(5)
            ->get();

        // Calculate wo_sudah_visit and wo_belum_visit manually
        foreach ($salespranpctertinggi as $salepranpc) {
            $wo_sudah_visit = DB::table('sales_reports')
                ->whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $currentMonth)
                ->where('users_id', $salepranpc->id)
                ->whereNotNull('pranpc_id')
                ->distinct('pranpc_id')
                ->count('pranpc_id');

            $salepranpc->wo_sudah_visit = $wo_sudah_visit;
            $salepranpc->wo_belum_visit = $salepranpc->total_assignment - $wo_sudah_visit;
        }

        $salespranpcterendah = User::where('level', 'Sales')
            ->withCount([
                'pranpcs as total_assignment' => function ($query) use ($currentMonth, $currentYear) {
                    $query->whereYear('created_at', $currentYear)
                        ->whereMonth('created_at', $currentMonth);
                },
                'salesReports as total_visit' => function ($query) use ($currentMonth, $currentYear) {
                    $query->whereYear('created_at', $currentYear)
                        ->whereMonth('created_at', $currentMonth)
                        ->whereNotNull('pranpc_id');
                }
            ])
            ->orderBy('total_visit')  // Ascending order to get the fewest visits
            ->limit(5)
            ->get();

        // Calculate wo_sudah_visit and wo_belum_visit manually for bottom 5 sales
        foreach ($salespranpcterendah as $salepranpc) {
            $wo_sudah_visit = DB::table('sales_reports')
                ->whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $currentMonth)
                ->where('users_id', $salepranpc->id)
                ->whereNotNull('pranpc_id')
                ->distinct('pranpc_id')
                ->count('pranpc_id');

            $salepranpc->wo_sudah_visit = $wo_sudah_visit;
            $salepranpc->wo_belum_visit = $salepranpc->total_assignment - $wo_sudah_visit;
        }


        // Data akun
        $users = User::where('level', '!=', 'Super Admin')
            ->orderBy('status', 'desc') // Sort by created_at in ascending order
            ->limit(5)
            ->get();

        // Mengirimkan data ke tampilan view
        return view(
            'super-admin.index',
            compact(
                'title',
                'billperCount',
                'existingCount',
                'dataMasterCount',
                'percentBillper',
                'percentExisting',
                'percentDataMaster',
                'labelbillper',
                'databillper',
                'labelexisting',
                'dataexisting',
                'labelpranpc',
                'datapranpc',
                'billperProdukCounts',
                'existingProdukCounts',
                'produkCountBillperExisting',
                'totalVisitSales',
                'salesbillpertertinggi',
                'salesexistingtertinggi',
                'salespranpctertinggi',
                'salesbillperterendah',
                'salesexistingterendah',
                'salespranpcterendah',
                'users'

            )
        );
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
            $query = DataMaster::orderBy('id', 'desc');

            $totalData = $query->count();
            $start = $request->input('start');
            $length = $request->input('length');
            $draw = $request->input('draw');
            $searchValue = $request->input('search.value');

            if (!empty($searchValue)) {
                $query->where('pelanggan', 'like', '%' . $searchValue . '%')
                    ->orWhere('event_source', 'like', '%' . $searchValue . '%')
                    ->orWhere('csto', 'like', '%' . $searchValue . '%')
                    ->orWhere('mobile_contact_tel', 'like', '%' . $searchValue . '%')
                    ->orWhere('email_address', 'like', '%' . $searchValue . '%')
                    ->orWhere('alamat_pelanggan', 'like', '%' . $searchValue . '%');
            }

            $filteredData = $query->count();
            $data_masters = $query->skip($start)->take($length)->get();

            $data = [];
            foreach ($data_masters as $masters) {
                $data[] = [
                    'id' => $masters->id,
                    'pelanggan' => $masters->pelanggan,
                    'event_source' => $masters->event_source,
                    'csto' => $masters->csto,
                    'mobile_contact_tel' => $masters->mobile_contact_tel,
                    'email_address' => $masters->email_address,
                    'alamat_pelanggan' => $masters->alamat_pelanggan,
                    'opsi-tabel-datamaster' => view('components.opsi-tabel-datamaster', compact('masters'))->render()
                ];
            }

            return response()->json([
                'draw' => intval($draw),
                'recordsTotal' => $totalData,
                'recordsFiltered' => $filteredData,
                'data' => $data
            ]);
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

    public function getTempDatamasters(Request $request)
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
        // Ambil data dari temp_billpers
        $tempDataMaster = TempDataMaster::all();


        // Insert data ke billpers
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

        // Kosongkan table temp_billpers
        TempDataMaster::truncate();


        // Redirect ke halaman tools.index atau halaman lainnya
        Alert::success('Data Berhasil Tersimpan');
        return redirect()->route('datamaster.index')->with('success', 'Data Berhasil Tersimpan');
    }


    public function deleteAllTempdatamasters()
    {
        // Kosongkan table temp_billpers
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



    // TOOL Billper
    public function indextoolbillper()
    {
        confirmDelete();
        $title = 'Tools Billper';
        $temp_billpers = TempBillper::all();
        return view('super-admin.tools-billper', compact('title', 'temp_billpers'));
    }

    public function checkFile1billper(Request $request)
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


    public function vlookupbillper(Request $request)
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
                    'alamat' => $dataMaster->alamat_pelanggan,
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
                    'alamat' => 'N/A',
                    'sto' => 'N/A',
                    'umur_customer' => 'N/A',
                    'produk' => 'N/A',
                    'nper' => $nperFormatted,
                ];
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
                'alamat' => $row['alamat'] ?: 'N/A',
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




    public function getDataTempbillpers(Request $request)
    {
        if ($request->ajax()) {
            $data_tempbillpers = TempBillper::all();
            return datatables()->of($data_tempbillpers)
                ->addIndexColumn()
                ->addColumn('opsi-tabel-datatempbillper', function ($tempbillper) {
                    return view('components.opsi-tabel-tempbillper', compact('tempbillper'));
                })
                ->toJson();
        }
    }

    public function savetempbillpers()
    {
        // Ambil data dari temp_billpers
        $tempBillpers = TempBillper::all();

        // Insert data ke billpers
        foreach ($tempBillpers as $row) {
            DB::table('billpers')->insert([
                'nama' => $row->nama ?: 'N/A',
                'no_inet' => $row->no_inet ?: 'N/A',
                'saldo' => $row->saldo ?: '0',
                'no_tlf' => $row->no_tlf ?: 'N/A',
                'email' => $row->email ?: 'N/A',
                'alamat' => $row->alamat ?: 'N/A',
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

        // Kosongkan table temp_billpers
        TempBillper::truncate();


        // Redirect ke halaman tools.index atau halaman lainnya
        Alert::success('Data Berhasil Tersimpan');
        return redirect()->route('toolsbillper.index')->with('success', 'Data Berhasil Tersimpan');
    }

    public function deleteAllTempbillpers()
    {
        // Kosongkan table temp_billpers
        TempBillper::truncate();

        Alert::success('Data Berhasil Terhapus');
        return redirect()->route('toolsbillper.index');
    }

    public function destroyTempbillpers($id)
    {
        $data = TempBillper::findOrFail($id);
        $data->delete();
        Alert::success('Data Berhasil Terhapus');
        return redirect()->route('toolsbillper.index');
    }

    // Data Billper
    public function indexbillper()
    {
        confirmDelete();
        $title = 'Data Billper';
        $billpers = Billper::all();

 // Mengambil last update dari created_at id yang terakhir
    $lastUpdate = Billper::latest()->first();
    $lastUpdate = $lastUpdate ? $lastUpdate->created_at->translatedFormat('d F Y H:i') : 'Tidak Ada';

        return view('super-admin.data-billper', compact('title', 'billpers', 'lastUpdate'));
    }


    public function getDatabillpers(Request $request)
    {
        if ($request->ajax()) {
            $query = Billper::query();

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

            // Filter berdasarkan jenis_produk
            if ($request->has('jenis_produk')) {
                $jenisProduk = $request->input('jenis_produk');
                if ($jenisProduk !== 'Semua') {
                    $query->where('produk', '=', $jenisProduk);
                }
            }

            $data_billpers = $query->get();
            return datatables()->of($data_billpers)
                ->addIndexColumn()
                ->addColumn('opsi-tabel-databillper', function ($billper) {
                    return view('components.opsi-tabel-databillper', compact('billper'));
                })
                ->toJson();
        }
    }



    public function viewgeneratePDFbillper(Request $request, $id)
    {
        $billper = Billper::findOrFail($id);
        $total_tagihan = 'RP. ' . number_format($billper->saldo, 2, ',', '.');

        $nper = \Carbon\Carbon::parse($billper->nper);

        // Mendapatkan path gambar dan mengubahnya menjadi format base64
        $imagePath = public_path('storage/file_assets/logo-telkom.png');
        $imageData = base64_encode(file_get_contents($imagePath));
        $imageSrc = 'data:image/png;base64,' . $imageData;

        $data = [
            'billper' => $billper,
            'total_tagihan' => $total_tagihan,
            'date' => Carbon::now()->translatedFormat('d F Y'),
            'nomor_surat' => $request->nomor_surat,
            'nper' => $nper->translatedFormat('F Y'),
            'image_src' => $imageSrc,  // Menyertakan gambar sebagai data base64
        ];

        return view('components.generate-pdf-billper', $data);
    }



    public function generatePDFbillper(Request $request, $id)
    {
        $billper = Billper::findOrFail($id);
        $total_tagihan = 'RP. ' . number_format($billper->saldo, 2, ',', '.');

        $nper = \Carbon\Carbon::parse($billper->nper);

        // Mendapatkan path gambar dan mengubahnya menjadi format base64
        $imagePath = public_path('storage/file_assets/logo-telkom.png');
        $imageData = base64_encode(file_get_contents($imagePath));
        $imageSrc = 'data:image/png;base64,' . $imageData;

        $data = [
            'billper' => $billper,
            'total_tagihan' => $total_tagihan,
            'date' => Carbon::now()->translatedFormat('d F Y'),
            'nomor_surat' => $request->nomor_surat,
            'nper' => $nper->translatedFormat('F Y'),
            'image_src' => $imageSrc,  // Menyertakan gambar sebagai data base64
        ];

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('components.generate-pdf-billper', $data);
        // Buat nama file menggunakan no_inet dan nama
        $fileName = 'Invoice-' . $billper->no_inet . '-' . $billper->nama . '-' . $billper->nper . '.pdf';

        return $pdf->download($fileName);
    }

    public function editbillpers($id)
    {
        $title = 'Edit Data Billper';
        $billper = Billper::findOrFail($id);
        $user = $billper->user ? $billper->user : 'Tidak ada';
        $sales_report = SalesReport::where('billper_id', $id)->orderBy('created_at', 'desc')->first() ?: new SalesReport(); // Initialize as an empty object if null
        $voc_kendala = VocKendala::all();
        return view('super-admin.edit-billper', compact('title', 'billper', 'user', 'sales_report', 'voc_kendala'));
    }


    public function updatebillpers(Request $request, $id)
    {
        $billper = Billper::findOrFail($id);

        // Store the original values
        $original_no_tlf = $billper->no_tlf;
        $original_email = $billper->email;
        $original_alamat = $billper->alamat;

        // Update data with new values
        $billper->nama = $request->input('nama');
        $billper->no_inet = $request->input('no_inet');
        $billper->saldo = $request->input('saldo');
        $billper->no_tlf = $request->input('no_tlf');
        $billper->email = $request->input('email');
        $billper->alamat = $request->input('alamat');
        $billper->sto = $request->input('sto');
        $billper->produk = $request->input('produk');
        $billper->umur_customer = $request->input('umur_customer');
        $billper->status_pembayaran = $request->input('status_pembayaran');
        $billper->nper = $request->input('nper');
        $billper->save();

        // Check if either no_tlf or email has changed
        if ($original_no_tlf !== $request->input('no_tlf') || $original_email !== $request->input('email') || $original_alamat !== $request->input('alamat')) {
            // Save updated data to the riwayat table
            RiwayatBillper::create([
                'nama' => $billper->nama,
                'no_inet' => $billper->no_inet,
                'saldo' => $billper->saldo,
                'no_tlf' => $billper->no_tlf,
                'email' => $billper->email,
                'alamat' => $billper->alamat,
                'sto' => $billper->sto,
                'umur_customer' => $billper->umur_customer,
                'produk' => $billper->produk,
                'status_pembayaran' => $billper->status_pembayaran,
                'nper' => $billper->nper,
            ]);
        }

        Alert::success('Data Berhasil Diperbarui');
        return redirect()->route('billper.index');
    }


    public function viewPDFreportbillpersuperadmin($id)
    {
        $billper = Billper::with('user')->findOrFail($id);
        $sales_report = SalesReport::where('billper_id', $id)->first() ?: new SalesReport();
        $voc_kendala = VocKendala::all();

        return view('components.pdf-report-billper', compact('billper', 'sales_report', 'voc_kendala'));
    }

    public function downloadPDFreportbillpersuperadmin($id)
    {
        $billper = Billper::with('user')->findOrFail($id);
        $sales_report = SalesReport::where('billper_id', $id)->first() ?: new SalesReport();
        $voc_kendala = VocKendala::all();

        // Generate the file name
        $fileName = 'Report - ' . $billper->nama . '-' . $billper->no_inet . '/' . ($billper->user ? $billper->user->name : 'Sales Tidak Ada') . '-' . ($billper->user ? $billper->user->nik : 'Nik Sales Tidak Ada') . '.pdf';

        // Create an instance of PDF
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('components.pdf-report-billper', compact('billper', 'sales_report', 'voc_kendala'));

        return $pdf->download($fileName);
    }

    public function checkFilePembayaranbillper(Request $request)
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

    public function cekPembayaranbillper(Request $request)
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
        $records = Billper::where('nper', $nper)->get();

        // Save riwayat entry
        $riwayat = new Riwayat();
        $riwayat->deskripsi_riwayat = $request->file('file')->getClientOriginalName();
        $riwayat->tanggal_riwayat = $nper;
        $riwayat->riwayat_id = 'riwayat_billper';
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
        return redirect()->route('billper.index');
    }




    public function exportbillpersuperadmin()
    {
        $billperData = Billper::select('nama', 'no_inet', 'saldo', 'no_tlf', 'email', 'sto', 'umur_customer', 'produk', 'status_pembayaran', 'nper')->get();

        return Excel::download(new AllExport($billperData), 'Data-Billper.xlsx');
    }

    public function downloadFilteredExcelbillpersuperadmin(Request $request)
    {
        $bulanTahun = $request->input('nper');
        $statusPembayaran = $request->input('status_pembayaran');
        $jenisProduk = $request->input('jenis_produk');


        // Format input nper ke format yang sesuai dengan kebutuhan database
        $formattedBulanTahun = Carbon::createFromFormat('Y-m', $bulanTahun)->format('Y-m-d');

        // Query untuk mengambil data berdasarkan rentang nper
        $query = Billper::where('nper', 'like', substr($formattedBulanTahun, 0, 7) . '%');

        // Filter berdasarkan status_pembayaran jika tidak "Semua"
        if ($statusPembayaran && $statusPembayaran !== 'Semua') {
            $query->where('status_pembayaran', $statusPembayaran);
        } else {
        }

        // Filter berdasarkan jenis_produk jika tidak "Semua"
        if ($jenisProduk && $jenisProduk !== 'Semua') {
            $query->where('produk', $jenisProduk);
        }

        // Ambil data yang sudah difilter
        $filteredData = $query->select('nama', 'no_inet', 'saldo', 'no_tlf', 'email', 'sto', 'umur_customer', 'produk', 'status_pembayaran', 'nper')->get();

        // Export data menggunakan AllExport dengan data yang sudah difilter
        return Excel::download(new AllExport($filteredData), 'Data-Billper-' . $bulanTahun . '-' . $statusPembayaran . '-' . $jenisProduk . '.xlsx');
    }



    public function destroybillpers($id)
    {
        $billper = Billper::findOrFail($id);
        $billper->delete();
        Alert::success('Data Berhasil Terhapus');
        return redirect()->route('billper.index');
    }


    public function indexbillperriwayat()
    {
        $title = 'Data Riwayat Billper';
        return view('super-admin.riwayat-data-billper', compact('title'));
    }

    public function getDatabillpersriwayat(Request $request)
    {
        if ($request->ajax()) {
            $data_billpers = RiwayatBillper::query(); // atau gunakan paginate jika perlu
            return datatables()->of($data_billpers)->toJson();
        }
    }



    // Report Data Billper
    public function indexreportbillper(Request $request)
    {
        $title = 'Report Data Billper';

        // History
        $riwayats = Riwayat::where('created_at', '>=', Carbon::now()->subWeek())
            ->where('riwayat_id', 'riwayat_billper')
            ->orderBy('id', 'desc')
            ->get();

        // Fetch filter values
        $filter_type = $request->input('filter_type', 'sto');
        $nper = $request->input('nper');
        $show_all = $request->input('show_all');

        // Determine the column to group by based on the filter type
        $group_column = $filter_type === 'umur_customer' ? 'umur_customer' : 'sto';

        // Query with optional filter
        $query = Billper::select(
            $group_column,
            DB::raw('COUNT(*) as total_ssl'),
            DB::raw('SUM(CAST(REPLACE(REPLACE(REPLACE(saldo, "Rp", ""), ".", ""), ",", "") AS UNSIGNED)) as total_saldo'),
            DB::raw('SUM(CASE WHEN status_pembayaran = "Paid" THEN CAST(REPLACE(REPLACE(REPLACE(saldo, "Rp", ""), ".", ""), ",", "") AS UNSIGNED) ELSE 0 END) as total_paid'),
            DB::raw('SUM(CASE WHEN status_pembayaran = "Unpaid" THEN CAST(REPLACE(REPLACE(REPLACE(saldo, "Rp", ""), ".", ""), ",", "") AS UNSIGNED) ELSE 0 END) as total_unpaid'),
            DB::raw('SUM(CASE WHEN status_pembayaran = "Pending" THEN CAST(REPLACE(REPLACE(REPLACE(saldo, "Rp", ""), ".", ""), ",", "") AS UNSIGNED) ELSE 0 END) as total_pending')
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
        $total_pending = $reports->sum('total_pending');

 // Mengambil last update dari created_at id yang terakhir
    $lastUpdate = Billper::latest()->first();
    $lastUpdate = $lastUpdate ? $lastUpdate->created_at->translatedFormat('d F Y H:i') : 'Tidak Ada';

        return view('super-admin.report-databillper', compact('title', 'reports', 'total_ssl', 'total_saldo', 'total_paid', 'total_unpaid', 'total_pending', 'nper', 'filter_type', 'show_all', 'riwayats', 'lastUpdate'));
    }

    public function indexgrafikbillper(Request $request)
    {
        $title = 'Grafik Billper';
        $selectedYear = $request->input('year', date('Y')); // Default to current year if no year is selected
        $jenisGrafik = $request->input('jenis_grafik', 'Billing'); // Default to Billing if no type is selected

        if ($jenisGrafik === 'SSL') {
            // Query for SSL
            $data = DB::table('billpers')
                ->select(
                    DB::raw('COUNT(*) as total_ssl'),
                    DB::raw('SUM(CASE WHEN status_pembayaran = "Paid" THEN 1 ELSE 0 END) as total_paid'),
                    DB::raw('SUM(CASE WHEN status_pembayaran = "Unpaid" THEN 1 ELSE 0 END) as total_unpaid'),
                    DB::raw('SUM(CASE WHEN status_pembayaran = "Pending" THEN 1 ELSE 0 END) as total_pending'),
                    DB::raw('SUBSTRING(nper, 6, 2) as month'),
                    DB::raw('SUBSTRING(nper, 1, 4) as year')
                )
                ->whereRaw("SUBSTRING(nper, 1, 4) = ?", [$selectedYear])
                ->groupBy('year', 'month')
                ->get();
            $months = ['01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'];

            $chartData = [
                'categories' => [],
                'total_ssl' => [],
                'total_paid' => [],
                'total_unpaid' => [],
                'total_pending' => []
            ];

            foreach ($months as $key => $month) {
                $chartData['categories'][] = $month;
                $monthData = $data->firstWhere('month', $key);

                if ($monthData) {
                    $chartData['total_ssl'][] = $monthData->total_ssl;
                    $chartData['total_paid'][] = $monthData->total_paid;
                    $chartData['total_unpaid'][] = $monthData->total_unpaid;
                    $chartData['total_pending'][] = $monthData->total_pending;
                    $chartData['billing'][] = $monthData->total_ssl ? number_format(($monthData->total_paid / $monthData->total_ssl) * 100, 2) : 0;
                } else {
                    $chartData['total_ssl'][] = 0;
                    $chartData['total_paid'][] = 0;
                    $chartData['total_unpaid'][] = 0;
                    $chartData['total_pending'][] = 0;
                    $chartData['billing'][] = 0;
                }
            }
        } else {
            // Query for Billing
            $data = DB::table('billpers')
                ->select(
                    DB::raw("SUM(CASE WHEN status_pembayaran = 'Paid' THEN saldo ELSE 0 END) as paid"),
                    DB::raw("SUM(CASE WHEN status_pembayaran = 'Unpaid' THEN saldo ELSE 0 END) as unpaid"),
                    DB::raw("SUM(CASE WHEN status_pembayaran = 'Pending' THEN saldo ELSE 0 END) as pending"),
                    DB::raw("SUM(saldo) as total"),
                    DB::raw("SUBSTRING(nper, 6, 2) as month"),
                    DB::raw("SUBSTRING(nper, 1, 4) as year")
                )
                ->whereRaw("SUBSTRING(nper, 1, 4) = ?", [$selectedYear])
                ->groupBy('year', 'month')
                ->get();

            $months = ['01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'];

            $chartData = [
                'categories' => [],
                'paid' => [],
                'unpaid' => [],
                'pending' => [],
                'total' => [],
                'billing' => []
            ];

            foreach ($months as $key => $month) {
                $chartData['categories'][] = $month;
                $monthData = $data->firstWhere('month', $key);

                if ($monthData) {
                    $chartData['paid'][] = $monthData->paid;
                    $chartData['unpaid'][] = $monthData->unpaid;
                    $chartData['pending'][] = $monthData->pending;
                    $chartData['total'][] = $monthData->total;
                    $chartData['billing'][] = $monthData->total ? number_format(($monthData->paid / $monthData->total) * 100, 2) : 0;
                } else {
                    $chartData['paid'][] = 0;
                    $chartData['unpaid'][] = 0;
                    $chartData['pending'][] = 0;
                    $chartData['total'][] = 0;
                    $chartData['billing'][] = 0;
                }
            }
        }

 // Mengambil last update dari created_at id yang terakhir
    $lastUpdate = Billper::latest()->first();
    $lastUpdate = $lastUpdate ? $lastUpdate->created_at->translatedFormat('d F Y H:i') : 'Tidak Ada';

        return view('super-admin.grafik-databillper', compact('title', 'chartData', 'selectedYear', 'jenisGrafik', 'lastUpdate'));
    }



    // report sales BIllper
    public function indexreportsalesbillper(Request $request)
    {
        $title = 'Report Sales Billper';
        confirmDelete();

        // Get filter values from request
        $filterMonth = $request->input('month', now()->format('m'));
        $filterYear = $request->input('year', now()->format('Y'));
        $filterSales = $request->input('filter_sales', '');

        // Calculate the current date in 'Y-m' format
        $currentMonth = Carbon::now()->format('Y-m');

        // Retrieve all voc_kendalas and their related report counts for the specified month, year, and sales
        $voc_kendalas = VocKendala::withCount(['salesReports' => function ($query) use ($filterMonth, $filterYear, $filterSales) {
            $query->whereYear('created_at', $filterYear)
                ->whereMonth('created_at', $filterMonth)
                ->whereNotNull('billper_id'); // Ensure only records with billper_id are included

            // Apply sales filter if provided
            if ($filterSales) {
                $query->whereHas('user', function ($q) use ($filterSales) {
                    $q->where('name', $filterSales);
                });
            }
        }])->get();

        // Retrieve all sales with total assignments and total visits
        $sales = User::where('level', 'Sales')
            ->withCount([
                'billpers as total_assignment' => function ($query) use ($filterMonth, $filterYear) {
                    $query->whereYear('created_at', $filterYear)
                        ->whereMonth('created_at', $filterMonth);
                },
                'salesReports as total_visit' => function ($query) use ($filterMonth, $filterYear) {
                    $query->whereYear('created_at', $filterYear)
                        ->whereMonth('created_at', $filterMonth)
                        ->whereNotNull('billper_id');
                }
            ])
            ->get();

        // Calculate wo_sudah_visit and wo_belum_visit manually
        foreach ($sales as $sale) {
            $wo_sudah_visit = DB::table('sales_reports')
                ->whereYear('created_at', $filterYear)
                ->whereMonth('created_at', $filterMonth)
                ->where('users_id', $sale->id)
                ->whereNotNull('billper_id')
                ->distinct('billper_id')
                ->count('billper_id');

            $sale->wo_sudah_visit = $wo_sudah_visit;
            $sale->wo_belum_visit = $sale->total_assignment - $wo_sudah_visit;
        }

        return view('super-admin.report-salesbillper', compact('title', 'voc_kendalas', 'filterMonth', 'filterYear', 'sales', 'filterSales'));
    }

    public function getDatareportbillpersuperadmin(Request $request)
    {
        if ($request->ajax()) {
            $filterMonth = $request->input('month', now()->format('m'));
            $filterYear = $request->input('year', now()->format('Y'));
            $filterSales = $request->input('filter_sales', '');


            $data_report_billper = SalesReport::with('billpers', 'user', 'vockendals')
                ->whereNotNull('billper_id') // Ensure only records with billper_id are included
                ->whereYear('created_at', $filterYear)
                ->whereMonth('created_at', $filterMonth);

            // Apply sales filter if provided
            if ($filterSales) {
                $data_report_billper->whereHas('user', function ($query) use ($filterSales) {
                    $query->where('name', $filterSales);
                });
            }

            $data_report_billper = $data_report_billper->get();

            return datatables()->of($data_report_billper)
                ->addIndexColumn()
                ->addColumn('evidence', function ($row) {
                    return view('components.evidences-buttons', compact('row'));
                })
                ->toJson();
        }
    }

    public function downloadAllExcelreportbillpersuperadmin()
    {
        $reports = SalesReport::with('billpers', 'user', 'vockendals')
            ->whereNotNull('billper_id') // Ensure only records with billper_id are included
            ->get();

        return Excel::download(new SalesReportBillperExisting($reports), 'Report_Billper_Semua.xlsx');
    }

    public function downloadFilteredExcelreportbillpersuperadmin(Request $request)
    {
        $reports = SalesReport::with('billpers', 'user', 'vockendals')
            ->whereNotNull('billper_id') // Ensure only records with billper_id are included
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




























    // TOOL Billper
    public function indextoolexisting()
    {
        confirmDelete();
        $title = 'Tools Existing';
        $temp_existings = TempExisting::all();
        return view('super-admin.tools-existing', compact('title', 'temp_existings'));
    }

    public function checkFile1existing(Request $request)
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


    public function vlookupexisting(Request $request)
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
                    'alamat' => $dataMaster->alamat_pelanggan,
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
                    'alamat' => 'N/A',
                    'sto' => 'N/A',
                    'umur_customer' => 'N/A',
                    'produk' => 'N/A',
                    'nper' => $nperFormatted,
                ];
            }
        }

        // Insert the result into the database
        foreach ($result as $row) {
            DB::table('temp_existings')->insert([
                'nama' => $row['nama'] ?: 'N/A',
                'no_inet' => $row['no_inet'] ?: 'N/A',
                'saldo' => $row['saldo'] ?: 'N/A',
                'no_tlf' => $row['no_tlf'] ?: 'N/A',
                'email' => $row['email'] ?: 'N/A',
                'alamat' => $row['alamat'] ?: 'N/A',
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




    public function getDataTempexistings(Request $request)
    {
        if ($request->ajax()) {
            $data_tempexistings = TempExisting::all();
            return datatables()->of($data_tempexistings)
                ->addIndexColumn()
                ->addColumn('opsi-tabel-datatempexisting', function ($tempexisting) {
                    return view('components.opsi-tabel-tempexisting', compact('tempexisting'));
                })
                ->toJson();
        }
    }


    public function savetempexistings()
    {
        // Ambil data dari temp_existings
        $tempExistings = TempExisting::all();

        // Insert data ke existings
        foreach ($tempExistings as $row) {
            DB::table('existings')->insert([
                'nama' => $row->nama ?: 'N/A',
                'no_inet' => $row->no_inet ?: 'N/A',
                'saldo' => $row->saldo ?: '0',
                'no_tlf' => $row->no_tlf ?: 'N/A',
                'email' => $row->email ?: 'N/A',
                'alamat' => $row->alamat ?: 'N/A',
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

        // Kosongkan table temp_existings
        TempExisting::truncate();


        // Redirect ke halaman tools.index atau halaman lainnya
        Alert::success('Data Berhasil Tersimpan');
        return redirect()->route('toolsexisting.index')->with('success', 'Data Berhasil Tersimpan');
    }

    public function deleteAllTempexistings()
    {
        // Kosongkan table temp_existings
        TempExisting::truncate();

        Alert::success('Data Berhasil Terhapus');
        return redirect()->route('toolsexisting.index');
    }

    public function destroyTempexistings($id)
    {
        $data = TempExisting::findOrFail($id);
        $data->delete();
        Alert::success('Data Berhasil Terhapus');
        return redirect()->route('toolsexisting.index');
    }

    // Data Existing
    public function indexexisting()
    {
        confirmDelete();
        $title = 'Data Existing';
        $existings = Existing::all();

 // Mengambil last update dari created_at id yang terakhir
    $lastUpdate = Existing::latest()->first();
    $lastUpdate = $lastUpdate ? $lastUpdate->created_at->translatedFormat('d F Y H:i') : 'Tidak Ada';
        return view('super-admin.data-existing', compact('title', 'existings', 'lastUpdate'));
    }

    public function getDataexistings(Request $request)
    {
        if ($request->ajax()) {
            $query = Existing::query();

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

            // Filter berdasarkan jenis_produk
            if ($request->has('jenis_produk')) {
                $jenisProduk = $request->input('jenis_produk');
                if ($jenisProduk !== 'Semua') {
                    $query->where('produk', '=', $jenisProduk);
                }
            }

            $data_existings = $query->get();
            return datatables()->of($data_existings)
                ->addIndexColumn()
                ->addColumn('opsi-tabel-dataexisting', function ($existing) {
                    return view('components.opsi-tabel-dataexisting', compact('existing'));
                })
                ->toJson();
        }
    }



    public function viewgeneratePDFexisting(Request $request, $id)
    {
        $existing = Existing::findOrFail($id);
        $total_tagihan = 'RP. ' . number_format($existing->saldo, 2, ',', '.');

        $nper = \Carbon\Carbon::parse($existing->nper);

        // Mendapatkan path gambar dan mengubahnya menjadi format base64
        $imagePath = public_path('storage/file_assets/logo-telkom.png');
        $imageData = base64_encode(file_get_contents($imagePath));
        $imageSrc = 'data:image/png;base64,' . $imageData;

        $data = [
            'existing' => $existing,
            'total_tagihan' => $total_tagihan,
            'date' => Carbon::now()->translatedFormat('d F Y'),
            'nomor_surat' => $request->nomor_surat,
            'nper' => $nper->translatedFormat('F Y'),
            'image_src' => $imageSrc,  // Menyertakan gambar sebagai data base64
        ];

        return view('components.generate-pdf-existing', $data);
    }



    public function generatePDFexisting(Request $request, $id)
    {
        $existing = Existing::findOrFail($id);
        $total_tagihan = 'RP. ' . number_format($existing->saldo, 2, ',', '.');

        $nper = \Carbon\Carbon::parse($existing->nper);

        // Mendapatkan path gambar dan mengubahnya menjadi format base64
        $imagePath = public_path('storage/file_assets/logo-telkom.png');
        $imageData = base64_encode(file_get_contents($imagePath));
        $imageSrc = 'data:image/png;base64,' . $imageData;

        $data = [
            'existing' => $existing,
            'total_tagihan' => $total_tagihan,
            'date' => Carbon::now()->translatedFormat('d F Y'),
            'nomor_surat' => $request->nomor_surat,
            'nper' => $nper->translatedFormat('F Y'),
            'image_src' => $imageSrc,  // Menyertakan gambar sebagai data base64
        ];

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('components.generate-pdf-existing', $data);
        // Buat nama file menggunakan no_inet dan nama
        $fileName = 'Invoice-' . $existing->no_inet . '-' . $existing->nama . '-' . $existing->nper . '.pdf';

        return $pdf->download($fileName);
    }

    public function editexistings($id)
    {
        $title = 'Edit Data Existing';
        $existing = Existing::findOrFail($id);
        $user = $existing->user ? $existing->user : 'Tidak ada';
        $sales_report = SalesReport::where('existing_id', $id)->orderBy('created_at', 'desc')->first() ?: new SalesReport(); // Initialize as an empty object if null
        $voc_kendala = VocKendala::all();
        return view('super-admin.edit-existing', compact('title', 'existing', 'user', 'sales_report', 'voc_kendala'));
    }


    public function updateexistings(Request $request, $id)
    {
        $existing = Existing::findOrFail($id);

        // Store the original values
        $original_no_tlf = $existing->no_tlf;
        $original_email = $existing->email;
        $original_alamat = $existing->alamat;

        // Update data with new values
        $existing->nama = $request->input('nama');
        $existing->no_inet = $request->input('no_inet');
        $existing->saldo = $request->input('saldo');
        $existing->no_tlf = $request->input('no_tlf');
        $existing->email = $request->input('email');
        $existing->alamat = $request->input('alamat');
        $existing->sto = $request->input('sto');
        $existing->produk = $request->input('produk');
        $existing->umur_customer = $request->input('umur_customer');
        $existing->status_pembayaran = $request->input('status_pembayaran');
        $existing->nper = $request->input('nper');
        $existing->save();

        // Check if either no_tlf or email has changed
        if ($original_no_tlf !== $request->input('no_tlf') || $original_email !== $request->input('email') || $original_alamat !== $request->input('alamat')) {
            // Save updated data to the riwayat table
            RiwayatExisting::create([
                'nama' => $existing->nama,
                'no_inet' => $existing->no_inet,
                'saldo' => $existing->saldo,
                'no_tlf' => $existing->no_tlf,
                'email' => $existing->email,
                'alamat' => $existing->alamat,
                'sto' => $existing->sto,
                'umur_customer' => $existing->umur_customer,
                'produk' => $existing->produk,
                'status_pembayaran' => $existing->status_pembayaran,
                'nper' => $existing->nper,
            ]);
        }

        Alert::success('Data Berhasil Diperbarui');
        return redirect()->route('existing.index');
    }

    public function viewPDFreportexistingsuperadmin($id)
    {
        $existing = Existing::with('user')->findOrFail($id);
        $sales_report = SalesReport::where('existing_id', $id)->first() ?: new SalesReport();
        $voc_kendala = VocKendala::all();

        return view('components.pdf-report-existing', compact('existing', 'sales_report', 'voc_kendala'));
    }

    public function downloadPDFreportexistingsuperadmin($id)
    {
        $existing = Existing::with('user')->findOrFail($id);
        $sales_report = SalesReport::where('existing_id', $id)->first() ?: new SalesReport();
        $voc_kendala = VocKendala::all();

        // Generate the file name
        $fileName = 'Report - ' . $existing->nama . '-' . $existing->no_inet . '/' . ($existing->user ? $existing->user->name : 'Sales Tidak Ada') . '-' . ($existing->user ? $existing->user->nik : 'Nik Sales Tidak Ada') . '.pdf';

        // Create an instance of PDF
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('components.pdf-report-existing', compact('existing', 'sales_report', 'voc_kendala'));

        return $pdf->download($fileName);
    }

    public function checkFilePembayaranexisting(Request $request)
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

    public function cekPembayaranexisting(Request $request)
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
        $records = Existing::where('nper', $nper)->get();

        // Save riwayat entry
        $riwayat = new Riwayat();
        $riwayat->deskripsi_riwayat = $request->file('file')->getClientOriginalName();
        $riwayat->tanggal_riwayat = $nper;
        $riwayat->riwayat_id = 'riwayat_existing';
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
        return redirect()->route('existing.index');
    }




    public function exportexistingsuperadmin()
    {
        $existingData = Existing::select('nama', 'no_inet', 'saldo', 'no_tlf', 'email', 'sto', 'umur_customer', 'produk', 'status_pembayaran', 'nper')->get();

        return Excel::download(new AllExport($existingData), 'Data-Existing.xlsx');
    }

    public function downloadFilteredExcelexistingsuperadmin(Request $request)
    {
        $bulanTahun = $request->input('nper');
        $statusPembayaran = $request->input('status_pembayaran');
        $jenisProduk = $request->input('jenis_produk');


        // Format input nper ke format yang sesuai dengan kebutuhan database
        $formattedBulanTahun = Carbon::createFromFormat('Y-m', $bulanTahun)->format('Y-m-d');

        // Query untuk mengambil data berdasarkan rentang nper
        $query = Existing::where('nper', 'like', substr($formattedBulanTahun, 0, 7) . '%');

        // Filter berdasarkan status_pembayaran jika tidak "Semua"
        if ($statusPembayaran && $statusPembayaran !== 'Semua') {
            $query->where('status_pembayaran', $statusPembayaran);
        } else {
        }

        // Filter berdasarkan jenis_produk jika tidak "Semua"
        if ($jenisProduk && $jenisProduk !== 'Semua') {
            $query->where('produk', $jenisProduk);
        }

        // Ambil data yang sudah difilter
        $filteredData = $query->select('nama', 'no_inet', 'saldo', 'no_tlf', 'email', 'sto', 'umur_customer', 'produk', 'status_pembayaran', 'nper')->get();

        // Export data menggunakan AllExport dengan data yang sudah difilter
        return Excel::download(new AllExport($filteredData), 'Data-Existing-' . $bulanTahun . '-' . $statusPembayaran . '-' . $jenisProduk . '.xlsx');
    }



    public function destroyexistings($id)
    {
        $existing = Existing::findOrFail($id);
        $existing->delete();
        Alert::success('Data Berhasil Terhapus');
        return redirect()->route('existing.index');
    }


    public function indexexistingriwayat()
    {
        $title = 'Data Riwayat Existing';
        return view('super-admin.riwayat-data-existing', compact('title'));
    }

    public function getDataexistingsriwayat(Request $request)
    {
        if ($request->ajax()) {
            $data_existings = RiwayatExisting::query(); // atau gunakan paginate jika perlu
            return datatables()->of($data_existings)->toJson();
        }
    }



    // Report Data Existing
    public function indexreportexisting(Request $request)
    {
        $title = 'Report Data Existing';

        // History
        $riwayats = Riwayat::where('created_at', '>=', Carbon::now()->subWeek())
            ->where('riwayat_id', 'riwayat_existing')
            ->orderBy('id', 'desc')
            ->get();

        // Fetch filter values
        $filter_type = $request->input('filter_type', 'sto');
        $nper = $request->input('nper');
        $show_all = $request->input('show_all');

        // Determine the column to group by based on the filter type
        $group_column = $filter_type === 'umur_customer' ? 'umur_customer' : 'sto';

        // Query with optional filter
        $query = Existing::select(
            $group_column,
            DB::raw('COUNT(*) as total_ssl'),
            DB::raw('SUM(CAST(REPLACE(REPLACE(REPLACE(saldo, "Rp", ""), ".", ""), ",", "") AS UNSIGNED)) as total_saldo'),
            DB::raw('SUM(CASE WHEN status_pembayaran = "Paid" THEN CAST(REPLACE(REPLACE(REPLACE(saldo, "Rp", ""), ".", ""), ",", "") AS UNSIGNED) ELSE 0 END) as total_paid'),
            DB::raw('SUM(CASE WHEN status_pembayaran = "Unpaid" THEN CAST(REPLACE(REPLACE(REPLACE(saldo, "Rp", ""), ".", ""), ",", "") AS UNSIGNED) ELSE 0 END) as total_unpaid'),
            DB::raw('SUM(CASE WHEN status_pembayaran = "Pending" THEN CAST(REPLACE(REPLACE(REPLACE(saldo, "Rp", ""), ".", ""), ",", "") AS UNSIGNED) ELSE 0 END) as total_pending')
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
        $total_pending = $reports->sum('total_pending');

 // Mengambil last update dari created_at id yang terakhir
    $lastUpdate = Existing::latest()->first();
    $lastUpdate = $lastUpdate ? $lastUpdate->created_at->translatedFormat('d F Y H:i') : 'Tidak Ada';

        return view('super-admin.report-dataexisting', compact('title', 'reports', 'total_ssl', 'total_saldo', 'total_paid', 'total_unpaid', 'total_pending', 'nper', 'filter_type', 'show_all', 'riwayats', 'lastUpdate'));
    }

    public function indexgrafikexisting(Request $request)
    {
        $title = 'Grafik Existing';
        $selectedYear = $request->input('year', date('Y')); // Default to current year if no year is selected
        $jenisGrafik = $request->input('jenis_grafik', 'Billing'); // Default to Billing if no type is selected

        if ($jenisGrafik === 'SSL') {
            // Query for SSL
            $data = DB::table('existings')
                ->select(
                    DB::raw('COUNT(*) as total_ssl'),
                    DB::raw('SUM(CASE WHEN status_pembayaran = "Paid" THEN 1 ELSE 0 END) as total_paid'),
                    DB::raw('SUM(CASE WHEN status_pembayaran = "Unpaid" THEN 1 ELSE 0 END) as total_unpaid'),
                    DB::raw('SUM(CASE WHEN status_pembayaran = "Pending" THEN 1 ELSE 0 END) as total_pending'),
                    DB::raw('SUBSTRING(nper, 6, 2) as month'),
                    DB::raw('SUBSTRING(nper, 1, 4) as year')
                )
                ->whereRaw("SUBSTRING(nper, 1, 4) = ?", [$selectedYear])
                ->groupBy('year', 'month')
                ->get();
            $months = ['01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'];

            $chartData = [
                'categories' => [],
                'total_ssl' => [],
                'total_paid' => [],
                'total_unpaid' => [],
                'total_pending' => []
            ];

            foreach ($months as $key => $month) {
                $chartData['categories'][] = $month;
                $monthData = $data->firstWhere('month', $key);

                if ($monthData) {
                    $chartData['total_ssl'][] = $monthData->total_ssl;
                    $chartData['total_paid'][] = $monthData->total_paid;
                    $chartData['total_unpaid'][] = $monthData->total_unpaid;
                    $chartData['total_pending'][] = $monthData->total_pending;
                    $chartData['billing'][] = $monthData->total_ssl ? number_format(($monthData->total_paid / $monthData->total_ssl) * 100, 2) : 0;
                } else {
                    $chartData['total_ssl'][] = 0;
                    $chartData['total_paid'][] = 0;
                    $chartData['total_unpaid'][] = 0;
                    $chartData['total_pending'][] = 0;
                    $chartData['billing'][] = 0;
                }
            }
        } else {
            // Query for Billing
            $data = DB::table('existings')
                ->select(
                    DB::raw("SUM(CASE WHEN status_pembayaran = 'Paid' THEN saldo ELSE 0 END) as paid"),
                    DB::raw("SUM(CASE WHEN status_pembayaran = 'Unpaid' THEN saldo ELSE 0 END) as unpaid"),
                    DB::raw("SUM(CASE WHEN status_pembayaran = 'Pending' THEN saldo ELSE 0 END) as pending"),
                    DB::raw("SUM(saldo) as total"),
                    DB::raw("SUBSTRING(nper, 6, 2) as month"),
                    DB::raw("SUBSTRING(nper, 1, 4) as year")
                )
                ->whereRaw("SUBSTRING(nper, 1, 4) = ?", [$selectedYear])
                ->groupBy('year', 'month')
                ->get();

            $months = ['01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'];

            $chartData = [
                'categories' => [],
                'paid' => [],
                'unpaid' => [],
                'pending' => [],
                'total' => [],
                'billing' => []
            ];

            foreach ($months as $key => $month) {
                $chartData['categories'][] = $month;
                $monthData = $data->firstWhere('month', $key);

                if ($monthData) {
                    $chartData['paid'][] = $monthData->paid;
                    $chartData['unpaid'][] = $monthData->unpaid;
                    $chartData['pending'][] = $monthData->pending;
                    $chartData['total'][] = $monthData->total;
                    $chartData['billing'][] = $monthData->total ? number_format(($monthData->paid / $monthData->total) * 100, 2) : 0;
                } else {
                    $chartData['paid'][] = 0;
                    $chartData['unpaid'][] = 0;
                    $chartData['pending'][] = 0;
                    $chartData['total'][] = 0;
                    $chartData['billing'][] = 0;
                }
            }
        }

 // Mengambil last update dari created_at id yang terakhir
    $lastUpdate = Existing::latest()->first();
    $lastUpdate = $lastUpdate ? $lastUpdate->created_at->translatedFormat('d F Y H:i') : 'Tidak Ada';

        return view('super-admin.grafik-dataexisting', compact('title', 'chartData', 'selectedYear', 'jenisGrafik', 'lastUpdate'));
    }

    // report sales Existing
    public function indexreportsalesexisting(Request $request)
    {
        $title = 'Report Sales Existing';
        confirmDelete();

        // Get filter values from request
        $filterMonth = $request->input('month', now()->format('m'));
        $filterYear = $request->input('year', now()->format('Y'));
        $filterSales = $request->input('filter_sales', '');

        // Calculate the current date in 'Y-m' format
        $currentMonth = Carbon::now()->format('Y-m');

        // Retrieve all voc_kendalas and their related report counts for the specified month, year, and sales
        $voc_kendalas = VocKendala::withCount(['salesReports' => function ($query) use ($filterMonth, $filterYear, $filterSales) {
            $query->whereYear('created_at', $filterYear)
                ->whereMonth('created_at', $filterMonth)
                ->whereNotNull('existing_id'); // Ensure only records with existing_id are included

            // Apply sales filter if provided
            if ($filterSales) {
                $query->whereHas('user', function ($q) use ($filterSales) {
                    $q->where('name', $filterSales);
                });
            }
        }])->get();

        // Retrieve all sales with total assignments and total visits
        $sales = User::where('level', 'Sales')
            ->withCount([
                'existings as total_assignment' => function ($query) use ($filterMonth, $filterYear) {
                    $query->whereYear('created_at', $filterYear)
                        ->whereMonth('created_at', $filterMonth);
                },
                'salesReports as total_visit' => function ($query) use ($filterMonth, $filterYear) {
                    $query->whereYear('created_at', $filterYear)
                        ->whereMonth('created_at', $filterMonth)
                        ->whereNotNull('existing_id');
                }
            ])
            ->get();

        // Calculate wo_sudah_visit and wo_belum_visit manually
        foreach ($sales as $sale) {
            $wo_sudah_visit = DB::table('sales_reports')
                ->whereYear('created_at', $filterYear)
                ->whereMonth('created_at', $filterMonth)
                ->where('users_id', $sale->id)
                ->whereNotNull('existing_id')
                ->distinct('existing_id')
                ->count('existing_id');

            $sale->wo_sudah_visit = $wo_sudah_visit;
            $sale->wo_belum_visit = $sale->total_assignment - $wo_sudah_visit;
        }

        return view('super-admin.report-salesexisting', compact('title', 'voc_kendalas', 'filterMonth', 'filterYear', 'sales', 'filterSales'));
    }

    public function getDatareportexistingsuperadmin(Request $request)
    {
        if ($request->ajax()) {
            $filterMonth = $request->input('month', now()->format('m'));
            $filterYear = $request->input('year', now()->format('Y'));
            $filterSales = $request->input('filter_sales', '');

            $data_report_existing = SalesReport::with('existings', 'user', 'vockendals')
                ->whereNotNull('existing_id') // Ensure only records with existing_id are included
                ->whereYear('created_at', $filterYear)
                ->whereMonth('created_at', $filterMonth);

            // Apply sales filter if provided
            if ($filterSales) {
                $data_report_existing->whereHas('user', function ($query) use ($filterSales) {
                    $query->where('name', $filterSales);
                });
            }


            $data_report_existing = $data_report_existing->get();

            return datatables()->of($data_report_existing)
                ->addIndexColumn()
                ->addColumn('evidence', function ($row) {
                    return view('components.evidences-buttons', compact('row'));
                })
                ->toJson();
        }
    }

    public function downloadAllExcelreportexistingsuperadmin()
    {
        $reports = SalesReport::with('existings', 'user', 'vockendals')
            ->whereNotNull('existing_id') // Ensure only records with existing_id are included
            ->get();

        return Excel::download(new SalesReportBillperExisting($reports), 'Report_Existing_Semua.xlsx');
    }

    public function downloadFilteredExcelreportexistingsuperadmin(Request $request)
    {
        $reports = SalesReport::with('existings', 'user', 'vockendals')
            ->whereNotNull('existing_id') // Ensure only records with existing_id are included
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






























    // TOOL Pranpc
    public function indextoolpranpc()
    {
        confirmDelete();
        $title = 'Tools Pranpc';
        $temp_pranpcs = TempPranpc::all();
        return view('super-admin.tools-pranc', compact('title', 'temp_pranpcs'));
    }

    public function getDataTemppranpcs(Request $request)
    {
        if ($request->ajax()) {
            $data_temppranpcs = TempPranpc::all();
            return datatables()->of($data_temppranpcs)
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

 // Mengambil last update dari created_at id yang terakhir
    $lastUpdate = Pranpc::latest()->first();
    $lastUpdate = $lastUpdate ? $lastUpdate->created_at->translatedFormat('d F Y H:i') : 'Tidak Ada';
        return view('super-admin.data-pranpc', compact('title', 'pranpcs', 'lastUpdate'));
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
            'date' => Carbon::now()->translatedFormat('d F Y'),
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
            'date' => Carbon::now()->translatedFormat('d F Y'),
            'nomor_surat' => $request->nomor_surat,
            'mintgk_bulan' => $mintgkDate->translatedFormat('F Y'),
            'maxtgk_bulan' => $maxtgkDate->translatedFormat('F Y'),
            'image_src' => $src,
        ];

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('components.generate-pdf-pranpc', $data);

        // Format tanggal untuk nama file
        $mintgkFormatted = $mintgkDate->translatedFormat('F-Y');
        $maxtgkFormatted = $maxtgkDate->translatedFormat('F-Y');

        // Buat nama file menggunakan snd, nama, dan tanggal yang diformat
        $fileName = 'Invoice-' . $pranpc->snd . '-' . $pranpc->nama . '-' . $mintgkFormatted . '-' . $maxtgkFormatted . '.pdf';

        return $pdf->download($fileName);
    }




    public function editpranpcs($id)
    {
        $title = 'Edit Data PraNPC';
        $pranpc = Pranpc::findOrFail($id);
        $user = $pranpc->user ? $pranpc->user->name : 'Tidak ada'; // Ambil name atau 'Tidak ada'
        $sales_report = SalesReport::where('pranpc_id', $id)->orderBy('created_at', 'desc')->first() ?: new SalesReport(); // Initialize as an empty object if null
        $voc_kendala = VocKendala::all();
        return view('super-admin.edit-pranpc', compact('title', 'pranpc', 'user', 'sales_report', 'voc_kendala'));
    }

    public function updatepranpcs(Request $request, $id)
    {
        $pranpc = Pranpc::findOrFail($id);

        // Store the original values
        $original_multi_kontak1 = $pranpc->multi_kontak1;
        $original_email = $pranpc->email;
        $original_alamat = $pranpc->alamat;

        // Update data with new values
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

        // Check if either multi_kontak1 or email has changed
        if ($original_multi_kontak1 !== $request->input('multi_kontak1') || $original_email !== $request->input('email') || $original_alamat !== $request->input('alamat')) {
            // Save updated data to the riwayat table
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
        }

        Alert::success('Data Berhasil Diperbarui');
        return redirect()->route('pranpc.index');
    }


    public function viewPDFreportpranpcsuperadmin($id)
    {
        $pranpc = Pranpc::with('user')->findOrFail($id);
        $sales_report = SalesReport::where('pranpc_id', $id)->first() ?: new SalesReport();
        $voc_kendala = VocKendala::all();

        return view('components.pdf-reportpranpc-adminpranpc', compact('pranpc', 'sales_report', 'voc_kendala'));
    }

    public function downloadPDFreportpranpcsuperadmin($id)
    {
        $pranpc = Pranpc::with('user')->findOrFail($id);
        $sales_report = SalesReport::where('pranpc_id', $id)->first() ?: new SalesReport();
        $voc_kendala = VocKendala::all();

        // Generate the file name
        $fileName = 'Report-' . $pranpc->nama . '-' . $pranpc->snd . '/' . ($pranpc->user ? $pranpc->user->name : 'Sales Tidak Ada') . '-' . ($pranpc->user ? $pranpc->user->nik : 'Nik Sales Tidak Ada') . '.pdf';

        // Create an instance of PDF
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('components.pdf-reportpranpc-adminpranpc', compact('pranpc', 'sales_report', 'voc_kendala'));

        return $pdf->download($fileName);
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


    // Report Data Pranpc
    public function indexreportpranpc(Request $request)
    {
        $title = 'Report Data Pranpc';

        // Fetch filter values
        $year = $request->input('year');
        $bulan = $request->input('bulan');
        $show_all = $request->input('show_all', false);

        // Query with optional filter
        $query = Pranpc::select(
            'sto', // Selalu gunakan 'sto' untuk pengelompokan
            DB::raw('COUNT(*) as total_ssl'),
            DB::raw('SUM(CAST(REPLACE(REPLACE(REPLACE(bill_bln, "Rp", ""), ".", ""), ",", "") AS UNSIGNED)) as total_bill_bln'),
            DB::raw('SUM(CAST(REPLACE(REPLACE(REPLACE(bill_bln1, "Rp", ""), ".", ""), ",", "") AS UNSIGNED)) as total_bill_bln1'),
            DB::raw('SUM(CASE WHEN status_pembayaran = "Paid" THEN CAST(REPLACE(REPLACE(REPLACE(bill_bln, "Rp", ""), ".", ""), ",", "") AS UNSIGNED) ELSE 0 END) as total_paid_bill_bln'),
            DB::raw('SUM(CASE WHEN status_pembayaran = "Paid" THEN CAST(REPLACE(REPLACE(REPLACE(bill_bln1, "Rp", ""), ".", ""), ",", "") AS UNSIGNED) ELSE 0 END) as total_paid_bill_bln1'),
            DB::raw('SUM(CASE WHEN status_pembayaran = "Unpaid" THEN CAST(REPLACE(REPLACE(REPLACE(bill_bln, "Rp", ""), ".", ""), ",", "") AS UNSIGNED) ELSE 0 END) as total_unpaid_bill_bln'),
            DB::raw('SUM(CASE WHEN status_pembayaran = "Unpaid" THEN CAST(REPLACE(REPLACE(REPLACE(bill_bln1, "Rp", ""), ".", ""), ",", "") AS UNSIGNED) ELSE 0 END) as total_unpaid_bill_bln1'),
            DB::raw('SUM(CASE WHEN status_pembayaran = "Pending" THEN CAST(REPLACE(REPLACE(REPLACE(bill_bln, "Rp", ""), ".", ""), ",", "") AS UNSIGNED) ELSE 0 END) as total_pending_bill_bln'),
            DB::raw('SUM(CASE WHEN status_pembayaran = "Pending" THEN CAST(REPLACE(REPLACE(REPLACE(bill_bln1, "Rp", ""), ".", ""), ",", "") AS UNSIGNED) ELSE 0 END) as total_pending_bill_bln1')
        );

        // Exclude rows where sto is 'N/A'
        $query->where('sto', '!=', 'N/A');

        // Apply filter by year and month range if show_all is not checked
        if (!$show_all && $year && $bulan) {
            $bulanRange = explode('-', $bulan);
            $startMonth = $bulanRange[0];
            $endMonth = $bulanRange[1];

            $startMintgk = $year . '-' . $startMonth;
            $endMaxtgk = $year . '-' . $endMonth;

            $query->where('mintgk', '>=', $startMintgk)
                ->where('maxtgk', '<=', $endMaxtgk);
        }

        $reports = $query->groupBy('sto')->get();

        $total_ssl = $reports->sum('total_ssl');
        $total_bill_bln = $reports->sum('total_bill_bln');
        $total_bill_bln1 = $reports->sum('total_bill_bln1');
        $total_paid_bill_bln = $reports->sum('total_paid_bill_bln');
        $total_paid_bill_bln1 = $reports->sum('total_paid_bill_bln1');
        $total_unpaid_bill_bln = $reports->sum('total_unpaid_bill_bln');
        $total_unpaid_bill_bln1 = $reports->sum('total_unpaid_bill_bln1');
        $total_pending_bill_bln = $reports->sum('total_pending_bill_bln');
        $total_pending_bill_bln1 = $reports->sum('total_pending_bill_bln1');

 // Mengambil last update dari created_at id yang terakhir
    $lastUpdate = Pranpc::latest()->first();
    $lastUpdate = $lastUpdate ? $lastUpdate->created_at->translatedFormat('d F Y H:i') : 'Tidak Ada';

        return view('super-admin.report-datapranpc', compact(
            'title',
            'reports',
            'total_ssl',
            'total_bill_bln',
            'total_bill_bln1',
            'total_paid_bill_bln',
            'total_paid_bill_bln1',
            'total_unpaid_bill_bln',
            'total_unpaid_bill_bln1',
            'total_pending_bill_bln',
            'total_pending_bill_bln1',
            'year',
            'bulan',
            'show_all',
            'lastUpdate'
        ));
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
        $sales = User::where('level', 'Sales')
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
                    return view('components.evidences-buttons', compact('row'));
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
