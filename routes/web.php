<?php

use App\Http\Controllers\ApprovalModalController;
use App\Http\Controllers\CurrencyDetailController;
use App\Http\Controllers\JurnalBulananController;
use App\Http\Controllers\JurnalHarianController;
use App\Http\Controllers\JurnalKreditDebitController;
use App\Http\Controllers\LogEditController;
use App\Http\Controllers\MasterCurrencyController;
use App\Http\Controllers\MasterPegawaiController;
use App\Http\Controllers\ModalController;
use App\Http\Controllers\TransaksiController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Auth::routes();



Route::group(['middleware' => 'auth'], function () {
    Route::get('/', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::post('/change-password', [\App\Http\Controllers\DashboardController::class, 'change_password'])->name('change_password');

    Route::prefix('owner')->middleware(['Owner'])->group(function(){
        // MASTER DATA
        Route::resource('master-pegawai', MasterPegawaiController::class);
        Route::post('/delete-pegawai', [\App\Http\Controllers\MasterPegawaiController::class, 'hapus'])->name('master-pegawai-delete');
        Route::get('/master-currency', [\App\Http\Controllers\MasterCurrencyController::class, 'index'])->name('master-currency');
        Route::post('/tambah-currency', [\App\Http\Controllers\MasterCurrencyController::class, 'store'])->name('master-currency-store');
        Route::post('/delete-currency', [\App\Http\Controllers\MasterCurrencyController::class, 'hapus']);
        Route::post('/update-currency', [\App\Http\Controllers\MasterCurrencyController::class, 'updatedata']);
        Route::post('/update-nilai-kurs', [\App\Http\Controllers\MasterCurrencyController::class, 'updatekurs']);

        // JURNAL
        Route::resource('jurnal-harian', JurnalHarianController::class);
        Route::resource('jurnal-bulanan', JurnalBulananController::class);
        Route::get('/jurnal-bulanan/detail/{id}', [\App\Http\Controllers\JurnalBulananController::class, 'DetailTransaksi'])->name('bulanan-transaksi');
        
         // EXCEL DAN PDF
         Route::get('/download-dokumen', [\App\Http\Controllers\JurnalHarianController::class, 'Export_dokumen'])->name('export-dokumen');
    });
    Route::resource('jurnal-debit-kredit', JurnalKreditDebitController::class);
    Route::post('/delete-jurnal', [\App\Http\Controllers\JurnalKreditDebitController::class, 'hapus'])->name('jurnal-delete');


    // TRANSAKSI
    Route::resource('transaksi', TransaksiController::class);
    Route::get('transaksi/getkurs/{id_currency}', [\App\Http\Controllers\TransaksiController::class, 'getkurs']);
    Route::get('/edit/getkurs/{id_currency}', [\App\Http\Controllers\TransaksiController::class, 'getkursedit']);
    Route::post('/delete-transaksi', [\App\Http\Controllers\TransaksiController::class, 'hapus'])->name('transaksi-delete');

    // MODAL
    Route::resource('modal', ModalController::class);
    Route::post('/delete-modal', [\App\Http\Controllers\ModalController::class, 'hapus'])->name('modal-delete');
    Route::post('/transfer-modal', [\App\Http\Controllers\ModalController::class, 'transfer'])->name('modal-transfer');
    Route::post('/tambah-modal', [\App\Http\Controllers\ModalController::class, 'tambah']);
    
    // LOG EDIT
    Route::resource('log-edit', LogEditController::class);
    Route::get('log-edit/getdetail/{id}', [\App\Http\Controllers\LogEditController::class, 'getdetail']);
    Route::get('/filter-log', [\App\Http\Controllers\LogEditController::class, 'filterLog'])->name('filterLog');

    // APPROVAL
    Route::resource('approval-modal', ApprovalModalController::class)->middleware(['Owner']);

    // CETAK DOWNLOAD
    Route::get('/cetak/{id}', [\App\Http\Controllers\CetakController::class, 'cetak'])->name('cetak');
    Route::get('/exportexcel/{today}', [\App\Http\Controllers\CetakController::class, 'exportexcel'])->name('exportexcel');
    
    Route::get('/download-harian', [\App\Http\Controllers\TransaksiController::class, 'Export_dokumen'])->name('export-dokumen-harian');
   
});
