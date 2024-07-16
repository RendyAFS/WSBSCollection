<?php

use App\Http\Controllers\AdminBillperController;
use App\Http\Controllers\AdminController;
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


        // Tool
        Route::get('/tool', [SuperAdminController::class, 'indextool'])->name('tools.index');
        Route::post('/vlookup/checkFile1', [SuperAdminController::class, 'checkFile1'])->name('vlookup.checkFile1');
        Route::post('/vlookup', [SuperAdminController::class, 'vlookup'])->name('vlookup.perform');
        Route::get('gettabeltempalls', [SuperAdminController::class, 'getDataTempalls'])->name('gettabeltempalls');
        Route::post('/savealls', [SuperAdminController::class, 'savetempalls'])->name('savealls');
        Route::post('deleteAllTempalls', [SuperAdminController::class, 'deleteAllTempalls'])->name('deleteAllTempalls');
        Route::delete('/destroy-tempalls/{id}', [SuperAdminController::class, 'destroyTempalls'])->name('destroy-tempalls');


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



        // Data All
        Route::get('/data-all', [SuperAdminController::class, 'indexall'])->name('all.index');
        Route::get('gettabelalls', [SuperAdminController::class, 'getDataalls'])->name('gettabelalls');
        Route::get('edit-alls/{id}', [SuperAdminController::class, 'editalls'])->name('edit-alls');
        Route::post('update-alls/{id}', [SuperAdminController::class, 'updatealls'])->name('update-alls');
        Route::get('/download/excel', [SuperAdminController::class, 'export'])->name('download.excel');
        Route::post('/download/filtered/excel', [SuperAdminController::class, 'downloadFilteredExcel'])->name('download.filtered.excel');
        Route::post('/cek-filepembayaran', [SuperAdminController::class, 'checkFilePembayaran'])->name('cek.filepembayaran');
        Route::post('/cek-pembayaran', [SuperAdminController::class, 'cekPembayaran'])->name('cek-pembayaran');
        Route::delete('/destroy-alls/{id}', [SuperAdminController::class, 'destroyalls'])->name('destroy-alls');


        // Report Data
        Route::get('/report-data', [SuperAdminController::class, 'indexreport'])->name('reportdata.index');

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
        Route::get('/download/excel', [SuperAdminController::class, 'export'])->name('download.excel');
        Route::post('/download/filtered/excel', [SuperAdminController::class, 'downloadFilteredExcel'])->name('download.filtered.excel');
        Route::post('/cek-filepembayaran', [SuperAdminController::class, 'checkFilePembayaran'])->name('cek.filepembayaran');
        Route::post('/cek-pembayaran', [SuperAdminController::class, 'cekPembayaran'])->name('cek-pembayaran');
        Route::delete('/destroy-pranpcs/{id}', [SuperAdminController::class, 'destroypranpcs'])->name('destroy-pranpcs');


        // Data Akun
        Route::get('/data-akun', [SuperAdminController::class, 'indexdataakun'])->name('data-akun.index');
        Route::post('/update-status', [SuperAdminController::class, 'updatestatus'])->name('updatestatus');
        Route::delete('/akun/{id}', [SuperAdminController::class, 'destroyakun'])->name('destroy-akun');
    });

    // Route Admin Bilper
    Route::middleware(['AD-B'])->group(function () {
        Route::get('/admin-billper', [AdminBillperController::class, 'index'])->name('adminbillper.index');

        // Data All Admin
        Route::get('/data-all-adminbillper', [AdminBillperController::class, 'indexalladminbillper'])->name('all-adminbillper.index');
        Route::get('gettabelallsadminbillper', [AdminBillperController::class, 'getDataallsadminbillper'])->name('gettabelallsadminbillper');
        Route::get('/download/excel-billper', [AdminBillperController::class, 'exportbillper'])->name('download.excelbillper');
        Route::post('/download/filtered/excel-billper', [AdminBillperController::class, 'downloadFilteredExcelbillper'])->name('download.filtered.excelbillper');
    });

    // Route Admin PraNPC
    Route::middleware(['AD-P'])->group(function () {
        Route::get('/admin-pranpc', [AdminPranpcController::class, 'index'])->name('adminpranpc.index');

        // Data All Admin
        Route::get('/data-all-adminpranpc', [AdminPranpcController::class, 'indexalladminpranpc'])->name('all-adminpranpc.index');
        Route::get('gettabelallsadminpranpc', [AdminPranpcController::class, 'getDataallsadminpranpc'])->name('gettabelallsadminpranpc');
        Route::get('/download/excel-pranpc', [AdminPranpcController::class, 'exportpranpc'])->name('download.excelpranpc');
        Route::post('/download/filtered/excel-pranpc', [AdminPranpcController::class, 'downloadFilteredExcelpranpc'])->name('download.filtered.excelpranpc');
    });


    // Route User
    Route::middleware(['US'])->group(function () {
        Route::get('/user', [UserController::class, 'index'])->name('user.index');
    });
});
