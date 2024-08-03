<?php

use App\Http\Controllers\AdminBillperController;
use App\Http\Controllers\AdminPranpcController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();
Route::post('/login', [LoginController::class, 'authenticate']);


Route::prefix('login')->middleware(['auth', 'checkStatus'])->group(function () {
    // Route Super Admin
    Route::middleware(['SA'])->group(function () {
        // Dashboard
        Route::get('/super-admin', [SuperAdminController::class, 'index'])->name('super-admin.index');

        // Data Master
        Route::get('/data-master', [SuperAdminController::class, 'indexdatamaster'])->name('datamaster.index');
        Route::get('gettabeldatamaster', [SuperAdminController::class, 'getDatamasters'])->name('gettabeldatamaster');
        Route::post('tambah-pelanggan', [SuperAdminController::class, 'tambahPelanggan'])->name('tambah-pelanggan');
        Route::post('cek-filedatamaster', [SuperAdminController::class, 'cekFileDataMaster'])->name('cek.filedatamaster');
        Route::get('edit-datamasters/{id}', [SuperAdminController::class, 'editdatamasters'])->name('edit-datamasters');
        Route::post('update-datamasters/{id}', [SuperAdminController::class, 'updatedatamasters'])->name('update-datamasters');
        Route::delete('/destroy-datamasters/{id}', [SuperAdminController::class, 'destroydatamasters'])->name('destroy-datamasters');

        // Preview Data Master
        Route::get('/preview-data-master', [SuperAdminController::class, 'indexpreviewdatamaster'])->name('previewdatamaster.index');
        Route::get('gettabelpreviewdatamaster', [SuperAdminController::class, 'getPreviewDatamasters'])->name('gettabelpreviewdatamaster');
        Route::get('edit-previewdatamasters/{id}', [SuperAdminController::class, 'editpreviewdatamasters'])->name('edit-tempdatamasters');
        Route::post('update-previewdatamasters/{id}', [SuperAdminController::class, 'updatepreviewdatamasters'])->name('update-previewdatamasters');
        Route::post('/savedatamasters', [SuperAdminController::class, 'savetempdatamasters'])->name('savedatamasters');
        Route::post('deleteAllTempdatamasters', [SuperAdminController::class, 'deleteAllTempdatamasters'])->name('deleteAllTempdatamasters');
        Route::delete('/destroy-tempdatamasters/{id}', [SuperAdminController::class, 'destroytempdatamasters'])->name('destroy-tempdatamasters');



        // Tool Billper
        Route::get('/tool-billper', [SuperAdminController::class, 'indextoolbillper'])->name('toolsbillper.index');
        Route::post('/vlookup/checkFile1/billper', [SuperAdminController::class, 'checkFile1billper'])->name('vlookup.checkFile1billper');
        Route::post('/vlookup-billper', [SuperAdminController::class, 'vlookupbillper'])->name('vlookup.performbillper');
        Route::get('gettabeltempbillpers', [SuperAdminController::class, 'getDataTempbillpers'])->name('gettabeltempbillpers');
        Route::post('/savebillpers', [SuperAdminController::class, 'savetempbillpers'])->name('savealls');
        Route::post('deleteAllTempbillpers', [SuperAdminController::class, 'deleteAllTempbillpers'])->name('deleteAllTempbillpers');
        Route::delete('/destroy-tempbillpers/{id}', [SuperAdminController::class, 'destroyTempbillpers'])->name('destroy-tempbillpers');


        // Data Billper
        Route::get('/data-billper', [SuperAdminController::class, 'indexbillper'])->name('billper.index');
        Route::get('gettabelbillpers', [SuperAdminController::class, 'getDatabillpers'])->name('gettabelbillpers');
        Route::get('edit-billpers/{id}', [SuperAdminController::class, 'editbillpers'])->name('edit-billpers');
        Route::post('update-billpers/{id}', [SuperAdminController::class, 'updatebillpers'])->name('update-billpers');
        Route::get('/superadmin/billper/{id}/view', [SuperAdminController::class, 'viewPDFreportbillpersuperadmin'])->name('view-pdf-report-billpersuperadmin');
        Route::get('/superadmin/billper/{id}/download', [SuperAdminController::class, 'downloadPDFreportbillpersuperadmin'])->name('download-pdf-report-billpersuperadmin');
        Route::get('/download/excelbilpersuperadmin', [SuperAdminController::class, 'exportbillpersuperadmin'])->name('download.excelbillpersuperadmin');
        Route::post('/download/filtered/excelbilpersuperadmin', [SuperAdminController::class, 'downloadFilteredExcelbillpersuperadmin'])->name('download.filtered.excelbillpersuperadmin');
        Route::get('viewbillper/pdf/{id}', [SuperAdminController::class, 'viewgeneratePDFbillper'])->name('viewbillper.pdf');
        Route::get('billper/pdf/{id}', [SuperAdminController::class, 'generatePDFbillper'])->name('billper.pdf');
        Route::post('/cek-filepembayaranbillper', [SuperAdminController::class, 'checkFilePembayaranbillper'])->name('cek.filepembayaranbillper');
        Route::post('/cek-pembayaranbillper', [SuperAdminController::class, 'cekPembayaranbillper'])->name('cek-pembayaranbillper');
        Route::delete('/destroy-billpers/{id}', [SuperAdminController::class, 'destroybillpers'])->name('destroy-billpers');
        Route::get('/data-billper-riwayat', [SuperAdminController::class, 'indexbillperriwayat'])->name('billperriwayat.index');
        Route::get('gettabelbillpersriwayat', [SuperAdminController::class, 'getDatabillpersriwayat'])->name('gettabelbillpersriwayat');

        // Report Data Billper
        Route::get('/report-databillper', [SuperAdminController::class, 'indexreportbillper'])->name('reportdatabillper.index');
        Route::get('/grafik-databillper', [SuperAdminController::class, 'indexgrafikbillper'])->name('grafikdatabillper.index');

        // Report Sales Billper
        Route::get('/report-salesbillper', [SuperAdminController::class, 'indexreportsalesbillper'])->name('reportsalesbillper.index');
        Route::get('/get-data-reportbillpersuperadmin', [SuperAdminController::class, 'getDatareportbillpersuperadmin'])->name('getDatareportbillpersuperadmin');
        Route::get('/download/excelreportbillpersuperadmin', [SuperAdminController::class, 'downloadAllExcelreportbillpersuperadmin'])->name('download.excelreportbillpersuperadmin');
        Route::post('/download/filtered/excelreportbillpersuperadmin', [SuperAdminController::class, 'downloadFilteredExcelreportbillpersuperadmin'])->name('download.filtered.excelreportbillpersuperadmin');





        

        // Tool Pra NPC
        Route::get('/tool-pranpc', [SuperAdminController::class, 'indextoolpranpc'])->name('toolspranpc.index');
        Route::get('gettabeltemppranpcs', [SuperAdminController::class, 'getDataTemppranpcs'])->name('gettabeltemppranpcs');
        Route::post('/upload/checkFile1', [SuperAdminController::class, 'checkFile1pranpc'])->name('upload.checkFile1pranpc');
        Route::post('/upload', [SuperAdminController::class, 'upload'])->name('upload.perform');
        Route::post('/savepranpcs', [SuperAdminController::class, 'savetemppranpcs'])->name('savepranpcs');
        Route::post('deleteAllTemppranpcs', [SuperAdminController::class, 'deleteAllTemppranpcs'])->name('deleteAllTemppranpcs');
        Route::delete('/destroy-temppranpcs/{id}', [SuperAdminController::class, 'destroyTemppranpcs'])->name('destroy-temppranpcs');


        // Data PraNPC
        Route::get('/data-pranpc', [SuperAdminController::class, 'indexpranpc'])->name('pranpc.index');
        Route::get('gettabelpranpcs', [SuperAdminController::class, 'getDatapranpcs'])->name('gettabelpranpcs');
        Route::get('edit-pranpcs/{id}', [SuperAdminController::class, 'editpranpcs'])->name('edit-pranpcs');
        Route::post('update-pranpcs/{id}', [SuperAdminController::class, 'updatepranpcs'])->name('update-pranpcs');
        Route::get('/superadminadmin/pranpc/{id}/view', [SuperAdminController::class, 'viewPDFreportpranpcsuperadmin'])->name('view-pdf-report-pranpcsuperadmin');
        Route::get('/superadminadmin/pranpc/{id}/download', [SuperAdminController::class, 'downloadPDFreportpranpcsuperadmin'])->name('download-pdf-report-pranpcsuperadmin');
        Route::get('/download/excelpranpc', [SuperAdminController::class, 'exportpranpc'])->name('download.excelpranpc');
        Route::post('/download/filtered/excelpranpc', [SuperAdminController::class, 'downloadFilteredExcelpranpc'])->name('download.filtered.excelpranpc');
        Route::get('viewpranpc/pdf/{id}', [SuperAdminController::class, 'viewgeneratePDFpranpc'])->name('viewpranpc.pdf');
        Route::get('pranpc/pdf/{id}', [SuperAdminController::class, 'generatePDFpranpc'])->name('pranpc.pdf');
        Route::delete('/destroy-pranpcs/{id}', [SuperAdminController::class, 'destroypranpcs'])->name('destroy-pranpcs');
        Route::get('/data-pranp-criwayat', [SuperAdminController::class, 'indexpranpcriwayat'])->name('pranpcriwayat.index');
        Route::get('gettabelpranpcsriwayat', [SuperAdminController::class, 'getDatapranpcsriwayat'])->name('gettabelpranpcsriwayat');

        // Report Data Pranpc
        Route::get('/report-datapranpc', [SuperAdminController::class, 'indexreportpranpc'])->name('reportdatapranpc.index');

        // Report Sales pranpc
        Route::get('/report-salespranpc', [SuperAdminController::class, 'indexreportsalespranpc'])->name('reportsalespranpc.index');
        Route::get('/get-data-reportpranpcsuperadmin', [SuperAdminController::class, 'getDatareportpranpcsuperadmin'])->name('getDatareportpranpcsuperadmin');
        Route::get('/download/excelreportpranpcsuperadmin', [SuperAdminController::class, 'downloadAllExcelreportpranpcsuperadmin'])->name('download.excelreportpranpcsuperadmin');
        Route::post('/download/filtered/excelreportpranpcsuperadmin', [SuperAdminController::class, 'downloadFilteredExcelreportpranpcsuperadmin'])->name('download.filtered.excelreportpranpcsuperadmin');




        // Data Akun
        Route::get('/data-akun', [SuperAdminController::class, 'indexdataakun'])->name('data-akun.index');
        Route::post('/update-status', [SuperAdminController::class, 'updatestatus'])->name('updatestatus');
        Route::delete('/akun/{id}', [SuperAdminController::class, 'destroyakun'])->name('destroy-akun');
    });

    // Route Admin Billper
    Route::middleware(['AD-B'])->group(function () {
        Route::get('/admin-billper', [AdminBillperController::class, 'index'])->name('adminbillper.index');

        // Data All Admin billper
        Route::get('/report-all-adminbillper', [AdminBillperController::class, 'indexreportalladminbillper'])->name('report-all-adminbillper.index');
        Route::get('/data-all-adminbillper', [AdminBillperController::class, 'indexalladminbillper'])->name('all-adminbillper.index');
        Route::get('gettabelbillpersadminbillper', [AdminBillperController::class, 'getDatabillpersadminbillper'])->name('gettabelbillpersadminbillper');
        Route::get('/download/excelbillperadminbillper', [AdminBillperController::class, 'exportbillper'])->name('download.excelbillperadminbillper');
        Route::post('/download/filtered/excelbillperadminbillper', [AdminBillperController::class, 'downloadFilteredExcelbillper'])->name('download.filtered.excelbillperadminbillper');
        Route::get('edit-billpersadminbillper/{id}', [AdminBillperController::class, 'editbillpersadminbillper'])->name('edit-billpersadminbillper');
        Route::get('viewbillperexistingadminbillper/pdf/{id}', [AdminBillperController::class, 'viewgeneratePDFbillperexistingadminbillper'])->name('viewbillperexistingadminbillper.pdf');
        Route::get('billperexistingadminbillper/pdf/{id}', [AdminBillperController::class, 'generatePDFbillperexistingadminbillper'])->name('billperexistingadminbillper.pdf');
        Route::post('update-billpersadminbillper/{id}', [AdminBillperController::class, 'updatebillpersadminbillper'])->name('update-billpersadminbillper');
        Route::get('/admin/billper/{id}/view', [AdminBillperController::class, 'viewPDFreportbillper'])->name('view-pdf-report-billper');
        Route::get('/admin/billper/{id}/download', [AdminBillperController::class, 'downloadPDFreportbillper'])->name('download-pdf-report-billper');
        Route::get('/get-data-reportbillper', [AdminBillperController::class, 'getDatareportbillper'])->name('getDatareportbillper');
        Route::get('/download/excelreportbillper', [AdminBillperController::class, 'downloadAllExcelreportbillper'])->name('download.excelreportbillper');
        Route::post('/download/filtered/excelreportbillper', [AdminBillperController::class, 'downloadFilteredExcelreportbillper'])->name('download.filtered.excelreportbillper');
        Route::post('/savePlottingbillper', [AdminBillperController::class, 'savePlotting'])->name('savePlottingbillper');
    });

    // Route Admin PraNPC
    Route::middleware(['AD-P'])->group(function () {
        Route::get('/admin-pranpc', [AdminPranpcController::class, 'index'])->name('adminpranpc.index');

        // Data Pranpc admin
        Route::get('/report-pranpc-adminpranpc', [AdminPranpcController::class, 'indexreportpranpcadminpranpc'])->name('report-pranpc-adminpranpc.index');
        Route::get('/get-data-reportpranpc', [AdminPranpcController::class, 'getDatareportpranpc'])->name('getDatareportpranpc');
        Route::get('/data-pranpc-adminpranpc', [AdminPranpcController::class, 'indexpranpcadminpranpc'])->name('pranpc-adminpranpc.index');
        Route::get('gettabelpranpcadminpranpc', [AdminPranpcController::class, 'getDatapranpcsadminpranpc'])->name('gettabelpranpcadminpranpc');
        Route::get('/download/exceladminpranpc', [AdminPranpcController::class, 'exportpranpc'])->name('download.exceladminpranpc');
        Route::post('/download/filtered/exceladminpranpc', [AdminPranpcController::class, 'downloadFilteredExcelpranpc'])->name('download.filtered.exceladminpranpc');
        Route::get('edit-pranpcsadminpranpc/{id}', [AdminPranpcController::class, 'editpranpcsadminpranpc'])->name('edit-pranpcsadminpranpc');
        Route::post('update-pranpcsadminpranpc/{id}', [AdminPranpcController::class, 'updatepranpcsadminpranpc'])->name('update-pranpcsadminpranpc');
        Route::get('viewpranpcadminpranpc/pdf/{id}', [AdminPranpcController::class, 'viewgeneratePDFpranpcadminpranpc'])->name('viewpranpcadminpranpc.pdf');
        Route::get('pranpcadminpranpc/pdf/{id}', [AdminPranpcController::class, 'generatePDFpranpcadminpranpc'])->name('pranpcadminpranpc.pdf');
        Route::get('/admin/pranpc/{id}/view', [AdminPranpcController::class, 'viewPDFreportpranpc'])->name('view-pdf-report-pranpc');
        Route::get('/admin/pranpc/{id}/download', [AdminPranpcController::class, 'downloadPDFreportpranpc'])->name('download-pdf-report-pranpc');
        Route::get('/download/excelreportpranpc', [AdminPranpcController::class, 'downloadAllExcelreportpranpc'])->name('download.excelreportpranpc');
        Route::post('/download/filtered/excelreportpranpc', [AdminPranpcController::class, 'downloadFilteredExcelreportpranpc'])->name('download.filtered.excelreportpranpc');
        Route::post('/savePlottingpranpc', [AdminPranpcController::class, 'savePlotting'])->name('savePlottingpranpc');


        // Data Existing Admin
        Route::get('/report-existing-adminpranpc', [AdminPranpcController::class, 'indexreportexistingadminpranpc'])->name('report-existing-adminpranpc.index');
        Route::get('/get-data-reportexisting', [AdminPranpcController::class, 'getDatareportexisting'])->name('getDatareportexisting');
        Route::get('/data-existing-adminpranpc', [AdminPranpcController::class, 'indexexistingadminpranpc'])->name('existing-adminpranpc.index');
        Route::get('gettabelexistingsadminpranpc', [AdminPranpcController::class, 'getDataexistingsadminpranpc'])->name('gettabelexistingsadminpranpc');
        Route::get('/download/excelexistingadminpranpc', [AdminPranpcController::class, 'exportexisting'])->name('download.excelexistingadminpranpc');
        Route::post('/download/filtered/excelexistingadminpranpc', [AdminPranpcController::class, 'downloadFilteredExcelexisting'])->name('download.filtered.excelexistingadminpranpc');
        Route::get('edit-existingsadminpranpc/{id}', [AdminPranpcController::class, 'editexistingsadminpranpc'])->name('edit-existingsadminpranpc');
        Route::post('update-existingsadminpranpc/{id}', [AdminPranpcController::class, 'updateexistingsadminpranpc'])->name('update-existingsadminpranpc');
        Route::get('viewexistingadminpranpc/pdf/{id}', [AdminPranpcController::class, 'viewgeneratePDFexistingadminpranpc'])->name('viewexistingadminpranpc.pdf');
        Route::get('existingadminpranpc/pdf/{id}', [AdminPranpcController::class, 'generatePDFexistingadminpranpc'])->name('existingadminpranpc.pdf');
        Route::get('/admin/pranpc/{id}/view', [AdminPranpcController::class, 'viewPDFreportpranpc'])->name('view-pdf-report-pranpc');
        Route::get('/admin/existing/{id}/view', [AdminPranpcController::class, 'viewPDFreportexisting'])->name('view-pdf-report-existing');
        Route::get('/admin/existing/{id}/download', [AdminPranpcController::class, 'downloadPDFreportexisting'])->name('download-pdf-report-existing');
        Route::get('/download/excelreportexisting', [AdminPranpcController::class, 'downloadAllExcelreportexisting'])->name('download.excelreportexisting');
        Route::post('/download/filtered/excelreportexisting', [AdminPranpcController::class, 'downloadFilteredExcelreportexisting'])->name('download.filtered.excelreportexisting');
        Route::post('/savePlottingexisting', [AdminPranpcController::class, 'savePlottingexisting'])->name('savePlottingexisting');
    });


    // Route User
    Route::middleware(['US'])->group(function () {
        // Dashboard
        Route::get('/user', [UserController::class, 'index'])->name('user.index');


        // Assignment Billper
        Route::get('/assignment-billper', [UserController::class, 'indexassignmentbillper'])->name('assignmentbillper.index');
        Route::get('gettabelassignmentbillper', [UserController::class, 'getDataassignmentbillper'])->name('gettabelassignmentbillper');
        Route::get('info-assignmentbillper/{id}', [UserController::class, 'infoassignmentbillper'])->name('info-assignmentbillper');
        Route::post('update-assignmentbillper/{id}', [UserController::class, 'updateassignmentbillper'])->name('update-assignmentbillper');


        // Report Assignment Billper
        Route::get('/report-assignment-billper', [UserController::class, 'indexreportassignmentbillper'])->name('reportassignmentbillper.index');
        Route::get('gettabelreportassignmentbillper', [UserController::class, 'getDatareportassignmentbillper'])->name('gettabelreportassignmentbillper');
        Route::get('info-reportassignmentbillper/{id}', [UserController::class, 'inforeportassignmentbillper'])->name('info-reportassignmentbillper');
        Route::post('update-reportassignmentbillper/{id}', [UserController::class, 'updatereportassignmentbillper'])->name('update-reportassignmentbillper');
        Route::post('/reset-reportassignmentbillper/{id}', [UserController::class, 'resetReportAssignmentbillper'])->name('reset-reportassignmentbillper');


        // Assignment Pranpc
        Route::get('/assignment-pranpc', [UserController::class, 'indexassignmentpranpc'])->name('assignmentpranpc.index');
        Route::get('gettabelassignmentpranpc', [UserController::class, 'getDataassignmentpranpc'])->name('gettabelassignmentpranpc');
        Route::get('info-assignmentpranpc/{id}', [UserController::class, 'infoassignmentpranpc'])->name('info-assignmentpranpc');
        Route::post('update-assignmentpranpc/{id}', [UserController::class, 'updateassignmentpranpc'])->name('update-assignmentpranpc');



        // Report Assignment Pranpc
        Route::get('/report-assignment-pranpc', [UserController::class, 'indexreportassignmentpranpc'])->name('reportassignmentpranpc.index');
        Route::get('gettabelreportassignmentpranpc', [UserController::class, 'getDatareportassignmentpranpc'])->name('gettabelreportassignmentpranpc');
        Route::get('info-reportassignmentpranpc/{id}', [UserController::class, 'inforeportassignmentpranpc'])->name('info-reportassignmentpranpc');
        Route::post('update-reportassignmentpranpc/{id}', [UserController::class, 'updatereportassignmentpranpc'])->name('update-reportassignmentpranpc');
        Route::post('/reset-reportassignmentpranpc/{id}', [UserController::class, 'resetReportAssignmentpranpc'])->name('reset-reportassignmentpranpc');
    });
});
