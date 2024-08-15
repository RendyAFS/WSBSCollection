<?php

use App\Http\Controllers\AdminBillperController;
use App\Http\Controllers\AdminPranpcController;
use App\Http\Controllers\AkunController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
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
    return view('auth.login');
});
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();
Route::post('/login', [LoginController::class, 'authenticate']);

// Profile
Route::get('/profile', [AkunController::class, 'index'])->name('profile.index');
Route::post('/profile/update', [AkunController::class, 'update'])->name('profile.update');


// OTP dan forgot password
Route::get('/forgot-password', [OtpController::class, 'indexforgotpassword'])->name('forgot-password');
Route::post('/reset-password', [OtpController::class, 'sendResetLink'])->name('resetPassword');
Route::get('/reset-password/{token}', [OtpController::class, 'indexresetpassword'])->name('reset-password');
Route::post('/update-password', [OtpController::class, 'updatePassword'])->name('updatePassword');

Route::post('/verify-otp', [OtpController::class, 'verifyOtp'])->name('verifyOtp');
Route::post('/request-otp', [OtpController::class, 'requestOtp'])->name('requestOtp');




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
        Route::get('gettabelpreviewdatamaster', [SuperAdminController::class, 'getTempDatamasters'])->name('gettabelpreviewdatamaster');
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
        Route::post('/savebillpers', [SuperAdminController::class, 'savetempbillpers'])->name('savebillpers');
        Route::post('deleteAllTempbillpers', [SuperAdminController::class, 'deleteAllTempbillpers'])->name('deleteAllTempbillpers');
        Route::delete('/destroy-tempbillpers/{id}', [SuperAdminController::class, 'destroyTempbillpers'])->name('destroy-tempbillpers');


        // Data Billper
        Route::get('/data-billper', [SuperAdminController::class, 'indexbillper'])->name('billper.index');
        Route::get('gettabelbillpers', [SuperAdminController::class, 'getDatabillpers'])->name('gettabelbillpers');
        Route::get('edit-billpers/{id}', [SuperAdminController::class, 'editbillpers'])->name('edit-billpers');
        Route::post('update-billpers/{id}', [SuperAdminController::class, 'updatebillpers'])->name('update-billpers');
        Route::get('/superadmin/billper/{id}/view', [SuperAdminController::class, 'viewPDFreportbillpersuperadmin'])->name('view-pdf-report-billpersuperadmin');
        Route::get('/superadmin/billper/{id}/download', [SuperAdminController::class, 'downloadPDFreportbillpersuperadmin'])->name('download-pdf-report-billpersuperadmin');
        Route::get('/download/excelbillpersuperadmin', [SuperAdminController::class, 'exportbillpersuperadmin'])->name('download.excelbillpersuperadmin');
        Route::post('/download/filtered/excelbillpersuperadmin', [SuperAdminController::class, 'downloadFilteredExcelbillpersuperadmin'])->name('download.filtered.excelbillpersuperadmin');
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



        // Tool Existing
        Route::get('/tool-existing', [SuperAdminController::class, 'indextoolexisting'])->name('toolsexisting.index');
        Route::post('/vlookup/checkFile1/existing', [SuperAdminController::class, 'checkFile1existing'])->name('vlookup.checkFile1existing');
        Route::post('/vlookup-existing', [SuperAdminController::class, 'vlookupexisting'])->name('vlookup.performexisting');
        Route::get('gettabeltempexistings', [SuperAdminController::class, 'getDataTempexistings'])->name('gettabeltempexistings');
        Route::post('/saveexistings', [SuperAdminController::class, 'savetempexistings'])->name('saveexistings');
        Route::post('deleteAllTempexistings', [SuperAdminController::class, 'deleteAllTempexistings'])->name('deleteAllTempexistings');
        Route::delete('/destroy-tempexistings/{id}', [SuperAdminController::class, 'destroyTempexistings'])->name('destroy-tempexistings');

        // Data Existing
        Route::get('/data-existing', [SuperAdminController::class, 'indexexisting'])->name('existing.index');
        Route::get('gettabelexistings', [SuperAdminController::class, 'getDataexistings'])->name('gettabelexistings');
        Route::get('edit-existings/{id}', [SuperAdminController::class, 'editexistings'])->name('edit-existings');
        Route::post('update-existings/{id}', [SuperAdminController::class, 'updateexistings'])->name('update-existings');
        Route::get('/superadmin/existing/{id}/view', [SuperAdminController::class, 'viewPDFreportexistingsuperadmin'])->name('view-pdf-report-existingsuperadmin');
        Route::get('/superadmin/existing/{id}/download', [SuperAdminController::class, 'downloadPDFreportexistingsuperadmin'])->name('download-pdf-report-existingsuperadmin');
        Route::get('/download/excelexistingsuperadmin', [SuperAdminController::class, 'exportexistingsuperadmin'])->name('download.excelexistingsuperadmin');
        Route::post('/download/filtered/excelexistingsuperadmin', [SuperAdminController::class, 'downloadFilteredExcelexistingsuperadmin'])->name('download.filtered.excelexistingsuperadmin');
        Route::get('viewexisting/pdf/{id}', [SuperAdminController::class, 'viewgeneratePDFexisting'])->name('viewexisting.pdf');
        Route::get('existing/pdf/{id}', [SuperAdminController::class, 'generatePDFexisting'])->name('existing.pdf');
        Route::post('/cek-filepembayaranexisting', [SuperAdminController::class, 'checkFilePembayaranexisting'])->name('cek.filepembayaranexisting');
        Route::post('/cek-pembayaranexisting', [SuperAdminController::class, 'cekPembayaranexisting'])->name('cek-pembayaranexisting');
        Route::delete('/destroy-existings/{id}', [SuperAdminController::class, 'destroyexistings'])->name('destroy-existings');
        Route::get('/data-existing-riwayat', [SuperAdminController::class, 'indexexistingriwayat'])->name('existingriwayat.index');
        Route::get('gettabelexistingsriwayat', [SuperAdminController::class, 'getDataexistingsriwayat'])->name('gettabelexistingsriwayat');

        // Report Data Existing
        Route::get('/report-dataexisting', [SuperAdminController::class, 'indexreportexisting'])->name('reportdataexisting.index');
        Route::get('/grafik-dataexisting', [SuperAdminController::class, 'indexgrafikexisting'])->name('grafikdataexisting.index');

        // Report Sales Existing
        Route::get('/report-salesexisting', [SuperAdminController::class, 'indexreportsalesexisting'])->name('reportsalesexisting.index');
        Route::get('/get-data-reportexistingsuperadmin', [SuperAdminController::class, 'getDatareportexistingsuperadmin'])->name('getDatareportexistingsuperadmin');
        Route::get('/download/excelreportexistingsuperadmin', [SuperAdminController::class, 'downloadAllExcelreportexistingsuperadmin'])->name('download.excelreportexistingsuperadmin');
        Route::post('/download/filtered/excelreportexistingsuperadmin', [SuperAdminController::class, 'downloadFilteredExcelreportexistingsuperadmin'])->name('download.filtered.excelreportexistingsuperadmin');



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
        Route::get('/data-pranpc-riwayat', [SuperAdminController::class, 'indexpranpcriwayat'])->name('pranpcriwayat.index');
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

        // Data Billper Admin billper
        Route::get('/data-billper-adminbillper', [AdminBillperController::class, 'indexbillperadminbillper'])->name('billper-adminbillper.index');
        Route::get('/report-billper-adminbillper', [AdminBillperController::class, 'indexreportbillperadminbillper'])->name('report-billper-adminbillper.index');
        Route::get('gettabelbillpersadminbillper', [AdminBillperController::class, 'getDatabillpersadminbillper'])->name('gettabelbillpersadminbillper');
        Route::get('/download/excelbillperadminbillper', [AdminBillperController::class, 'exportbillper'])->name('download.excelbillperadminbillper');
        Route::post('/download/filtered/excelbillperadminbillper', [AdminBillperController::class, 'downloadFilteredExcelbillper'])->name('download.filtered.excelbillperadminbillper');
        Route::get('edit-billpersadminbillper/{id}', [AdminBillperController::class, 'editbillpersadminbillper'])->name('edit-billpersadminbillper');
        Route::get('viewbillperadminbillper/pdf/{id}', [AdminBillperController::class, 'viewgeneratePDFbillperadminbillper'])->name('viewbillperadminbillper.pdf');
        Route::get('billperadminbillper/pdf/{id}', [AdminBillperController::class, 'generatePDFbillperadminbillper'])->name('billperadminbillper.pdf');
        Route::post('update-billpersadminbillper/{id}', [AdminBillperController::class, 'updatebillpersadminbillper'])->name('update-billpersadminbillper');
        Route::get('/adminbillper/billper/{id}/view', [AdminBillperController::class, 'viewPDFreportbillper'])->name('view-pdf-report-billper');
        Route::get('/adminbillper/billper/{id}/download', [AdminBillperController::class, 'downloadPDFreportbillper'])->name('download-pdf-report-billper');
        Route::get('/get-data-reportbillper', [AdminBillperController::class, 'getDatareportbillper'])->name('getDatareportbillper');
        Route::get('/download/excelreportbillper', [AdminBillperController::class, 'downloadAllExcelreportbillper'])->name('download.excelreportbillper');
        Route::post('/download/filtered/excelreportbillper', [AdminBillperController::class, 'downloadFilteredExcelreportbillper'])->name('download.filtered.excelreportbillper');
        Route::post('/savePlottingbillper', [AdminBillperController::class, 'savePlottingbillper'])->name('savePlottingbillper');

        // Data Existing Admin billper
        Route::get('/data-existing-adminbillper', [AdminBillperController::class, 'indexexistingadminbillper'])->name('existing-adminbillper.index');
        Route::get('/report-existing-adminbillper', [AdminBillperController::class, 'indexreportexistingadminbillper'])->name('report-existing-adminbillper.index');
        Route::get('gettabelexistingsadminbillper', [AdminBillperController::class, 'getDataexistingsadminbillper'])->name('gettabelexistingsadminbillper');
        Route::get('/download/excelexistingadminbillper', [AdminBillperController::class, 'exportexisting'])->name('download.excelexistingadminbillper');
        Route::post('/download/filtered/excelexitingadminbillper', [AdminBillperController::class, 'downloadFilteredExcelexisting'])->name('download.filtered.excelexistingadminbillper');
        Route::get('edit-existingsadminbillper/{id}', [AdminBillperController::class, 'editexistingsadminbillper'])->name('edit-existingsadminbillper');
        Route::get('viewexistingadminbillper/pdf/{id}', [AdminBillperController::class, 'viewgeneratePDFexistingadminbillper'])->name('viewbillperadminbillper.pdf');
        Route::get('existingadminbillper/pdf/{id}', [AdminBillperController::class, 'generatePDFexistingadminbillper'])->name('existingadminbillper.pdf');
        Route::post('update-existingsadminbillper/{id}', [AdminBillperController::class, 'updateexistingsadminbillper'])->name('update-existingsadminbillper');
        Route::get('/adminbillper/existing/{id}/view', [AdminBillperController::class, 'viewPDFreportexisting'])->name('view-pdf-report-existing');
        Route::get('/adminbillper/existing/{id}/download', [AdminBillperController::class, 'downloadPDFreportexisting'])->name('download-pdf-report-existing');
        Route::get('/get-data-reportexisting', [AdminBillperController::class, 'getDatareportexisting'])->name('getDatareportexisting');
        Route::get('/download/excelreportexisting', [AdminBillperController::class, 'downloadAllExcelreportexisting'])->name('download.excelreportexisting');
        Route::post('/download/filtered/excelreportexisting', [AdminBillperController::class, 'downloadFilteredExcelreportexisting'])->name('download.filtered.excelreportexisting');
        Route::post('/savePlottingexisting', [AdminBillperController::class, 'savePlottingexisting'])->name('savePlottingexisting');
    });




    // Route Admin PraNPC
    Route::middleware(['AD-P'])->group(function () {
        Route::get('/admin-pranpc', [AdminPranpcController::class, 'index'])->name('adminpranpc.index');

        // Data Pranpc admin
        Route::get('/get-data-reportpranpc', [AdminPranpcController::class, 'getDatareportpranpc'])->name('getDatareportpranpc');
        Route::get('/report-pranpc-adminpranpc', [AdminPranpcController::class, 'indexreportpranpcadminpranpc'])->name('report-pranpc-adminpranpc.index');
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




        // // Data Existing Admin
        Route::get('/data-existing-adminpranpc', [AdminPranpcController::class, 'indexexistingadminpranpc'])->name('existing-adminpranpc.index');
        Route::get('/report-existing-adminpranpc', [AdminPranpcController::class, 'indexreportexistingadminpranpc'])->name('report-existing-adminpranpc.index');
        Route::get('gettabelexistingsadminpranpc', [AdminPranpcController::class, 'getDataexistingsadminpranpc'])->name('gettabelexistingsadminpranpc');
        Route::get('/download/excelexistingadminpranpc', [AdminPranpcController::class, 'exportexisting'])->name('download.excelexistingadminpranpc');
        Route::post('/download/filtered/excelexitingadminpranpc', [AdminPranpcController::class, 'downloadFilteredExcelexisting'])->name('download.filtered.excelexistingadminpranpc');
        Route::get('edit-existingsadminpranpc/{id}', [AdminPranpcController::class, 'editexistingsadminpranpc'])->name('edit-existingsadminpranpc');
        Route::get('viewexistingadminpranpc/pdf/{id}', [AdminPranpcController::class, 'viewgeneratePDFexistingadminpranpc'])->name('viewbillperadminpranpc.pdf');
        Route::get('existingadminpranpc/pdf/{id}', [AdminPranpcController::class, 'generatePDFexistingadminpranpc'])->name('existingadminpranpc.pdf');
        Route::post('update-existingsadminpranpc/{id}', [AdminPranpcController::class, 'updateexistingsadminpranpc'])->name('update-existingsadminpranpc');
        Route::get('/adminpranpc/existing/{id}/view', [AdminPranpcController::class, 'viewPDFreportexistingadminpranpc'])->name('view-pdf-report-existingadminpranpc');
        Route::get('/adminpranpc/existingadminpranpc/{id}/download', [AdminPranpcController::class, 'downloadPDFreportexistingadminpranpc'])->name('download-pdf-report-existingadminpranpc');
        Route::get('/get-data-reportexistingadminpranpc', [AdminPranpcController::class, 'getDatareportexistingadminpranpc'])->name('getDatareportexistingadminpranpc');
        Route::get('/download/excelreportexistingadminpranpc', [AdminPranpcController::class, 'downloadAllExcelreportexistingadminpranpc'])->name('download.excelreportexistingadminpranpc');
        Route::post('/download/filtered/excelreportexistingadminpranpc', [AdminPranpcController::class, 'downloadFilteredExcelreportexistingadminpranpc'])->name('download.filtered.excelreportexistingadminpranpc');
        Route::post('/savePlottingexistingadminpranpc', [AdminPranpcController::class, 'savePlottingexistingadminpranpc'])->name('savePlottingexistingadminpranpc');
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




        // Assignment Existing
        Route::get('/assignment-existing', [UserController::class, 'indexassignmentexisting'])->name('assignmentexisting.index');
        Route::get('gettabelassignmentexisting', [UserController::class, 'getDataassignmentexisting'])->name('gettabelassignmentexisting');
        Route::get('info-assignmentexisting/{id}', [UserController::class, 'infoassignmentexisting'])->name('info-assignmentexisting');
        Route::post('update-assignmentexisting/{id}', [UserController::class, 'updateassignmentexisting'])->name('update-assignmentexisting');

        // Report Assignment Existing
        Route::get('/report-assignment-existing', [UserController::class, 'indexreportassignmentexisting'])->name('reportassignmentexisting.index');
        Route::get('gettabelreportassignmentexisting', [UserController::class, 'getDatareportassignmentexisting'])->name('gettabelreportassignmentexisting');
        Route::get('info-reportassignmentexisting/{id}', [UserController::class, 'inforeportassignmentexisting'])->name('info-reportassignmentexisting');
        Route::post('update-reportassignmentexisting/{id}', [UserController::class, 'updatereportassignmentexisting'])->name('update-reportassignmentexisting');
        Route::post('/reset-reportassignmentexisting/{id}', [UserController::class, 'resetReportAssignmentexisting'])->name('reset-reportassignmentexisting');




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
