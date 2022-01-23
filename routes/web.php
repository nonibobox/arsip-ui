<?php

use App\Http\Controllers\ArsipController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InstansiController;
use App\Http\Controllers\SertifikatElektronikController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::group(['middleware'=>'auth'], function() use ($router){
    Route::get('/user', [UserController::class, 'index'])->name('user');
    Route::get('/instansi', [InstansiController::class, 'index'])->name('instansi');
    Route::get('/arsip', [ArsipController::class, 'index'])->name('arsip');
    Route::get('/sertifikat-elektronik', [SertifikatElektronikController::class, 'index'])->name('sertifikat-elektronik');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

require __DIR__.'/auth.php';
