<?php

namespace App\Http\Controllers;

use App\Models\All;
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
        $voc_kendala = VocKendala::all(); // Assuming you have a model named VocKendala
        $sales_report = SalesReport::all();
        return view('user.info-assignmentbillper', compact('title', 'all', 'voc_kendala', 'sales_report'));
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
        $all->status_pembayaran = $request->input('status_pembayaran');
        $all->save();

        // Update the SalesReport model
        $report = new SalesReport; // Make sure to use findOrFail() to update existing records
        $report->users_id = $request->input('users_id');
        $report->all_id = $request->input('all_id');
        $report->snd = $request->input('snd');
        $report->witel = $request->input('witel');
        $report->waktu_visit = $request->input('waktu_visit');
        $report->voc_kendalas_id = $request->input('voc_kendalas_id');
        $report->follow_up = $request->input('follow_up');

        // Handle evidence_sales file upload
        if ($request->hasFile('evidence_sales')) {
            $file = $request->file('evidence_sales');
            $filename = $all->nama . '_' . $all->no_inet . '_evidence_sales_' . now()->format('Ymd_His') . '_' . $report->users_id . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/file_evidence', $filename); // Save to storage/app/public/file_evidence
            $report->evidence_sales = $filename;
        }

        // Handle evidence_pembayaran file upload
        if ($request->hasFile('evidence_pembayaran')) {
            $file = $request->file('evidence_pembayaran');
            $filename = $all->nama . '_' . $all->no_inet . '_evidence_pembayaran_' . now()->format('Ymd_His') . '_' . $report->users_id . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/file_evidence', $filename); // Save to storage/app/public/file_evidence
            $report->evidence_pembayaran = $filename;
        }

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
                        ->where('status_pembayaran', 'Pending');
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

        // Retrieve the current status_pembayaran
        $currentStatusPembayaran = $all->status_pembayaran;

        // Update the status_pembayaran in the related All model
        $statusPembayaran = $request->input('status_pembayaran');
        $all->status_pembayaran = $statusPembayaran;
        $all->save();

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

        // Check if the status_pembayaran was changed to Unpaid
        if ($statusPembayaran === 'Unpaid' && $currentStatusPembayaran !== 'Unpaid') {
            // Delete the related SalesReport entries and their files
            $salesReports = $all->salesReports;
            foreach ($salesReports as $salesReport) {
                // Delete evidence files if they exist
                if ($salesReport->evidence_sales) {
                    Storage::delete('public/file_evidence/' . $salesReport->evidence_sales);
                }
                if ($salesReport->evidence_pembayaran) {
                    Storage::delete('public/file_evidence/' . $salesReport->evidence_pembayaran);
                }

                // Delete the SalesReport record
                $salesReport->delete();
            }
        }

        // Set a success message and redirect
        Alert::success('Data Berhasil Diperbarui');
        return redirect()->route('reportassignmentbillper.index');
    }








    // Assignment Pranpc
    public function indexassignmentpranpc()
    {
        $title = 'Assignment Pranpc';
        return view('user.assignmentpranpc', compact('title'));
    }


    // Report Assignment Pranpc
    public function indexreportassignmentpranpc()
    {
        $title = 'Report Assignment Pranpc';
        return view('user.reportassignmentpranpc', compact('title'));
    }
}
