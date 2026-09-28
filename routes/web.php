<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PenggajianController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\AbsensiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect Root ke Dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Rute Guest (Hanya untuk yang belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Rute Terproteksi Autentikasi
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Group untuk Admin
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
        Route::get('/penggajian', [PenggajianController::class, 'adminDashboard'])->name('admin.penggajian.index');
        Route::get('/penggajian/create', [PenggajianController::class, 'create'])->name('admin.penggajian.create');
        Route::post('/penggajian', [PenggajianController::class, 'store'])->name('admin.penggajian.store');
        Route::get('/penggajian/{id}/edit', [PenggajianController::class, 'edit'])->name('admin.penggajian.edit');
        Route::put('/penggajian/{id}', [PenggajianController::class, 'update'])->name('admin.penggajian.update');
        Route::delete('/penggajian/{id}', [PenggajianController::class, 'destroy'])->name('admin.penggajian.destroy');
        Route::get('/slip/{id}', [PenggajianController::class, 'showSlip'])->name('admin.slip');

        // CRUD Pegawai
        Route::resource('/pegawai', PegawaiController::class, [
            'names' => [
                'index'   => 'admin.pegawai.index',
                'create'  => 'admin.pegawai.create',
                'store'   => 'admin.pegawai.store',
                'edit'    => 'admin.pegawai.edit',
                'update'  => 'admin.pegawai.update',
                'destroy' => 'admin.pegawai.destroy',
            ],
        ]);

        // CRUD Absensi
        Route::get('/absensi/get-potongan', [AbsensiController::class, 'getPotongan'])->name('admin.absensi.get_potongan');
        Route::resource('/absensi', AbsensiController::class, [
            'names' => [
                'index'   => 'admin.absensi.index',
                'store'   => 'admin.absensi.store',
                'update'  => 'admin.absensi.update',
                'destroy' => 'admin.absensi.destroy',
            ],
        ]);
    });

    // Group untuk Pegawai
    Route::middleware('role:pegawai')->prefix('pegawai')->group(function () {
        Route::get('/dashboard', [PenggajianController::class, 'pegawaiDashboard'])->name('pegawai.dashboard');
        Route::get('/slip/{id}', [PenggajianController::class, 'showSlip'])->name('pegawai.slip');
        Route::get('/absensi', [PenggajianController::class, 'pegawaiAbsensi'])->name('pegawai.absensi');
    });
});
