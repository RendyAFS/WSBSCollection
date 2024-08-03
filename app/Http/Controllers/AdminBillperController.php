<?php

namespace App\Http\Controllers;

use App\Exports\AllExport;
use App\Exports\SalesReportBillperExisting;
use App\Models\All;
use App\Models\SalesReport;
use App\Models\User;
use App\Models\VocKendala;
use PDF;
use Illuminate\Support\Facades\App;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

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
        $users = User::where('level', 'User')->get();
        return view('admin-billper.data-all-adminbillper', compact('title', 'alls', 'users'));
    }

    public function getDatabillpersadminbillper(Request $request)
    {
        if ($request->ajax()) {
            $query = All::query()->with('user'); // Menambahkan eager loading untuk relasi 'user'

            // Filter berdasarkan nper
            if ($request->has('nper')) {
                $nper = $request->input('nper');
                $query->where('nper', 'LIKE', "%$nper%");
            }

            // Filter berdasarkan status_pembayaran
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


            // Ambil data dan kembalikan sebagai JSON dengan Datatables
            $data_alls = $query->get();

            return datatables()->of($data_alls)
                ->addIndexColumn()
                ->addColumn('opsi-tabel-dataalladminbillper', function ($all) {
                    return view('components.opsi-tabel-dataalladminbillper', compact('all'));
                })
                ->addColumn('nama_user', function ($all) {
                    return $all->user ? $all->user->name : 'Tidak Ada'; // Mengakses nama pengguna atau teks "Tidak Ada" jika relasi user null
                })
                ->rawColumns(['opsi-tabel-dataalladminbillper']) // Menandai kolom sebagai raw HTML
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
        $jenisProduk = $request->input('jenis_produk');

        // Format input nper ke format yang sesuai dengan kebutuhan database
        $formattedBulanTahun = Carbon::createFromFormat('Y-m', $bulanTahun)->format('Y-m-d');

        // Query untuk mengambil data berdasarkan rentang nper
        $query = All::where('nper', 'like', substr($formattedBulanTahun, 0, 7) . '%');

        // Filter berdasarkan status_pembayaran jika tidak "Semua"
        if ($statusPembayaran && $statusPembayaran !== 'Semua') {
            $query->where('status_pembayaran', $statusPembayaran);
        }

        // Filter berdasarkan jenis_produk jika tidak "Semua"
        if ($jenisProduk && $jenisProduk !== 'Semua') {
            $query->where('produk', $jenisProduk);
        }

        // Ambil data yang sudah difilter
        $filteredData = $query->select('nama', 'no_inet', 'saldo', 'no_tlf', 'email', 'sto', 'umur_customer', 'produk', 'status_pembayaran', 'nper')->get();

        // Export data menggunakan AllExport dengan data yang sudah difilter
        return Excel::download(new AllExport($filteredData), 'Data-Semua-' . $bulanTahun . '-' . $statusPembayaran . '-' . $jenisProduk . '.xlsx');
    }


    public function editbillpersadminbillper($id)
    {
        $title = 'Edit Data Plotting';
        $all = All::with('user')->findOrFail($id);
        $user = $all->user ? $all->user : 'Tidak ada';
        $sales_report = SalesReport::where('all_id', $id)->orderBy('created_at', 'desc')->first() ?: new SalesReport(); // Initialize as an empty object if null
        $voc_kendala = VocKendala::all();
        return view('admin-billper.edit-alladminbillper', compact('title', 'all', 'user', 'sales_report', 'voc_kendala'));
    }


    public function viewgeneratePDFbillperexistingadminbillper(Request $request, $id)
    {
        $all = All::findOrFail($id);
        $total_tagihan = 'RP. ' . number_format($all->saldo, 2, ',', '.');

        $nper = \Carbon\Carbon::parse($all->nper);

        // Mendapatkan path gambar dan mengubahnya menjadi format base64
        $imagePath = public_path('storage/file_assets/logo-telkom.png');
        $imageData = base64_encode(file_get_contents($imagePath));
        $imageSrc = 'data:image/png;base64,' . $imageData;

        $data = [
            'all' => $all,
            'total_tagihan' => $total_tagihan,
            'date' => now()->format('d/m/Y'),
            'nomor_surat' => $request->nomor_surat,
            'nper' => $nper->translatedFormat('F Y'),
            'image_src' => $imageSrc,  // Menyertakan gambar sebagai data base64
        ];

        return view('components.generate-pdf-billperexisting', $data);
    }



    public function generatePDFbillperexistingadminbillper(Request $request, $id)
    {
        $all = All::findOrFail($id);
        $total_tagihan = 'RP. ' . number_format($all->saldo, 2, ',', '.');

        $nper = \Carbon\Carbon::parse($all->nper);

        // Mendapatkan path gambar dan mengubahnya menjadi format base64
        $imagePath = public_path('storage/file_assets/logo-telkom.png');
        $imageData = base64_encode(file_get_contents($imagePath));
        $imageSrc = 'data:image/png;base64,' . $imageData;

        $data = [
            'all' => $all,
            'total_tagihan' => $total_tagihan,
            'date' => now()->format('d/m/Y'),
            'nomor_surat' => $request->nomor_surat,
            'nper' => $nper->translatedFormat('F Y'),
            'image_src' => $imageSrc,  // Menyertakan gambar sebagai data base64
        ];

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('components.generate-pdf-billperexisting', $data);
        return $pdf->download('invoice.pdf');
    }



    public function updatebillpersadminbillper(Request $request, $id)
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
        return redirect()->route('all-adminbillper.index');
    }


    public function viewPDFreportbillper($id)
    {
        $all = All::with('user')->findOrFail($id);
        $sales_report = SalesReport::where('all_id', $id)->first() ?: new SalesReport();
        $voc_kendala = VocKendala::all();

        return view('components.pdf-report-billper', compact('all', 'sales_report', 'voc_kendala'));
    }

    public function downloadPDFreportbillper($id)
    {
        $all = All::with('user')->findOrFail($id);
        $sales_report = SalesReport::where('all_id', $id)->first() ?: new SalesReport();
        $voc_kendala = VocKendala::all();

        // Generate the file name
        $fileName = 'Report - ' . $all->nama . '-' . $all->no_inet . '/' . ($all->user ? $all->user->name : 'Unknown') . '-' . ($all->user ? $all->user->nik : 'Unknown') . '.pdf';

        // Create an instance of PDF
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('components.pdf-report-billper', compact('all', 'sales_report', 'voc_kendala'));

        return $pdf->download($fileName);
    }


    public function savePlotting(Request $request)
    {
        $ids = $request->input('ids');
        $userId = $request->input('user_id');

        // Update data dengan user_id yang dipilih
        All::whereIn('id', $ids)->update(['users_id' => $userId]);

        return response()->json(['success' => true]);
    }


    // Report Data All
    public function indexreportalladminbillper(Request $request)
    {
        confirmDelete();
        $title = 'Report Data All';

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
                ->whereNotNull('all_id'); // Ensure only records with all_id are included

            // Apply sales filter if provided
            if ($filterSales) {
                $query->whereHas('user', function ($q) use ($filterSales) {
                    $q->where('name', $filterSales);
                });
            }

        }])->get();

        // Retrieve all sales with total assignments and total visits
        $sales = User::where('level', 'user')
            ->withCount([
                'alls as total_assignment' => function ($query) use ($filterMonth, $filterYear) {
                    $query->whereYear('created_at', $filterYear)
                        ->whereMonth('created_at', $filterMonth);
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


        return view('admin-billper.report-all-adminbillper', compact('title', 'voc_kendalas', 'filterMonth', 'filterYear', 'sales', 'filterSales'));
    }





    public function getDatareportbillper(Request $request)
    {
        if ($request->ajax()) {
            $filterMonth = $request->input('month', now()->format('m'));
            $filterYear = $request->input('year', now()->format('Y'));
            $filterSales = $request->input('filter_sales', '');

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

            $data_report_billper = $data_report_billper->get();

            return datatables()->of($data_report_billper)
                ->addIndexColumn()
                ->addColumn('evidence', function ($row) {
                    return view('components.evidence-buttons-adminbillper', compact('row'));
                })
                ->toJson();
        }
    }




    public function downloadAllExcelreportbillper()
    {
        $reports = SalesReport::with('alls', 'user', 'vockendals')
            ->whereNotNull('all_id') // Ensure only records with all_id are included
            ->get();

        return Excel::download(new SalesReportBillperExisting($reports), 'Report_Billper-Existing_Semua.xlsx');
    }

    public function downloadFilteredExcelreportbillper(Request $request)
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
}
