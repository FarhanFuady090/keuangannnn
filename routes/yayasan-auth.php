<?php

use App\Http\Controllers\yayasan\Auth\LoginController;
use App\Http\Controllers\yayasan\LapSiswaController;
use App\Http\Controllers\yayasan\LapKasController;
use App\Http\Controllers\yayasan\LapTabunganController;
use App\Http\Controllers\yayasan\LapDashboardController;
use App\Http\Controllers\yayasan\LapTagihanController;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

Route::prefix('yayasan')->middleware('guest:yayasan')->group(function () {
    Route::get('login', [LoginController::class, 'create'])->name('yayasan.login');
    Route::post('login', [LoginController::class, 'store']);
});

Route::get('/dashboard', [LapDashboardController::class, 'index'])->name('yayasan.dashboard')->middleware(['auth:yayasan', 'verified']);
Route::get('/dashboard', function () {
    return redirect('/yayasan/dashboard');
});

Route::prefix('yayasan')->middleware('auth:yayasan', 'verified')->group(function () {

    Route::get('/dashboard', [LapDashboardController::class, 'index'])->name('yayasan.dashboard');

    Route::get('/export-excel', [LapDashboardController::class, 'exportExcel'])->name('yayasan.dashboard.export');
    Route::get('/export-pdf', [LapDashboardController::class, 'exportPDF'])->name('yayasan.dashboard.export');

    Route::prefix('laporan')->middleware('auth')->group(function () {
        Route::prefix('siswa')->middleware('auth')->group(function () {
            Route::get('/', [LapSiswaController::class, 'IndexSiswa'])->name('yayasan.laporan.siswa.index');
            Route::get('/laporan/siswa/export', [LapSiswaController::class, 'exportFiltered'])->name('yayasan.laporan.siswa.export.filtered');
            Route::get('/laporan/siswa/export-pdf', [LapSiswaController::class, 'exportPDF'])->name('yayasan.laporan.siswa.export.pdf');
            Route::get('/detail-data-siswa/{id}', [LapSiswaController::class, 'ShowSiswa'])->name('yayasan.laporan.siswa.show');
        });

        Route::prefix('kas')->middleware('auth')->group(function () {
            Route::get('/', [LapKasController::class, 'index'])->name('yayasan.laporan.kas.index');
            Route::get('yayasan/laporan/kas/export', [LapKasController::class, 'exportExcel'])->name('yayasan.laporan.kas.export');
            Route::get('yayasan/laporan/kas/export-pdf', [LapKasController::class, 'exportPDF'])->name('yayasan.laporan.kas.export.pdf');
            Route::get('/trashed', [LapKasController::class, 'trashed'])->name('yayasan.laporan.kas.trashed');
        });

        Route::prefix('tabungan')->middleware('auth')->group(function () {
            Route::get('/', [LapTabunganController::class, 'index'])->name('yayasan.laporan.tabungan.index');
            Route::get('/{id}/export-pdf', [LapTabunganController::class, 'exportPdf'])->name('yayasan.laporan.tabungan.export.pdf');

            Route::get('/export-filtered', [LapTabunganController::class, 'exportFiltered'])->name('yayasan.laporan.tabungan.export.filtered');
            Route::get('/laporan/tabungan/export/pdf', [LapTabunganController::class, 'exportFilteredPdf'])->name('yayasan.laporan.tabungan.export.filteredpdf');

            Route::get('/{id}', [LapTabunganController::class, 'show'])->name('yayasan.laporan.tabungan.show');
        });

        Route::prefix('tagihan')->middleware('auth')->group(function () {
            Route::get('/', [LapTagihanController::class, 'index'])->name('yayasan.laporan.tagihan.index');
            // Route::get('/detail-data-siswa/{id}', [LapTagihanController::class, 'ShowSiswa'])->name('yayasan.laporan.siswa.show');
            Route::get('/api/jenispembayaran/nominal', [LapTagihanController::class, 'getNominalJenisPembayaran'])->name('yayasan.api.jenispembayaran.nominal');

            Route::get('/api/tagihan/jenispembayaran', [LapTagihanController::class, 'getJenisPembayaran'])->name('yayasan.api.jenispembayaran');
            Route::get('/api/tagihan/siswa', [LapTagihanController::class, 'getSiswa'])->name('yayasan.api.siswa');
            Route::get('/api/kelas', [LapTagihanController::class, 'getKelasByUnit'])->name('yayasan.api.kelas');
            Route::get('/get-kelas-by-jenis', [LapTagihanController::class, 'getKelasByJenis'])->name('get.kelas.by.jenis');
        });
    });

    Route::post('logout', [LoginController::class, 'destroy'])->name('yayasan.logout');
});
