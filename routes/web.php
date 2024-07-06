<?php

use App\Http\Controllers\AdminContoller;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SuperAdminContoller;
use App\Http\Controllers\UserContoller;
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


Route::prefix('login')->middleware(['auth'])->group(function () {
    //Route Super Admin
    Route::middleware(['SA'])->group(function () {
        Route::get('/super-admin', [SuperAdminContoller::class, 'index'])->name('super-admin.index');
        Route::get('/tool', [SuperAdminContoller::class, 'indextool'])->name('tools.index');
        Route::get('/data-akun', [SuperAdminContoller::class, 'indexdataakun'])->name('data-akun.index');
        Route::post('/update-status', [SuperAdminContoller::class, 'updatestatus'])->name('updatestatus');
        Route::delete('/akun/{id}', [SuperAdminContoller::class, 'destroyakun'])->name('destroy-akun');
    });


    //Route Admin
    Route::middleware(['AD'])->group(function () {
        Route::get('/admin', [AdminContoller::class, 'index'])->name('admin.index');
    });


    //Route Admin
    Route::middleware(['US'])->group(function () {
        Route::get('/user', [UserContoller::class, 'index'])->name('user.index');
    });
});
