<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RfidController;
use App\Http\Controllers\PublicHomeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Rute-rute ini otomatis menggunakan prefix '/api' dan grup middleware 'api'
| yang bebas dari proteksi CSRF token serta session cookies (Stateless JSON).
|
*/

// =========================================================================
// 1. ESP32 IoT API — RFID / e-KTP Scanner & Heartbeat
// =========================================================================
Route::prefix('rfid')->middleware('rfid.device')->group(function () {
    // Menerima data tap kartu RFID / e-KTP dari alat ESP32
    Route::post('/tap', [RfidController::class, 'tap']);

    // Menerima sinyal denyut online ESP32 setiap 2-3 detik
    Route::post('/heartbeat', [RfidController::class, 'heartbeat']);

    // Alias rute tambahan untuk kompatibilitas sistem lama
    Route::post('/scan', [RfidController::class, 'tap']);
    Route::post('/gate-check', [RfidController::class, 'gateCheck']);
});

// =========================================================================
// 2. Public API Data
// =========================================================================
Route::get('/public/data', [PublicHomeController::class, 'index']);
