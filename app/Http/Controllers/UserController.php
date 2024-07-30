<?php

namespace App\Http\Controllers;

use App\Models\All;
use App\Models\Pranpc;
use App\Models\SalesReport;
use App\Models\VocKendala;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{
    public function index()
    {
        $title = 'WSBS Collection';
        return view('user.index', compact('title'));
    }

    // Assignment Billper
    public function indexassignmentbillper()
    {
        $title = 'Assignment Billper';
        return view('user.assignmentbillper', compact('title'));
    }

    public function getDataassignmentbillper(Request $request)
    {
        if ($request->ajax()) {
            $userId = Auth::id(); // Mendapatkan ID pengguna yang sedang masuk
            $query = All::where('users_id', $userId) // Memfilter data berdasarkan ID pengguna
                ->where('status_pembayaran', 'Unpaid'); // Memfilter data dengan status pembayaran 'Unpaid'

            $data_alls = $query->get();
            return datatables()->of($data_alls)
                ->addIndexColumn()
                ->addColumn('opsi-tabel-assignmentbillper', function ($all) {
                    return view('components.opsi-tabel-assignmentbillper', compact('all'));
                })
                ->toJson();
        }
    }

    public function infoassignmentbillper($id)
    {
        $title = 'Info Assignment';
        $all = All::with('user')->findOrFail($id);
        $voc_kendala = VocKendala::all(); // Mengambil semua data VocKendala

        // Mengambil SalesReport terbaru yang terkait dengan All tertentu
        $sales_report = SalesReport::where('all_id', $id)->orderBy('created_at', 'desc')->first();

        // Mengecek apakah ada data di sales_report dan mengecek jumlah visit
        $isSalesReportEmpty = $sales_report ? false : true;
        $jmlh_visit = $sales_report ? $sales_report->jmlh_visit : null;

        return view('user.info-assignmentbillper', compact('title', 'all', 'voc_kendala', 'sales_report', 'isSalesReportEmpty', 'jmlh_visit'));
    }




    public function updateassignmentbillper(Request $request, $id)
    {
        // Update the All model
        $all = All::findOrFail($id);
        $all->nama = $request->input('nama');
        $all->no_inet = $request->input('no_inet');
        $all->saldo = $request->input('saldo');
        $all->no_tlf = $request->input('no_tlf');
        $all->email = $request->input('email');
        $all->sto = $request->input('sto');
        $all->produk = $request->input('produk');
        $all->umur_customer = $request->input('umur_customer');

        // Initially set the status_pembayaran from the request
        $all->status_pembayaran = $request->input('status_pembayaran');

        // Update the SalesReport model
        $report = new SalesReport; // Make sure to use findOrFail() to update existing records
        $report->users_id = $request->input('users_id');
        $report->all_id = $request->input('all_id');
        $report->snd = $request->input('snd');
        $report->witel = $request->input('witel');
        $report->waktu_visit = $request->input('waktu_visit');
        $report->voc_kendalas_id = $request->input('voc_kendalas_id');
        $report->follow_up = $request->input('follow_up');
        $report->jmlh_visit = $request->input('jmlh_visit');

        // Flag to check if any evidence file is uploaded
        $evidenceUploaded = false;

        // Handle evidence_sales file upload
        if ($request->hasFile('evidence_sales')) {
            $file = $request->file('evidence_sales');
            $filename = $all->nama . '_' . $all->no_inet . '_evidence_sales_' . now()->format('Ymd_His') . '_' . $report->users_id . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/file_evidence', $filename); // Save to storage/app/public/file_evidence
            $report->evidence_sales = $filename;

            // Set flag to true if evidence_sales is uploaded
            $evidenceUploaded = true;
        }

        // Handle evidence_pembayaran file upload
        if ($request->hasFile('evidence_pembayaran')) {
            $file = $request->file('evidence_pembayaran');
            $filename = $all->nama . '_' . $all->no_inet . '_evidence_pembayaran_' . now()->format('Ymd_His') . '_' . $report->users_id . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/file_evidence', $filename); // Save to storage/app/public/file_evidence
            $report->evidence_pembayaran = $filename;

            // Set flag to true if evidence_pembayaran is uploaded
            $evidenceUploaded = true;
        }

        // If any evidence file is uploaded, change status_pembayaran to "Pending"
        if ($evidenceUploaded) {
            $all->status_pembayaran = 'Pending';
        }

        // Save the updated All model
        $all->save();

        // Save the updated SalesReport model
        $report->save();

        Alert::success('Data Berhasil Diperbarui');
        return redirect()->route('assignmentbillper.index');
    }





    // Report Assignment Billper
    public function indexreportassignmentbillper()
    {
        $title = 'Report Assignment Billper';
        return view('user.reportassignmentbillper', compact('title'));
    }


    public function getDatareportassignmentbillper(Request $request)
    {
        if ($request->ajax()) {
            $userId = Auth::id(); // Get the ID of the currently authenticated user

            $query = SalesReport::with('alls') // Ensure 'alls' relationship is eager loaded
                ->whereHas('alls', function ($query) use ($userId) {
                    $query->where('users_id', $userId)
                        ->whereIn('status_pembayaran', ['Pending', 'Paid']);
                });

            $data_sales_reports = $query->get(); // Get the data

            return datatables()->of($data_sales_reports)
                ->addIndexColumn()
                ->addColumn('opsi-tabel-reportassignmentbillper', function ($sales_report) {
                    return view('components.opsi-tabel-reportassignmentbillper', compact('sales_report'));
                })
                ->toJson();
        }
    }


    public function inforeportassignmentbillper($id)
    {
        $title = 'Info Report Assignment';
        $sales_report = SalesReport::with('user', 'alls')->findOrFail($id);

        // Retrieve all VocKendala records
        $voc_kendala = VocKendala::all();

        return view('user.info-reportassignmentbillper', compact('title', 'sales_report', 'voc_kendala'));
    }


    public function updatereportassignmentbillper(Request $request, $id)
    {
        // Find the SalesReport model by ID
        $report = SalesReport::findOrFail($id);

        // Retrieve related All model
        $all = $report->alls; // Ensure this matches your model relationship

        // Update fields from request
        $report->users_id = $request->input('users_id');
        $report->snd = $request->input('snd');
        $report->witel = $request->input('witel');
        $report->waktu_visit = $request->input('waktu_visit');
        $report->voc_kendalas_id = $request->input('voc_kendalas_id');
        $report->follow_up = $request->input('follow_up');
        $report->jmlh_visit = $request->input('jmlh_visit');

        // Update the status_pembayaran in the related All model
        if ($all) {
            $statusPembayaran = $request->input('status_pembayaran');
            $all->status_pembayaran = $statusPembayaran;
            $all->save();
        }

        // Handle evidence_sales file upload
        if ($request->hasFile('evidence_sales')) {
            // Delete the old file if it exists
            if ($report->evidence_sales) {
                Storage::delete('public/file_evidence/' . $report->evidence_sales);
            }

            // Store the new file
            $file = $request->file('evidence_sales');
            $filename = $all->nama . '_' . $all->no_inet . '_evidence_sales_' . now()->format('Ymd_His') . '_' . $report->users_id . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/file_evidence', $filename);
            $report->evidence_sales = $filename;
        }

        // Handle evidence_pembayaran file upload
        if ($request->hasFile('evidence_pembayaran')) {
            // Delete the old file if it exists
            if ($report->evidence_pembayaran) {
                Storage::delete('public/file_evidence/' . $report->evidence_pembayaran);
            }

            // Store the new file
            $file = $request->file('evidence_pembayaran');
            $filename = $all->nama . '_' . $all->no_inet . '_evidence_pembayaran_' . now()->format('Ymd_His') . '_' . $report->users_id . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/file_evidence', $filename);
            $report->evidence_pembayaran = $filename;
        }

        // Save the updated report
        $report->save();

        // Set a success message and redirect
        Alert::success('Data Berhasil Diperbarui');
        return redirect()->route('reportassignmentbillper.index');
    }



    public function resetReportAssignmentbillper($id)
    {
        // Find the SalesReport model by ID
        $report = SalesReport::findOrFail($id);

        // Retrieve related All model
        $all = $report->alls;

        if ($all) {
            // Update status_pembayaran to 'Unpaid'
            $all->status_pembayaran = 'Unpaid';
            $all->save();
        }

        // Delete evidence files if they exist
        if ($report->evidence_sales) {
            Storage::delete('public/file_evidence/' . $report->evidence_sales);
        }
        if ($report->evidence_pembayaran) {
            Storage::delete('public/file_evidence/' . $report->evidence_pembayaran);
        }

        // Delete the SalesReport record
        $report->delete();

        // Set a success message and redirect
        Alert::success('Data Berhasil Direset');
        return redirect()->route('reportassignmentbillper.index');
    }





    // Assignment Pranpc
    public function indexassignmentpranpc()
    {
        $title = 'Assignment Pranpc';
        return view('user.assignmentpranpc', compact('title'));
    }

    public function getDataassignmentpranpc(Request $request)
    {
        if ($request->ajax()) {
            $userId = Auth::id(); // Mendapatkan ID pengguna yang sedang masuk
            $query = Pranpc::where('users_id', $userId) // Memfilter data berdasarkan ID pengguna
                ->where('status_pembayaran', 'Unpaid'); // Memfilter data dengan status pembayaran 'Unpaid'

            return datatables()->of($query)
                ->addIndexColumn()
                ->addColumn('opsi-tabel-assignmentpranpc', function ($pranpc) {
                    return view('components.opsi-tabel-assignmentpranpc', compact('pranpc'));
                })
                ->toJson();
        }
    }

    public function infoassignmentpranpc($id)
    {
        $title = 'Info Assignment';
        $pranpc = Pranpc::with('user')->findOrFail($id);
        $voc_kendala = VocKendala::all(); // Assuming you have a model named VocKendala

        // Mengambil SalesReport terbaru yang terkait dengan All tertentu
        $sales_report = SalesReport::where('pranpc_id', $id)->orderBy('created_at', 'desc')->first();

        // Mengecek apakah ada data di sales_report dan mengecek jumlah visit
        $isSalesReportEmpty = $sales_report ? false : true;
        $jmlh_visit = $sales_report ? $sales_report->jmlh_visit : null;
        return view('user.info-assignmentpranpc', compact('title', 'pranpc', 'voc_kendala', 'sales_report', 'isSalesReportEmpty', 'jmlh_visit'));
    }


    public function updateassignmentpranpc(Request $request, $id)
    {
        // Find the Pranpc model by its ID
        $pranpc = Pranpc::findOrFail($id);

        // Update the Pranpc model with the new values from the request
        $pranpc->nama = $request->input('nama');
        $pranpc->snd = $request->input('snd');
        $pranpc->sto = $request->input('sto');
        $pranpc->alamat = $request->input('alamat');
        $pranpc->bill_bln = $request->input('bill_bln');
        $pranpc->bill_bln1 = $request->input('bill_bln1');
        $pranpc->mintgk = $request->input('mintgk');
        $pranpc->maxtgk = $request->input('maxtgk');
        $pranpc->status_pembayaran = $request->input('status_pembayaran');
        $pranpc->multi_kontak1 = $request->input('multi_kontak1');
        $pranpc->email = $request->input('email');
        $pranpc->users_id = $request->input('users_id');

        // Update the SalesReport model
        $report = new SalesReport; // Make sure to use findOrFail() to update existing records
        $report->users_id = $request->input('users_id');
        $report->pranpc_id = $request->input('pranpc_id');
        $report->snd = $request->input('snd');
        $report->witel = $request->input('witel');
        $report->waktu_visit = $request->input('waktu_visit');
        $report->voc_kendalas_id = $request->input('voc_kendalas_id');
        $report->follow_up = $request->input('follow_up');
        $report->jmlh_visit = $request->input('jmlh_visit');

        // Handle evidence file uploads
        $evidenceUploaded = false;

        // Handle evidence_sales file upload
        if ($request->hasFile('evidence_sales')) {
            $file = $request->file('evidence_sales');
            $filename = $pranpc->nama . '_' . $pranpc->snd . '_evidence_sales_' . now()->format('Ymd_His') . '_' . $report->users_id . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/file_evidence', $filename); // Save to storage/app/public/file_evidence
            $report->evidence_sales = $filename;
            $evidenceUploaded = true;
        }

        // Handle evidence_pembayaran file upload
        if ($request->hasFile('evidence_pembayaran')) {
            $file = $request->file('evidence_pembayaran');
            $filename = $pranpc->nama . '_' . $pranpc->snd . '_evidence_pembayaran_' . now()->format('Ymd_His') . '_' . $report->users_id . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/file_evidence', $filename); // Save to storage/app/public/file_evidence
            $report->evidence_pembayaran = $filename;
            $evidenceUploaded = true;
        }

        // Update status_pembayaran if any evidence file is uploaded
        if ($evidenceUploaded) {
            $pranpc->status_pembayaran = 'Pending';
        }

        // Save the updated Pranpc model
        $pranpc->save();

        // Save the updated SalesReport model
        $report->save();

        Alert::success('Data Berhasil Diperbarui');
        return redirect()->route('assignmentpranpc.index');
    }




    // Report Assignment Pranpc
    public function indexreportassignmentpranpc()
    {
        $title = 'Report Assignment Pranpc';
        return view('user.reportassignmentpranpc', compact('title'));
    }


    public function getDatareportassignmentpranpc(Request $request)
    {
        if ($request->ajax()) {
            $userId = Auth::id(); // Get the ID of the currently authenticated user

            $query = SalesReport::with('pranpcs') // Ensure 'pranpcs' relationship is eager loaded
                ->whereHas('pranpcs', function ($query) use ($userId) {
                    $query->where('users_id', $userId)
                        ->whereIn('status_pembayaran', ['Pending', 'Paid']);
                });


            $data_sales_reports = $query->get(); // Get the data

            return datatables()->of($data_sales_reports)
                ->addIndexColumn()
                ->addColumn('opsi-tabel-reportassignmentpranpc', function ($sales_report) {
                    return view('components.opsi-tabel-reportassignmentpranpc', compact('sales_report'));
                })
                ->toJson();
        }
    }


    public function inforeportassignmentpranpc($id)
    {
        $title = 'Info Report Assignment';
        $sales_report = SalesReport::with('user', 'pranpcs')->findOrFail($id);

        // Retrieve all VocKendala records
        $voc_kendala = VocKendala::all();

        return view('user.info-reportassignmentpranpc', compact('title', 'sales_report', 'voc_kendala'));
    }


    public function updatereportassignmentpranpc(Request $request, $id)
    {
        // Find the SalesReport model by ID
        $report = SalesReport::findOrFail($id);

        // Retrieve related pranpc model
        $pranpc = $report->pranpcs; // Ensure this matches your model relationship

        // Update fields from request
        $report->users_id = $request->input('users_id');
        $report->snd = $request->input('snd');
        $report->witel = $request->input('witel');
        $report->waktu_visit = $request->input('waktu_visit');
        $report->voc_kendalas_id = $request->input('voc_kendalas_id');
        $report->follow_up = $request->input('follow_up');
        $report->jmlh_visit = $request->input('jmlh_visit');

        // Update the status_pembayaran in the related pranpc model
        if ($pranpc) {
            $statusPembayaran = $request->input('status_pembayaran');
            $pranpc->status_pembayaran = $statusPembayaran;
            $pranpc->save();
        }

        // Handle evidence_sales file upload
        if ($request->hasFile('evidence_sales')) {
            // Delete the old file if it exists
            if ($report->evidence_sales) {
                Storage::delete('public/file_evidence/' . $report->evidence_sales);
            }

            // Store the new file
            $file = $request->file('evidence_sales');
            $filename = $pranpc->nama . '_' . $pranpc->no_inet . '_evidence_sales_' . now()->format('Ymd_His') . '_' . $report->users_id . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/file_evidence', $filename);
            $report->evidence_sales = $filename;
        }

        // Handle evidence_pembayaran file upload
        if ($request->hasFile('evidence_pembayaran')) {
            // Delete the old file if it exists
            if ($report->evidence_pembayaran) {
                Storage::delete('public/file_evidence/' . $report->evidence_pembayaran);
            }

            // Store the new file
            $file = $request->file('evidence_pembayaran');
            $filename = $pranpc->nama . '_' . $pranpc->no_inet . '_evidence_pembayaran_' . now()->format('Ymd_His') . '_' . $report->users_id . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/file_evidence', $filename);
            $report->evidence_pembayaran = $filename;
        }

        // Save the updated report
        $report->save();

        // Set a success message and redirect
        Alert::success('Data Berhasil Diperbarui');
        return redirect()->route('reportassignmentpranpc.index');
    }



    public function resetReportAssignmentpranpc($id)
    {
        // Find the SalesReport model by ID
        $report = SalesReport::findOrFail($id);

        // Retrieve related pranpc model
        $pranpc = $report->pranpcs;

        if ($pranpc) {
            // Update status_pembayaran to 'Unpaid'
            $pranpc->status_pembayaran = 'Unpaid';
            $pranpc->save();
        }

        // Delete evidence files if they exist
        if ($report->evidence_sales) {
            Storage::delete('public/file_evidence/' . $report->evidence_sales);
        }
        if ($report->evidence_pembayaran) {
            Storage::delete('public/file_evidence/' . $report->evidence_pembayaran);
        }

        // Delete the SalesReport record
        $report->delete();

        // Set a success message and redirect
        Alert::success('Data Berhasil Direset');
        return redirect()->route('reportassignmentpranpc.index');
    }
}
