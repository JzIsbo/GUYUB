<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DataWargaController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContributionController;
use App\Http\Controllers\OfficerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\SystemController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\KoperasiController;
use App\Http\Controllers\BankSampahController;
use App\Http\Controllers\UmkmController;
use App\Http\Controllers\PosyanduController;
use App\Http\Controllers\RondaController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\RukemController;
use App\Http\Controllers\AspirasiController;
use App\Http\Controllers\PublicHomeController;

// ========================================================
// 1. RUTE PUBLIK (Tidak Butuh Login)
// ========================================================
Route::get('/', function () {
    return Auth::check() ? redirect('/dashboard') : redirect('/welcome');
});

Route::get('/welcome', [PublicHomeController::class, 'index'])->name('welcome');
Route::get('/api/public/data', [PublicHomeController::class, 'index']);

Route::get('/login', function () {
    return Auth::check() ? redirect('/dashboard') : view('auth.login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Login berhasil!',
                'redirect' => '/dashboard.html'
            ]);
        }
        return redirect()->intended('/dashboard');
    }

    if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Email atau password salah.'
        ], 422);
    }

    return back()->withErrors([
        'email' => 'Email atau password salah.',
    ])->onlyInput('email');
});

// Callback Payment Gateway (Bisa dipanggil dari luar tanpa auth jika callback Midtrans)
Route::post('/payment/callback', [PaymentController::class, 'paymentCallback'])->name('payment.callback');

