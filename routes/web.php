<?php

use App\Http\Controllers\AdminController;
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
        Route::post('/vlookup', [SuperAdminController::class, 'vlookup'])->name('vlookup.perform');
        Route::post('/vlookup/checkFile1', [SuperAdminController::class, 'checkFile1'])->name('vlookup.checkFile1');
        Route::post('/vlookup/checkFile2', [SuperAdminController::class, 'checkFile2'])->name('vlookup.checkFile2');
        Route::get('gettabeltempbillpers', [SuperAdminController::class, 'getDataTempBillpers'])->name('gettabeltempbillpers');
        Route::post('/savebillpers', [SuperAdminController::class, 'savetempbillpers'])->name('savebillpers');
        Route::post('deleteAllTempBillpers', [SuperAdminController::class, 'deleteAllTempBillpers'])->name('deleteAllTempBillpers');
        Route::delete('/destroy-tempbillpers/{id}', [SuperAdminController::class, 'destroyTempBillpers'])->name('destroy-tempbillpers');


        // Data Bilper
        Route::get('/data-billper', [SuperAdminController::class, 'indexbillper'])->name('billper.index');
        Route::get('gettabelbillpers', [SuperAdminController::class, 'getDataBillpers'])->name('gettabelbillpers');
        Route::get('edit-billpers/{id}', [SuperAdminController::class, 'editBillpers'])->name('edit-billpers');
        Route::post('update-billpers/{id}', [SuperAdminController::class, 'updateBillpers'])->name('update-billpers');
        Route::get('/download/excel', [SuperAdminController::class, 'export'])->name('download.excel');
        Route::post('/cek-filepembayaran', [SuperAdminController::class, 'checkFilePembayaran'])->name('cek.filepembayaran');
        Route::post('/cek-pembayaran', [SuperAdminController::class, 'cekPembayaran'])->name('cek-pembayaran');
        Route::post('/download/filtered/excel', [SuperAdminController::class, 'downloadFilteredExcel'])->name('download.filtered.excel');
        Route::delete('/destroy-billpers/{id}', [SuperAdminController::class, 'destroyBillpers'])->name('destroy-billpers');


        // Report Billper
        Route::get('/report-billper', [SuperAdminController::class, 'indexreport'])->name('reportbillper.index');


        // riwayat Billper
        Route::get('/riwayat-billper', [SuperAdminController::class, 'indexriwayat'])->name('riwayatbillper.index');


        // Data Akun
        Route::get('/data-akun', [SuperAdminController::class, 'indexdataakun'])->name('data-akun.index');
        Route::post('/update-status', [SuperAdminController::class, 'updatestatus'])->name('updatestatus');
        Route::delete('/akun/{id}', [SuperAdminController::class, 'destroyakun'])->name('destroy-akun');

    });

    // Route Admin
    Route::middleware(['AD'])->group(function () {
        Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    });

    // Route User
    Route::middleware(['US'])->group(function () {
        Route::get('/user', [UserController::class, 'index'])->name('user.index');
    });
});
