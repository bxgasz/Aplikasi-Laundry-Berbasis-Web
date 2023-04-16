<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\DetailTransaksiController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', fn () => redirect()->route('login'));

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::group(['middleware' => 'cekRole:admin'], function() {
        // route outlet
        Route::get('/outlet/data', [OutletController::class, 'data'])->name('outlet.data');
        Route::resource('/outlet', OutletController::class);

        // route paket
        Route::get('/paket/data', [PaketController::class, 'data'])->name('paket.data');
        Route::resource('/paket', PaketController::class);
    });

    Route::group(['middleware' => ['cekRole:kasir,admin']], function() {
        // route pelanggan
        Route::get('/pelanggan/data', [PelangganController::class, 'data'])->name('pelanggan.data');
        Route::post('/pelanggan/cetak', [PelangganController::class, 'cetakMember'])->name('pelanggan.cetak');
        Route::resource('/pelanggan', PelangganController::class);
        
        // route penjualan
        Route::get('/penjualan/data', [TransaksiController::class, 'data'])->name('penjualan.data');
        Route::get('/penjualan', [TransaksiController::class, 'index'])->name('penjualan.index');
        Route::match(['put', 'patch'], '/penjualan/status/{id}', [TransaksiController::class, 'update'])->name('penjualan.update');
        Route::get('/penjualan/{id}', [TransaksiController::class, 'show'])->name('penjualan.show');
        Route::delete('/penjualan/{id}', [TransaksiController::class, 'destroy'])->name('penjualan.destroy');

        // route status bayar
        Route::get('/belombayar/data', [TransaksiController::class, 'datab'])->name('belum_bayar.data');
        Route::get('/belombayar', [TransaksiController::class, 'indexb'])->name('belum_bayar.index');
        Route::match(['put', 'patch'], '/belombayar/status/{id}', [TransaksiController::class, 'updateb'])->name('belum_bayar.update');
        Route::get('/belombayar/{id}', [DetailTransaksiController::class, 'bayar'])->name('belum_bayar.bayar');

        // route transaksi
        Route::get('/transaksi/baru', [TransaksiController::class, 'create'])->name('transaksi.baru');
        Route::post('/transaksi/simpan', [TransaksiController::class, 'store'])->name('transaksi.simpan');
        Route::get('/transaksi/selesai', [TransaksiController::class, 'selesai'])->name('transaksi.selesai');
        Route::get('/transaksi/nota_kecil', [TransaksiController::class, 'notaKecil'])->name('transaksi.pdf');

        Route::get('/transaksi/{id}/data', [DetailTransaksiController::class, 'data'])->name('transaksi.data');
        Route::get('/transaksi/loadform/{diskon}/{total}/{diterima}', [DetailTransaksiController::class, 'loadForm'])->name('transaksi.loadform');
        Route::resource('/transaksi', DetailTransaksiController::class)->except('show');
    });

    // route laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/data/{awal}/{akhir}', [LaporanController::class, 'data'])->name('laporan.data');
    Route::get('/laporan/pdf/{awal}/{akhir}', [LaporanController::class, 'exportPdf'])->name('laporan.pdf');

    // route laporan peroutlet sisi admin
    Route::get('/laporan/outlet', [LaporanController::class, 'indexOutlet'])->name('outlets.laporan.index');
    Route::get('/laporan/outlet-data', [LaporanController::class, 'dataOutlet'])->name('outlets.laporan.data');
    Route::get('/laporan/outlet/{id}', [LaporanController::class, 'laporanOutlet'])->name('outlet.laporan');
    // Route::get('/laporan/outlet/{id}', [LaporanController::class, 'dataLaporanOutlet'])->name('outlet.laporan.data');

    Route::group(['middleware' => 'cekRole:admin'], function () {
        // route user
        Route::get('/user/data', [UserController::class, 'data'])->name('user.data');
        Route::resource('/user', UserController::class);

        // route setting
        Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
        Route::get('/setting/first', [SettingController::class, 'show'])->name('setting.show');
        Route::post('/setting', [SettingController::class, 'update'])->name('setting.update');
    });

    // route profil
    Route::get('/profil', [UserController::class, 'profil'])->name('user.profil');
    Route::post('/profil', [UserController::class, 'updateProfil'])->name('user.update_pr');
});