// ========================================================
// 2. RUTE TERPROTEKSI (Wajib Login)
// ========================================================
Route::middleware(['auth'])->group(function () {

    // --- A. DATA WARGA ---
    Route::get('/data-warga', [AdminController::class, 'loadPage'])->defaults('page', 'data-warga')->name('admin.data-warga');
    Route::post('/warga/simpan', [DataWargaController::class, 'store'])->name('warga.store');
    Route::post('/admin/warga/store', [DataWargaController::class, 'store']);
    Route::post('/admin/warga/update', [DataWargaController::class, 'update'])->name('warga.update');
    Route::post('/admin/warga/delete', [DataWargaController::class, 'destroy'])->name('warga.delete');
    Route::delete('/admin/warga/delete/{id}', [DataWargaController::class, 'destroy']);
    Route::post('/admin/warga/delete/{id}', [DataWargaController::class, 'destroy']);
    Route::get('/admin/warga/{id}/edit', [DataWargaController::class, 'update']);

    // --- B. TRANSAKSI & LAPORAN ---
    Route::post('/transaksi/simpan', [TransactionController::class, 'store'])->name('transaksi.store');
    Route::post('/admin/transaksi/update', [TransactionController::class, 'update'])->name('transaksi.update');
    Route::post('/admin/transaksi/delete', [TransactionController::class, 'destroy'])->name('transaksi.delete');
    
    Route::get('/admin/export-laporan/{tipe?}', [TransactionController::class, 'export'])->name('export.laporan');
    Route::get('/admin/export/laporan', [TransactionController::class, 'export'])->name('admin.export.laporan');
    Route::get('/admin/export-laporan', [TransactionController::class, 'export'])->name('admin.export');
    Route::get('/admin/export/{tipe?}', [TransactionController::class, 'export']);

    // --- C. KATEGORI MASTER ---
    Route::post('/kategori/simpan', [CategoryController::class, 'store'])->name('kategori.store');
    Route::post('/admin/kategori/update', [CategoryController::class, 'update'])->name('kategori.update');
    Route::post('/admin/kategori/delete', [CategoryController::class, 'destroy'])->name('kategori.delete');
    Route::post('/admin/kategori/delete/{id}', [CategoryController::class, 'destroy']);

    // --- D. DATA IURAN ---
    Route::post('/iuran/simpan', [ContributionController::class, 'store'])->name('iuran.store');
    Route::post('/admin/iuran/update', [ContributionController::class, 'update'])->name('iuran.update');
    Route::post('/admin/iuran/delete', [ContributionController::class, 'destroy'])->name('iuran.delete');
    Route::post('/admin/laporan-iuran/simpan', [ContributionController::class, 'storePayment'])->name('laporan-iuran.store');

    // --- E. PENGURUS RT ---
    Route::post('/pengurus/simpan', [OfficerController::class, 'store'])->name('pengurus.store');
    Route::post('/admin/pengurus/store', [OfficerController::class, 'store']);
    Route::post('/admin/pengurus/update', [OfficerController::class, 'update'])->name('pengurus.update');
    Route::post('/admin/pengurus/delete/{id}', [OfficerController::class, 'destroy'])->name('pengurus.delete');

    // --- F. PENGGUNA SISTEM ---
    Route::post('/pengguna/simpan', [UserController::class, 'store'])->name('user.store');
    Route::post('/admin/pengguna/update', [UserController::class, 'update'])->name('pengguna.update');
    Route::post('/admin/pengguna/delete', [UserController::class, 'destroy'])->name('pengguna.delete');

    // --- G. PERANGKAT SISTEM ---
    Route::post('/admin/perangkat/store', [DeviceController::class, 'store']);
    Route::post('/admin/perangkat/update', [DeviceController::class, 'update']);
    Route::post('/admin/perangkat/delete/{id}', [DeviceController::class, 'destroy']);

    // --- H. PENGATURAN & SISTEM ---
    Route::post('/rt/simpan', [SystemController::class, 'storeRt'])->name('rt.store');
    Route::post('/admin/rt/update', [SystemController::class, 'storeRt'])->name('rt.update');
    Route::post('/admin/pengaturan/simpan', [SystemController::class, 'storeSettings'])->name('admin.settings.store');
    Route::post('/settings/update', [SystemController::class, 'updateSettings'])->name('settings.update');
    Route::get('/pengaturan', [AdminController::class, 'loadPage'])->defaults('page', 'pengaturan')->name('settings.index');
    Route::get('/aktivitas/data', [SystemController::class, 'getAktivitasData'])->name('aktivitas.data');

    // --- I. BACKUP & RESTORE ---
    Route::get('/admin/backup', [BackupController::class, 'backup'])->name('admin.backup');
    Route::post('/admin/restore', [BackupController::class, 'restore'])->name('admin.restore');

    // --- J. PEMBAYARAN ONLINE & TAGIHAN ---
    Route::get('/pembayaran/tagihan', [AdminController::class, 'loadPage'])->defaults('page', 'tagihan-warga')->name('tagihan.warga');
    Route::post('/tagihan/store', [PaymentController::class, 'storeTagihan']);
    Route::post('/tagihan/update', [PaymentController::class, 'updateTagihan']);
    Route::post('/tagihan/delete', [PaymentController::class, 'destroyTagihan']);
    Route::post('/pembayaran/gateway/store', [PaymentController::class, 'storeGateway']);
    Route::post('/payment/sync', [PaymentController::class, 'syncPembayaran']);
    Route::post('/payment/logs/clear', [PaymentController::class, 'clearGatewayLogs']);
    Route::post('/qris-va/update', [PaymentController::class, 'updateQrisVa']);

    // --- K. SURAT ONLINE & PENGUMUMAN ---
    Route::post('/surat-online/store', [SuratController::class, 'store']);
    Route::post('/surat-online/update-status', [SuratController::class, 'updateStatus']);
    Route::post('/pengumuman/store', [AnnouncementController::class, 'store']);
    Route::post('/pengumuman/delete', [AnnouncementController::class, 'destroy']);

    // --- NEW MODULES: LAYANAN WARGA ---
    Route::post('/koperasi/store', [KoperasiController::class, 'store']);
    Route::post('/koperasi/delete', [KoperasiController::class, 'destroy']);
    Route::post('/bank-sampah/store', [BankSampahController::class, 'store']);
    Route::post('/bank-sampah/delete', [BankSampahController::class, 'destroy']);
    Route::post('/umkm/store', [UmkmController::class, 'store']);
    Route::post('/umkm/delete', [UmkmController::class, 'destroy']);
    Route::post('/posyandu/store', [PosyanduController::class, 'store']);
    Route::post('/posyandu/delete', [PosyanduController::class, 'destroy']);
    Route::post('/ronda/store', [RondaController::class, 'storeRonda']);
    Route::post('/ronda/delete', [RondaController::class, 'destroyRonda']);
    Route::post('/incident/store', [RondaController::class, 'storeIncident']);
    Route::post('/incident/delete', [RondaController::class, 'destroyIncident']);
    Route::post('/kegiatan/store', [KegiatanController::class, 'store']);
    Route::post('/kegiatan/delete', [KegiatanController::class, 'destroy']);
    Route::post('/rukem/store', [RukemController::class, 'store']);
    Route::post('/rukem/delete', [RukemController::class, 'destroy']);
    Route::post('/aspirasi/store', [AspirasiController::class, 'store']);
    Route::post('/aspirasi/respond', [AspirasiController::class, 'respond']);
    Route::post('/aspirasi/delete', [AspirasiController::class, 'destroy']);

    // --- L. LOGOUT ---
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        if (request()->expectsJson() || request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil logout!',
                'redirect' => '/login.html'
            ]);
        }
        return redirect('/login');
    })->name('logout');

    // --- M. RUTE DINAMIS SPA (Wajib diletakkan paling bawah dalam grup ini) ---
    Route::get('/api/auth/user', function() {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user()
        ]);
    });
    Route::get('/api/page-data/{page?}', [AdminController::class, 'loadPage']);
    Route::get('/{page?}', [AdminController::class, 'loadPage'])->name('admin.page');
});
