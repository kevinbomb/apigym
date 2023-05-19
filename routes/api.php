<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstrukturController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\TransaksiAktivasiController;
use App\Http\Controllers\TransaksiUangController;
use App\Http\Controllers\TransaksiKelasController;
use App\Http\Controllers\JadwalHarianController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PerizinanController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\AuthPegawaiController;
use App\Http\Controllers\AuthMemberController;
use App\Http\Controllers\AuthInstrukturController;
use App\Http\Controllers\PresensiInstrukturController;
use App\Http\Controllers\PresensiKelasController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::post('/loginpegawai', [AuthPegawaiController::class, 'loginPegawai']);
Route::post('/loginmember', [AuthMemberController::class, 'loginMember']);
Route::post('/logininstruktur', [AuthInstrukturController::class, 'loginInstruktur']);

//Get
Route::get('/instruktur', [InstrukturController::class, 'index']);
Route::put('/instrukturPw', [InstrukturController::class, 'gantiPw']);
Route::put('/pegawaiPw', [PegawaiController::class, 'gantiPw']);
Route::get('/jadwal', [JadwalController::class, 'index']);
Route::get('/transaksiA', [TransaksiAktivasiController::class, 'index']);
Route::get('/transaksiU', [TransaksiUangController::class, 'index']);
Route::get('/transaksiK', [TransaksiKelasController::class, 'index']);
Route::get('/kelas', [KelasController::class, 'index']);
Route::get('/member', [MemberController::class, 'index']);
Route::get('/perizinan0', [PerizinanController::class, 'indexNol']);
Route::get('/perizinan1', [PerizinanController::class, 'indexSatu']);
Route::get('/perizinan/{id}', [PerizinanController::class, 'indexId']);
Route::delete('/perizinan/{id}', [PerizinanController::class, 'destroy']);
Route::post('/perizinan/{id}', [PerizinanController::class, 'store']);
Route::get('/jadwalHarian', [JadwalHarianController::class, 'index']);
Route::get('/jadwalHarian/{id}', [JadwalHarianController::class, 'show']);
Route::put('/jadwalHarian/{id}', [JadwalHarianController::class, 'libur']);
Route::post('/jadwalHarian', [JadwalHarianController::class, 'generateJadwalHarian']);
Route::put('/konfPerizinan/{id}', [PerizinanController::class, 'konfirmasi']);
Route::get('/presensiI', [PresensiInstrukturController::class, 'index']);
Route::put('/presensiI', [PresensiInstrukturController::class, 'resetTerlambat']);

Route::get('/presensiKelas/{id}', [PresensiKelasController::class, 'indexId']);
Route::post('/presensiKelas', [PresensiKelasController::class, 'store']);
Route::delete('/presensiKelas/{id}', [PresensiKelasController::class, 'destroy']);

Route::get('/cekStatus', [MemberController::class, 'cekStatusIndex']);
Route::put('/cekStatus', [MemberController::class, 'cekStatus']);
Route::get('/cekPaket', [MemberController::class, 'cekPaketTampil']);
Route::put('/cekPaket', [MemberController::class, 'cekPaketUpdate']);

// Protected routes (require authentication)
Route::group(["middleware"=>'auth:pegawaiPassport'], function () {
    Route::post('/logoutpegawai', [AuthPegawaiController::class, 'logoutPegawai']);

    Route::get('/instruktur/{id}', [InstrukturController::class, 'show']);
    Route::post('/instruktur', [InstrukturController::class, 'store']);
    Route::delete('/instruktur/{id}', [InstrukturController::class, 'destroy']);
    Route::put('/instruktur/{id}', [InstrukturController::class, 'update']);
    
    // Route::get('/cekJadwalHarian', [JadwalHarianController::class, 'cekGenerate']);
    
    Route::get('/jadwal/{id}', [JadwalController::class, 'show']);
    Route::post('/jadwal', [JadwalController::class, 'store']);
    Route::delete('/jadwal/{id}', [JadwalController::class, 'destroy']);
    Route::put('/jadwal/{id}', [JadwalController::class, 'update']);

    Route::get('/transaksiA/{id}', [TransaksiAktivasiController::class, 'show']);
    Route::post('/transaksiA/{id}', [TransaksiAktivasiController::class, 'store']);

    Route::get('/transaksiU/{id}', [TransaksiUangController::class, 'show']);
    Route::post('/transaksiU/{id}', [TransaksiUangController::class, 'store']);

    Route::get('/transaksiK/{id}', [TransaksiKelasController::class, 'show']);
    Route::post('/transaksiK/{id}', [TransaksiKelasController::class, 'store']);
    
    Route::get('/member/{id}', [MemberController::class, 'show']);
    Route::post('/member', [MemberController::class, 'store']);
    Route::delete('/member/{id}', [MemberController::class, 'destroy']);
    Route::put('/member/{id}', [MemberController::class, 'update']);
    Route::put('/memberRes/{id}', [MemberController::class, 'resetPw']);
    Route::put('/memberAkt/{id}', [MemberController::class, 'aktivasi']);

    Route::get('/pegawai', [PegawaiController::class, 'index']);

    Route::get('/promo', [PromoController::class, 'index']);
});
