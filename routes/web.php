<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Dashboards\DashboardController;
use App\Http\Controllers\Aset\AsetController;
use App\Http\Controllers\Aset\AsetLancarController;
use App\Http\Controllers\Aset\MutasiAsetController;
use App\Http\Controllers\UserController;

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Protected Routes
Route::middleware(['auth', 'verified'])->group(function () {

    // =============================
    // DASHBOARD
    // =============================
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // =============================
    // ASET TETAP
    // =============================
    Route::prefix('asets')->name('asets.')->group(function () {

        Route::get('/', [AsetController::class, 'index'])->name('index');
        Route::get('/create', [AsetController::class, 'create'])->name('create');
        Route::post('/', [AsetController::class, 'store'])->name('store');

        // 🔽 ROUTE AJAX (WAJIB DI ATAS)
        Route::post('/get-register-info', [AsetController::class, 'getRegisterInfo'])
            ->name('get-register-info');

        Route::post('/generate-register-preview', [AsetController::class, 'generateRegisterPreview'])
            ->name('generate-register-preview');

        // 🔽 EXPORT & DOWNLOAD
        Route::get('/export', [AsetController::class, 'export'])->name('export');

        Route::get('/{id}/download-pdf', [AsetController::class, 'downloadPdf'])
            ->name('downloadPdf');

        // ✅ ROUTE BARU: DOWNLOAD LABEL ASET (IMAGE)
        Route::get('/{id}/download-label', [AsetController::class, 'downloadLabel'])
            ->name('downloadLabel');
        // ✅ NEW: BATCH LABEL DOWNLOAD
        Route::post('/labels/batch', [AsetController::class, 'downloadBatchLabel'])
            ->name('downloadBatchLabel');

        // ⚠️ ROUTE DINAMIS PALING BAWAH (WAJIB TERAKHIR)
        Route::get('/{aset}', [AsetController::class, 'show'])->name('show');
        Route::get('/{aset}/edit', [AsetController::class, 'edit'])->name('edit');
        Route::put('/{aset}', [AsetController::class, 'update'])->name('update');
        Route::delete('/{aset}', [AsetController::class, 'destroy'])->name('destroy');
    });



    // API Routes for Aset Tetap
    Route::prefix('api/asets')->name('api.asets.')->group(function () {
        Route::get('/kelompoks/{akunId}', [AsetController::class, 'getKelompoks'])->name('kelompoks');
        Route::get('/jenis/{kelompokId}', [AsetController::class, 'getJenis'])->name('jenis');
        Route::get('/objeks/{jenisId}', [AsetController::class, 'getObjeks'])->name('objeks');
        Route::get('/rincian-objeks/{objekId}', [AsetController::class, 'getRincianObjeks'])->name('rincian-objeks');
        Route::get('/sub-rincian-objeks/{rincianObjekId}', [AsetController::class, 'getSubRincianObjeks'])->name('sub-rincian-objeks');
        Route::get('/sub-sub-rincian-objeks/{subRincianObjekId}', [AsetController::class, 'getSubSubRincianObjeks'])->name('sub-sub-rincian-objeks');
        Route::post('/generate-kode-preview', [AsetController::class, 'generateKodeBarangPreview'])->name('generate-kode-preview');
    });

    // =============================
    // ASET LANCAR
    // =============================
    Route::prefix('aset-lancars')->name('aset-lancars.')->group(function () {

        // =============================
        // ROUTE STATIS (WAJIB DI ATAS)
        // =============================
        Route::get('/', [AsetLancarController::class, 'index'])->name('index');
        Route::get('/create', [AsetLancarController::class, 'create'])->name('create');
        Route::post('/', [AsetLancarController::class, 'store'])->name('store');
        Route::get('/export', [AsetLancarController::class, 'export'])->name('export');

        // =============================
        // ROUTE DINAMIS (PALING BAWAH)
        // =============================
        Route::get('/{asetLancar}', [AsetLancarController::class, 'show'])->name('show');
        Route::get('/{asetLancar}/edit', [AsetLancarController::class, 'edit'])->name('edit');
        Route::put('/{asetLancar}', [AsetLancarController::class, 'update'])->name('update');
        Route::delete('/{asetLancar}', [AsetLancarController::class, 'destroy'])->name('destroy');
    });

    // =============================
    // MUTASI ASET
    // =============================
    Route::prefix('mutasi')->name('mutasi.')->group(function () {

        // Riwayat HARUS DI ATAS
        Route::get('/riwayat', [MutasiAsetController::class, 'riwayat'])->name('riwayat');
        Route::get('/riwayat/aset/{aset}', [MutasiAsetController::class, 'riwayatAset'])->name('riwayat.aset');

        // Main CRUD
        Route::get('/', [MutasiAsetController::class, 'index'])->name('index');
        Route::get('/create', [MutasiAsetController::class, 'create'])->name('create');
        Route::post('/', [MutasiAsetController::class, 'store'])->name('store');

        // Route dinamis PALING BAWAH
        Route::get('/{mutasi}', [MutasiAsetController::class, 'show'])->name('show');

        Route::get('/{mutasi}/download', [MutasiAsetController::class, 'downloadBeritaAcara'])->name('download');
        Route::get('/{mutasi}/preview', [MutasiAsetController::class, 'previewBeritaAcara'])->name('preview');
    });


    // =============================
    // USER MANAGEMENT
    // =============================
    Route::resource('users', UserController::class);
    Route::put('/user/change-password/{username}', [UserController::class, 'updatePassword'])->name('users.updatePassword');

    // =============================
    // PROFILE
    // =============================
    Route::middleware('auth')->group(function () {

        // Profile Routes
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

        // Password Routes
        Route::get('/profile/password', [ProfileController::class, 'editPassword'])->name('password.edit');
        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    });
});

// Auth routes
require __DIR__ . '/auth.php';
