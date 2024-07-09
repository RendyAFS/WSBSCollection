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
        Route::get('/super-admin', [SuperAdminController::class, 'index'])->name('super-admin.index');
        Route::get('/tool', [SuperAdminController::class, 'indextool'])->name('tools.index');
        Route::get('/data-billper', [SuperAdminController::class, 'indexbillper'])->name('billper.index');
        Route::get('/download/excel', [SuperAdminController::class, 'export'])->name('download.excel');
        Route::post('/download/filtered/excel', [SuperAdminController::class, 'downloadFilteredExcel'])->name('download.filtered.excel');
        Route::get('/data-akun', [SuperAdminController::class, 'indexdataakun'])->name('data-akun.index');
        Route::post('/update-status', [SuperAdminController::class, 'updatestatus'])->name('updatestatus');
        Route::delete('/akun/{id}', [SuperAdminController::class, 'destroyakun'])->name('destroy-akun');
        Route::post('/vlookup', [SuperAdminController::class, 'vlookup'])->name('vlookup.perform');
        Route::post('/vlookup/checkFile1', [SuperAdminController::class, 'checkFile1'])->name('vlookup.checkFile1');
        Route::post('/vlookup/checkFile2', [SuperAdminController::class, 'checkFile2'])->name('vlookup.checkFile2');
        Route::post('/savebillpers', [SuperAdminController::class, 'savetempbillpers'])->name('savebillpers');
        Route::post('deleteAllTempBillpers', [SuperAdminController::class, 'deleteAllTempBillpers'])->name('deleteAllTempBillpers');
        Route::delete('/destroy-tempbillpers/{id}', [SuperAdminController::class, 'destroyTempBillpers'])->name('destroy-tempbillpers');
        Route::delete('/destroy-billpers/{id}', [SuperAdminController::class, 'destroyBillpers'])->name('destroy-billpers');
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
