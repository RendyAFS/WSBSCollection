<?php

namespace App\Http\Controllers;

use App\Exports\AllExport;
use App\Exports\SalesReportBillperExisting;
use App\Models\Billper;
use App\Models\Existing;
use App\Models\RiwayatBillper;
use App\Models\RiwayatExisting;
use App\Models\SalesReport;
use App\Models\User;
use App\Models\VocKendala;
use PDF;
use Illuminate\Support\Facades\App;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class AdminBillperController extends Controller
{
    public function index()
    {
        $title = 'Dashboard';

        // Menghitung jumlah data untuk bulan ini
        $currentMonthYear = date('Y-m');

        // Untuk tipe created_at dan update_at
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Billper
        $billperCurrentMonthCount = Billper::where('nper', $currentMonthYear)
            ->count();
        // existing
        $existingCurrentMonthCount = Existing::where('nper', $currentMonthYear)
            ->count();
        // Billper
        $plottingbillperCurrentMonthCount = Billper::where('nper', $currentMonthYear)
            ->whereNotNull('users_id')
            ->count();
        // existing
        $plottingexistingCurrentMonthCount = Existing::where('nper', $currentMonthYear)
            ->whereNotNull('users_id')
            ->count();

        // Table Pending Billper
        $tabelPendingBillper = Billper::where('nper', $currentMonthYear)
            ->whereNotNull('users_id')
            ->where('status_pembayaran', 'Pending')
            ->get();

        // Table Pending Existing
        $tabelPendingExisting = Existing::where('nper', $currentMonthYear)
            ->whereNotNull('users_id')
            ->where('status_pembayaran', 'Pending')
            ->get();

        $totalVisitbillperexistingSales = 0;

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

            // Add to total visit count
            $totalVisitbillperexistingSales += $wo_sudah_visit;
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

            // Add to total visit count
            $totalVisitbillperexistingSales += $wo_sudah_visit;
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

            // Add to total visit count
            $totalVisitbillperexistingSales += $wo_sudah_visit;
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

            // Add to total visit count
            $totalVisitbillperexistingSales += $wo_sudah_visit;
        }

        return view(
            'admin-billper.index',
            compact(
                'title',
                'billperCurrentMonthCount',
                'existingCurrentMonthCount',
                'plottingbillperCurrentMonthCount',
                'plottingexistingCurrentMonthCount',
                'tabelPendingBillper',
                'tabelPendingExisting',
                'salesbillpertertinggi',
                'salesexistingtertinggi',
                'salesbillperterendah',
                'salesexistingterendah',
                'totalVisitbillperexistingSales',
            )
        );
    }


    // Data Billper Admin Billper
    public function indexbillperadminbillper()
    {
        confirmDelete();
        $title = 'Data Plotting Billper';
        $billpers = Billper::all();
        $users = User::where('level', 'Sales')->get();

        // Mengambil last update dari created_at id yang terakhir
        $lastUpdate = Billper::latest()->first();
        $lastUpdate = $lastUpdate ? $lastUpdate->created_at->translatedFormat('d F Y H:i') : 'Tidak Ada';
        return view('admin-billper.data-billper-adminbillper', compact('title', 'billpers', 'users', 'lastUpdate'));
    }

    public function getDatabillpersadminbillper(Request $request)
    {
        if ($request->ajax()) {
            $query = Billper::query()->with('user'); // Menambahkan eager loading untuk relasi 'user'

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
            $data_billpers = $query->get();

            return datatables()->of($data_billpers)
                ->addIndexColumn()
                ->addColumn('opsi-tabel-databillperadminbillper', function ($billper) {
                    return view('components.opsi-tabel-databillperadminbillper', compact('billper'));
                })
                ->addColumn('nama_user', function ($billper) {
                    return $billper->user ? $billper->user->name : 'Tidak Ada'; // Mengakses nama pengguna atau teks "Tidak Ada" jika relasi user null
                })
                ->rawColumns(['opsi-tabel-databillperadminbillper']) // Menandai kolom sebagai raw HTML
                ->toJson();
        }
    }


    public function exportbillper()
    {
        $billperData = Billper::select('nama', 'no_inet', 'saldo', 'no_tlf', 'email', 'sto', 'umur_customer', 'produk', 'status_pembayaran', 'nper')->get();

        return Excel::download(new AllExport($billperData), 'Data-Billper.xlsx');
    }

    public function downloadFilteredExcelbillper(Request $request)
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


    public function editbillpersadminbillper($id)
    {
        $title = 'Edit Data Plotting';
        $billper = Billper::with('user')->findOrFail($id);
        $user = $billper->user ? $billper->user : 'Tidak ada';
        $sales_report = SalesReport::where('billper_id', $id)->orderBy('created_at', 'desc')->first() ?: new SalesReport(); // Initialize as an empty object if null
        $voc_kendala = VocKendala::all();
        return view('admin-billper.edit-billperadminbillper', compact('title', 'billper', 'user', 'sales_report', 'voc_kendala'));
    }


    public function viewgeneratePDFbillperadminbillper(Request $request, $id)
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



    public function generatePDFbillperadminbillper(Request $request, $id)
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




    public function updatebillpersadminbillper(Request $request, $id)
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
        return redirect()->route('billper-adminbillper.index');
    }


    public function viewPDFreportbillper($id)
    {
        $billper = Billper::with('user')->findOrFail($id);
        $sales_report = SalesReport::where('billper_id', $id)->first() ?: new SalesReport();
        $voc_kendala = VocKendala::all();

        return view('components.pdf-report-billper', compact('billper', 'sales_report', 'voc_kendala'));
    }

    public function downloadPDFreportbillper($id)
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


    public function savePlottingbillper(Request $request)
    {
        $ids = $request->input('ids');
        $userId = $request->input('user_id');

        // Update data dengan user_id yang dipilih
        Billper::whereIn('id', $ids)->update(['users_id' => $userId]);

        return response()->json(['success' => true]);
    }


    // Report Data billper
    public function indexreportbillperadminbillper(Request $request)
    {
        confirmDelete();
        $title = 'Report Sales Billper';

        // Get filter values from request
        $filterMonth = $request->input('month', now()->format('m'));
        $filterYear = $request->input('year', now()->format('Y'));
        $filterSales = $request->input('filter_sales', '');

        // Calculate the current date in 'Y-m' format
        $currentMonth = Carbon::now()->format('Y-m');

        // Retrieve billper voc_kendalas and their related report counts for the specified month, year, and sales
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


        return view('admin-billper.report-billper-adminbillper', compact('title', 'voc_kendalas', 'filterMonth', 'filterYear', 'sales', 'filterSales'));
    }





    public function getDatareportbillper(Request $request)
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




    public function downloadAllExcelreportbillper()
    {
        $reports = SalesReport::with('billpers', 'user', 'vockendals')
            ->whereNotNull('billper_id') // Ensure only records with billper_id are included
            ->get();

        return Excel::download(new SalesReportBillperExisting($reports), 'Report_Billper_Semua.xlsx');
    }

    public function downloadFilteredExcelreportbillper(Request $request)
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




































    // Data Existing Admin Billper
    public function indexexistingadminbillper()
    {
        confirmDelete();
        $title = 'Data Plotting Existing';
        $existings = Existing::all();
        $users = User::where('level', 'Sales')->get();
        // Mengambil last update dari created_at id yang terakhir
        $lastUpdate = Existing::latest()->first();
        $lastUpdate = $lastUpdate ? $lastUpdate->created_at->translatedFormat('d F Y H:i') : 'Tidak Ada';
        return view('admin-billper.data-existing-adminbillper', compact('title', 'existings', 'users', 'lastUpdate'));
    }

    public function getDataexistingsadminbillper(Request $request)
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
                ->addColumn('opsi-tabel-dataexistingadminbillper', function ($existing) {
                    return view('components.opsi-tabel-dataexistingadminbillper', compact('existing'));
                })
                ->addColumn('nama_user', function ($existing) {
                    return $existing->user ? $existing->user->name : 'Tidak Ada'; // Mengakses nama pengguna atau teks "Tidak Ada" jika relasi user null
                })
                ->rawColumns(['opsi-tabel-dataexistingadminbillper']) // Menandai kolom sebagai raw HTML
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


    public function editexistingsadminbillper($id)
    {
        $title = 'Edit Data Plotting';
        $existing = Existing::with('user')->findOrFail($id);
        $user = $existing->user ? $existing->user : 'Tidak ada';
        $sales_report = SalesReport::where('existing_id', $id)->orderBy('created_at', 'desc')->first() ?: new SalesReport(); // Initialize as an empty object if null
        $voc_kendala = VocKendala::all();
        return view('admin-billper.edit-existingadminbillper', compact('title', 'existing', 'user', 'sales_report', 'voc_kendala'));
    }


    public function viewgeneratePDFexistingadminbillper(Request $request, $id)
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



    public function generatePDFexistingadminbillper(Request $request, $id)
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




    public function updateexistingsadminbillper(Request $request, $id)
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
        return redirect()->route('existing-adminbillper.index');
    }


    public function viewPDFreportexisting($id)
    {
        $existing = Existing::with('user')->findOrFail($id);
        $sales_report = SalesReport::where('existing_id', $id)->first() ?: new SalesReport();
        $voc_kendala = VocKendala::all();

        return view('components.pdf-report-existing', compact('existing', 'sales_report', 'voc_kendala'));
    }

    public function downloadPDFreportexisting($id)
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


    public function savePlottingexisting(Request $request)
    {
        $ids = $request->input('ids');
        $userId = $request->input('user_id');

        // Update data dengan user_id yang dipilih
        Existing::whereIn('id', $ids)->update(['users_id' => $userId]);

        return response()->json(['success' => true]);
    }


    // Report Sales Existing
    public function indexreportexistingadminbillper(Request $request)
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


        return view('admin-billper.report-existing-adminbillper', compact('title', 'voc_kendalas', 'filterMonth', 'filterYear', 'sales', 'filterSales'));
    }




    public function getDatareportexisting(Request $request)
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




    public function downloadAllExcelreportexisting()
    {
        $reports = SalesReport::with('existings', 'user', 'vockendals')
            ->whereNotNull('existing_id') // Ensure only records with existing_id are included
            ->get();

        return Excel::download(new SalesReportBillperExisting($reports), 'Report_Existing_Semua.xlsx');
    }

    public function downloadFilteredExcelreportexisting(Request $request)
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
