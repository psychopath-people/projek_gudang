<?php
// routes/api.php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BarangController;
use App\Http\Controllers\Api\MutasiController;
use App\Http\Controllers\Api\UserController;

// Public Routes
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    // Route::get('/users', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);


    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    Route::get('/barang', [BarangController::class, 'index']);
    Route::post('/barang', [BarangController::class, 'store']);
    Route::put('/barang/{id}', [BarangController::class, 'update']);
    Route::delete('/barang/{id}', [BarangController::class, 'destroy']);


    Route::get('/mutasi', [MutasiController::class, 'index']);
    Route::post('/mutasi', [MutasiController::class, 'store']);
    Route::get('/report', [MutasiController::class, 'report']);
    Route::get('/dashboard', [MutasiController::class, 'dashboard']);
    Route::get('/history/barang/{id}', [MutasiController::class, 'historyBarang']);
    Route::get('/history/user/{id}', [MutasiController::class, 'historyUser']);


});
