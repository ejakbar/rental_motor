<?php

use App\Http\Controllers\MotorController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\PenyewaanController;
use App\Http\Controllers\TarifController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('pages.dashboard');
});

Route::get('/pengguna', [PenggunaController::class, 'index']);
Route::get('/pengguna/create', [PenggunaController::class, 'create']);
Route::get('/pengguna/{$id}', [PenggunaController::class, 'edit']);
Route::post('/pengguna', [PenggunaController::class, 'store']);
Route::put('/pengguna/{$id}', [PenggunaController::class, 'update']);
Route::delete('/pengguna/{$id}', [PenggunaController::class, 'destroy']);

Route::get('/motor', [MotorController::class, 'index']);
Route::get('/motor/create', [MotorController::class, 'create']);
Route::get('/motor/{$id}', [MotorController::class, 'edit']);
Route::post('/motor', [MotorController::class, 'store']);
Route::put('/motor/{$id}', [MotorController::class, 'update']);
Route::delete('/motor/{$id}', [MotorController::class, 'destroy']);

Route::get('/penyewaan', [PenyewaanController::class, 'index']);
Route::get('/penyewaan/create', [PenyewaanController::class, 'create']);
Route::get('/penyewaan/{$id}', [PenyewaanController::class, 'edit']);
Route::post('/penyewaan', [PenyewaanController::class, 'store']);
Route::put('/penyewaan/{$id}', [PenyewaanController::class, 'update']);
Route::delete('/penyewaan/{$id}', [PenyewaanController::class, 'destroy']);

Route::get('/pembayaran', [PembayaranController::class, 'index']);
Route::get('/pembayaran/create', [PembayaranController::class, 'create']);
Route::get('/pembayaran/{$id}', [PembayaranController::class, 'edit']);
Route::post('/pembayaran', [PembayaranController::class, 'store']);
Route::put('/pembayaran/{$id}', [PembayaranController::class, 'update']);
Route::delete('/pembayaran/{$id}', [PembayaranController::class, 'destroy']);

