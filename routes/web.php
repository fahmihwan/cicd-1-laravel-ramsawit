<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\LabaController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\OpsController;
use App\Http\Controllers\PabrikController;
use App\Http\Controllers\PdfExportController;
use App\Http\Controllers\PekerjaanNonSawitController;
use App\Http\Controllers\PenggajianController;
// use App\Http\Controllers\PenggajianV2Controller;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\PeriodeController;
use App\Http\Controllers\PinjamanController;
use App\Http\Controllers\SlipGajiController;
use App\Http\Controllers\TarifController;
use App\Http\Controllers\TbsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->group(function () {
    Route::get('/', [AuthController::class, 'login'])->name('login');
    Route::post('/auth', [AuthController::class, 'authenticated'])->middleware('throttle:login');;
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::get('/periode', [PeriodeController::class, 'index']);
    Route::post('/periode', [PeriodeController::class, 'store']);
    Route::put('/periode/{id}', [PeriodeController::class, 'update']);
    Route::delete('/periode/{id}', [PeriodeController::class, 'destroy']);


    Route::get('/master/karyawan', [KaryawanController::class, 'index']);
    Route::post('/master/karyawan', [KaryawanController::class, 'store']);
    Route::put('/master/karyawan/{id}', [KaryawanController::class, 'update']);
    Route::delete('/master/karyawan/{id}', [KaryawanController::class, 'destroy']);

    Route::get('/master/pabrik', [PabrikController::class, 'index']);
    Route::post('/master/pabrik', [PabrikController::class, 'store']);
    Route::put('/master/pabrik/{id}', [PabrikController::class, 'update']);
    Route::delete('/master/pabrik/{id}', [PabrikController::class, 'destroy']);

    Route::get('/master/pekerjaan-lain', [PekerjaanNonSawitController::class, 'index_m_pekerjaan']);
    Route::post('/master/pekerjaan-lain', [PekerjaanNonSawitController::class, 'store_m_pekerjaan']);
    Route::put('/master/pekerjaan-lain/{id}', [PekerjaanNonSawitController::class, 'update_m_pekerjaan']);
    Route::delete('/master/pekerjaan-lain/{id}', [PekerjaanNonSawitController::class, 'destroy_m_pekerjaan']);

    Route::get('/master/tarif', [TarifController::class, 'index']);
    Route::post('/master/tarif', [TarifController::class, 'store']);
    Route::put('/master/tarif/{id}', [TarifController::class, 'update']);
    Route::delete('/master/tarif/{id}', [TarifController::class, 'destroy']);


    // Route::get('/master/ops', [OpsController::class, 'index']);
    // Route::post('/master/ops', [OpsController::class, 'store']);
    // Route::put('/master/ops/{id}', [OpsController::class, 'update']);
    // Route::delete('/master/ops/{id}', [OpsController::class, 'destroy']);



    Route::get('/pembelian/tbs/{menu}/periode', [TbsController::class, 'wrap_periode']);
    Route::get('/pembelian/tbs/{menu}/{periode}/view', [TbsController::class, 'index']);
    Route::post('/pembelian/tbs/{menu}/{periode}/view', [TbsController::class, 'store']);
    Route::put('/pembelian/tbs/{menu}/{periode}/view/{id}', [TbsController::class, 'update']);
    Route::delete('/pembelian/tbs/{menu}/delete/{id}', [TbsController::class, 'destroy']);


    Route::get('/penjualan/tbs/{menu}/periode', [PenjualanController::class, 'wrap_periode']);
    Route::get('/penjualan/tbs/{menu}/{periode}/view', [PenjualanController::class, 'index']);
    Route::post('/penjualan/tbs/{menu}/{periode}/view', [PenjualanController::class, 'store']);
    Route::put('/penjualan/tbs/{menu}/{periode}/view/{id}', [PenjualanController::class, 'update']);
    Route::delete('/penjualan/tbs/{menu}/delete/{id}', [PenjualanController::class, 'destroy']);


    Route::get('/pekerjaan-nonsawit/periode', [PekerjaanNonSawitController::class, 'wrap_periode']);
    Route::get('/pekerjaan-nonsawit/{periode}/view', [PekerjaanNonSawitController::class, 'index']);
    Route::post('/pekerjaan-nonsawit/{periode}/view', [PekerjaanNonSawitController::class, 'store']);
    Route::put('/pekerjaan-nonsawit/{periode}/view/{id}', [PekerjaanNonSawitController::class, 'update']);
    Route::delete('/pekerjaan-nonsawit/delete/{id}', [PekerjaanNonSawitController::class, 'destroy']);


    Route::get('/laba', [LabaController::class, 'index']);
    Route::get('/laba/{id}', [LabaController::class, 'detail']);

    Route::get('/slipgaji/karyawan', [SlipGajiController::class, 'index']);
    Route::get('/slipgaji/karyawan/{id}', [SlipGajiController::class, 'detail']);


    Route::get('/penggajian', [PenggajianController::class, 'index']);
    Route::get("penggajian/{id}", [PenggajianController::class, 'show_karyawan_penggajian_ajax']);
    Route::post('/penggajian', [PenggajianController::class, 'store']);
    Route::delete('/penggajian/{id}', [PenggajianController::class, 'destroy']);
    Route::put('/penggajian/{id}', [PenggajianController::class, 'update_delete_range_penggajian']);
    Route::get('/penggajian/{penggajianid}/{karyawanid}/detail-gaji', [PenggajianController::class, 'detail_gaji']);
    Route::get('/penggajian/{penggajianid}/{karyawanid}/ambil-gaji-perhari', [PenggajianController::class, 'ambil_gaji_perhari']);
    Route::put('/penggajian/{penggajianid}/{karyawanid}/ambil-gaji-perhari', [PenggajianController::class, 'update_ambil_gaji']);

    Route::get('/export-gaji-karyawan/{penggajianid}/{karyawanid}/detail-gaji', [PdfExportController::class, 'gaji_karyawan']);
    Route::get('/export-laba/{id}', [PdfExportController::class, 'detail_laba']);


    Route::get('/pinjaman', [PinjamanController::class, 'index']);
    Route::post('/pinjaman', [PinjamanController::class, 'store']);
    Route::put('/pinjaman/{id}', [PinjamanController::class, 'update']);
    Route::delete('/pinjaman/{id}', [PinjamanController::class, 'destroy']);

    Route::get('/user-profile', [UserController::class, 'profile']);

    Route::put('/user-profile/information', [UserController::class, 'update_information_user']);
    Route::put('/user-profile/changepassword', [UserController::class, 'change_password']);

    Route::get('/setting/users', [UserController::class, 'list_users']);
    Route::post('/setting/user', [UserController::class, 'store']);
    // Route::put('/setting/user/{id}', [UserController::class, 'update']);
    Route::delete('/setting/user/{user}', [UserController::class, 'destroy']);

    Route::get('/laporan/laporan-stock', [LaporanController::class, 'laporan_semua_stok']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
