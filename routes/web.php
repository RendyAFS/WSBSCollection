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
        Route::get('gettabeltempalls', [SuperAdminController::class, 'getDataTempalls'])->name('gettabeltempalls');
        Route::post('/savealls', [SuperAdminController::class, 'savetempalls'])->name('savealls');
        Route::post('deleteAllTempalls', [SuperAdminController::class, 'deleteAllTempalls'])->name('deleteAllTempalls');
        Route::delete('/destroy-tempalls/{id}', [SuperAdminController::class, 'destroyTempalls'])->name('destroy-tempalls');


        // Data All
        Route::get('/data-billper', [SuperAdminController::class, 'indexall'])->name('all.index');
        Route::get('gettabelalls', [SuperAdminController::class, 'getDataalls'])->name('gettabelalls');
        Route::get('edit-alls/{id}', [SuperAdminController::class, 'editalls'])->name('edit-alls');
        Route::post('update-alls/{id}', [SuperAdminController::class, 'updatealls'])->name('update-alls');
        Route::get('/download/excel', [SuperAdminController::class, 'export'])->name('download.excel');
        Route::post('/cek-filepembayaran', [SuperAdminController::class, 'checkFilePembayaran'])->name('cek.filepembayaran');
        Route::post('/cek-pembayaran', [SuperAdminController::class, 'cekPembayaran'])->name('cek-pembayaran');
        Route::post('/download/filtered/excel', [SuperAdminController::class, 'downloadFilteredExcel'])->name('download.filtered.excel');
        Route::delete('/destroy-alls/{id}', [SuperAdminController::class, 'destroyalls'])->name('destroy-alls');


        // Report Data
        Route::get('/report-data', [SuperAdminController::class, 'indexreport'])->name('reportdata.index');


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
