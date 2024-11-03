<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\MutasiController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Spatie\FlareClient\Api;

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


Route::get('/products', [ProductController::class, 'index'])->name('products.index'); Route::get('/products/create', [ProductController::class, 'create'])->name('products.create'); Route::post('/products', [ProductController::class, 'store'])->name('products.store'); Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit'); Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update'); Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy'); Route::get('/users', [UserController::class, 'index'])->name('users.index'); Route::get('/users/create', [UserController::class, 'create'])->name('users.create'); Route::post('/users/store', [UserController::class, 'store'])->name('users.store'); Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit'); Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update'); Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy'); Route::get('/barang', [BarangController::class, 'index'])->name('barang.index'); Route::get('/barang/create', [BarangController::class, 'create'])->name('barang.create'); Route::post('/barang', [BarangController::class, 'store'])->name('barang.store'); Route::get('/barang/{barang}/edit', [BarangController::class, 'edit'])->name('barang.edit'); Route::put('/barang/{barang}', [BarangController::class, 'update'])->name('barang.update'); Route::delete('/barang/{barang}', [BarangController::class, 'destroy'])->name('barang.destroy'); Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard'); Route::get('/dashboard/export', [DashboardController::class, 'export'])->name('dashboard.export'); Route::get('mutasi/report', [MutasiController::class, 'report'])->name('mutasi.report'); Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index'); Route::get('/laporan/generate', [LaporanController::class, 'generate'])->name('laporan.generate'); Route::get('/mutasi/filter', [MutasiController::class, 'filter'])->name('mutasi.filter');
Route::get('/mutasi/barang/{id}/history', [MutasiController::class, 'historyBarang'])->name('mutasi.historyBarang');
Route::get('/mutasi/user/{id}/history', [MutasiController::class, 'historyUser'])->name('mutasi.historyUser'); Route::get('/mutasi', [MutasiController::class, 'index'])->name('mutasi.index'); Route::get('/mutasi/create', [MutasiController::class, 'create'])->name('mutasi.create'); Route::post('/mutasi/store', [MutasiController::class, 'store'])->name('mutasi.store'); Route::get('/mutasi/print', [MutasiController::class, 'print'])->name('mutasi.print'); Route::get('/mutasi/export', [MutasiController::class, 'export'])->name('mutasi.export'); Route::get('/login', [LoginController::class, 'index'])->name('login'); Route::post('/login', [LoginController::class, 'authenticate'])->name('login.authenticate'); Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::middleware(['auth'])->group(function () { Route::post('/logout', [LoginController::class, 'logout'])->name('logout'); });
