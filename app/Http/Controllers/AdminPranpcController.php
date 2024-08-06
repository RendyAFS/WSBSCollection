<?php

namespace App\Http\Controllers;

use App\Exports\AllExport;
use App\Exports\PranpcExport;
use App\Exports\SalesReportBillperExisting;
use App\Exports\SalesReportPranpc;
use App\Models\Billper;
use App\Models\Existing;
use App\Models\Pranpc;
use App\Models\SalesReport;
use App\Models\User;
use App\Models\VocKendala;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;
use PDF;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
        $title = 'Data Plotting Pranpc';
        $pranpcs = Pranpc::all();
        $users = User::where('level', 'Sales')->get();
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
        $sales_report = SalesReport::where('pranpc_id', $id)->orderBy('created_at', 'desc')->first() ?: new SalesReport(); // Initialize as an empty object if null
        $voc_kendala = VocKendala::all();
        return view('admin-pranpc.edit-pranpcadminpranpc', compact('title', 'pranpc', 'user', 'sales_report', 'voc_kendala'));
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

    public function viewgeneratePDFpranpcadminpranpc(Request $request, $id)
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

    public function generatePDFpranpcadminpranpc(Request $request, $id)
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

    public function viewPDFreportpranpc($id)
    {
        $pranpc = Pranpc::with('user')->findOrFail($id);
        $sales_report = SalesReport::where('pranpc_id', $id)->first() ?: new SalesReport();
        $voc_kendala = VocKendala::all();

        return view('components.pdf-reportpranpc-adminpranpc', compact('pranpc', 'sales_report', 'voc_kendala'));
    }

    public function downloadPDFreportpranpc($id)
    {
        $pranpc = Pranpc::with('user')->findOrFail($id);
        $sales_report = SalesReport::where('pranpc_id', $id)->first() ?: new SalesReport();
        $voc_kendala = VocKendala::all();

        // Generate the file name
        $fileName = 'Report-' . $pranpc->nama . '-' . $pranpc->snd . '/' . ($pranpc->user ? $pranpc->user->name : 'Unknown') . '-' . ($pranpc->user ? $pranpc->user->nik : 'Unknown') . '.pdf';

        // Create an instance of PDF
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('components.pdf-reportpranpc-adminpranpc', compact('pranpc', 'sales_report', 'voc_kendala'));

        return $pdf->download($fileName);
    }

    public function savePlotting(Request $request)
    {
        $ids = $request->input('ids');
        $userId = $request->input('user_id');

        // Update data dengan user_id yang dipilih
        Pranpc::whereIn('id', $ids)->update(['users_id' => $userId]);

        return response()->json(['success' => true]);
    }


    public function indexreportpranpcadminpranpc(Request $request)
    {
        confirmDelete();
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

        return view('admin-pranpc.report-pranpc-adminpranpc', compact('title', 'voc_kendalas', 'filterMonth', 'filterYear', 'sales', 'filterSales'));
    }


    public function getDatareportpranpc(Request $request)
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



    public function downloadAllExcelreportpranpc()
    {
        $reports = SalesReport::with('pranpcs', 'user', 'vockendals')
            ->whereNotNull('pranpc_id') // Ensure only records with pranpc_id are included
            ->get();

        return Excel::download(new SalesReportPranpc($reports), 'Report_Pranpc_Semua.xlsx');
    }

    public function downloadFilteredExcelreportpranpc(Request $request)
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






































    // Data Existing Admin Pranpc
    public function indexexistingadminpranpc()
    {
        confirmDelete();
        $title = 'Data Plotting Existing';
        $existings = Existing::all();
        $users = User::where('level', 'Sales')->get();
        return view('admin-pranpc.data-existing-adminpranpc', compact('title', 'existings', 'users'));
    }

    public function getDataexistingsadminpranpc(Request $request)
    {
        if ($request->ajax()) {
            $query = Existing::query()->with('user'); // Menambahkan eager loading untuk relasi 'user'

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
            $data_existings = $query->get();

            return datatables()->of($data_existings)
                ->addIndexColumn()
                ->addColumn('opsi-tabel-dataexistingadminpranpc', function ($existing) {
                    return view('components.opsi-tabel-dataexistingadminpranpc', compact('existing'));
                })
                ->addColumn('nama_user', function ($existing) {
                    return $existing->user ? $existing->user->name : 'Tidak Ada'; // Mengakses nama pengguna atau teks "Tidak Ada" jika relasi user null
                })
                ->rawColumns(['opsi-tabel-dataexistingadminpranpc']) // Menandai kolom sebagai raw HTML
                ->toJson();
        }
    }


    public function exportexisting()
    {
        $existingData = Existing::select('nama', 'no_inet', 'saldo', 'no_tlf', 'email', 'sto', 'umur_customer', 'produk', 'status_pembayaran', 'nper')->get();

        return Excel::download(new AllExport($existingData), 'Data-Existing.xlsx');
    }

    public function downloadFilteredExcelexisting(Request $request)
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


    public function editexistingsadminpranpc($id)
    {
        $title = 'Edit Data Plotting';
        $existing = Existing::with('user')->findOrFail($id);
        $user = $existing->user ? $existing->user : 'Tidak ada';
        $sales_report = SalesReport::where('existing_id', $id)->orderBy('created_at', 'desc')->first() ?: new SalesReport(); // Initialize as an empty object if null
        $voc_kendala = VocKendala::all();
        return view('admin-pranpc.edit-existingadminpranpc', compact('title', 'existing', 'user', 'sales_report', 'voc_kendala'));
    }


    public function viewgeneratePDFexistingadminpranpc(Request $request, $id)
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
            'date' => now()->format('d/m/Y'),
            'nomor_surat' => $request->nomor_surat,
            'nper' => $nper->translatedFormat('F Y'),
            'image_src' => $imageSrc,  // Menyertakan gambar sebagai data base64
        ];

        return view('components.generate-pdf-existing', $data);
    }



    public function generatePDFexistingadminpranpc(Request $request, $id)
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
            'date' => now()->format('d/m/Y'),
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




    public function updateexistingsadminpranpc(Request $request, $id)
    {
        $existing = Existing::findOrFail($id);
        $existing->nama = $request->input('nama');
        $existing->no_inet = $request->input('no_inet');
        $existing->saldo = $request->input('saldo');
        $existing->no_tlf = $request->input('no_tlf');
        $existing->email = $request->input('email');
        $existing->sto = $request->input('sto');
        $existing->produk = $request->input('produk');
        $existing->umur_customer = $request->input('umur_customer');
        $existing->status_pembayaran = $request->input('status_pembayaran');
        $existing->save();


        Alert::success('Data Berhasil Diperbarui');
        return redirect()->route('existing-adminpranpc.index');
    }


    public function viewPDFreportexistingadminpranpc($id)
    {
        $existing = Existing::with('user')->findOrFail($id);
        $sales_report = SalesReport::where('existing_id', $id)->first() ?: new SalesReport();
        $voc_kendala = VocKendala::all();

        return view('components.pdf-report-existing', compact('existing', 'sales_report', 'voc_kendala'));
    }

    public function downloadPDFreportexistingadminpranpc($id)
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


    public function savePlottingexistingadminpranpc(Request $request)
    {
        $ids = $request->input('ids');
        $userId = $request->input('user_id');

        // Update data dengan user_id yang dipilih
        Existing::whereIn('id', $ids)->update(['users_id' => $userId]);

        return response()->json(['success' => true]);
    }


    // Report Sales Existing
    public function indexreportexistingadminpranpc(Request $request)
    {
        confirmDelete();
        $title = 'Report Sales Existng';

        // Get filter values from request
        $filterMonth = $request->input('month', now()->format('m'));
        $filterYear = $request->input('year', now()->format('Y'));
        $filterSales = $request->input('filter_sales', '');

        // Calculate the current date in 'Y-m' format
        $currentMonth = Carbon::now()->format('Y-m');

        // Retrieve Existing voc_kendalas and their related report counts for the specified month, year, and sales
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

        // Retrieve Existing sales with total assignments and total visits
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


        return view('admin-pranpc.report-existing-adminpranpc', compact('title', 'voc_kendalas', 'filterMonth', 'filterYear', 'sales', 'filterSales'));
    }




    public function getDatareportexistingadminpranpc(Request $request)
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




    public function downloadAllExcelreportexistingadminpranpc()
    {
        $reports = SalesReport::with('existings', 'user', 'vockendals')
            ->whereNotNull('existing_id') // Ensure only records with existing_id are included
            ->get();

        return Excel::download(new SalesReportBillperExisting($reports), 'Report_Existing_Semua.xlsx');
    }

    public function downloadFilteredExcelreportexistingadminpranpc(Request $request)
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
}
