<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\DataDiriController;
use App\Http\Controllers\DataRuanganController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\JadwalDosenController;
use App\Http\Controllers\JadwalRuanganController;
use App\Http\Controllers\LogRuangController;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\PemetaanMKController;
use illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ruanganTersediaController;
use App\Http\Controllers\tambahRuangController;
use App\Http\Middleware\RoleMiddleware;
use App\Models\JadwalRuangan;
use App\Models\MataKuliah;
use App\Models\Pemetaan;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('jadwal_ruangan.index', ['role' =>Auth::user()->role]);
})->middleware(['auth', 'roles:admin,user,operator'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('roles:admin')->group(function () {
    Route::resource('data_diri', DataDiriController::class);
});

Route::middleware('roles:admin,operator')->group(function () {
    Route::resource('data_dosen', DosenController::class);
    Route::resource('data_mk', MataKuliahController::class);
    Route::resource('data_ruangan', DataRuanganController::class);
    Route::resource('pemetaan_mk', PemetaanMKController::class);
    Route::resource('log_ruangan', LogRuangController::class);
    Route::resource('ruangan_tersedia', ruanganTersediaController::class);
    Route::resource('tambah_ruangan', tambahRuangController::class);
    });

//     Route::middleware('roles:admin,operator')->group(function () {
// // Rute untuk menampilkan halaman utama
// Route::get('/tambah_ruangan', [tambahRuangController::class, 'index'])->name('tambah_ruangan.index');
// // Rute untuk menampilkan jadwal berdasarkan hari tertentu
// Route::get('/tambah_ruangan/{day?}', [tambahRuangController::class, 'show'])->name('tambah_ruangan.show');
// // Rute untuk menyimpan data
// Route::post('/tambah_ruangan', [tambahRuangController::class, 'store'])->name('tambah_ruangan.store');
// // Rute untuk menampilkan halaman konfirmasi hapus

// Route::get('/tambah_ruangan/hapus/{datas}', [tambahRuangController::class, 'delete'])->name('tambah_ruangan.delete');
// // Rute untuk menghapus data
// Route::delete('/tambah_ruangan/destroy/{id}', [tambahRuangController::class, 'destroy'])->name('tambah_ruangan.destroy');

// // Rute untuk memperbarui data
// Route::put('/tambah_ruangan/update/{id}', [tambahRuangController::class, 'update'])->name('tambah_ruangan.update');

//     });


Route::middleware('roles:admin,operator,user')->group(function () {
    Route::resource('jadwal_ruangan', JadwalRuanganController::class);
});

Route::middleware('roles:user')->group(function () {
    Route::resource('jadwal_dosen', JadwalDosenController::class);
});

Route::get('/filter-jadwal', [JadwalRuanganController::class, 'filterJadwal'])->name('filter.jadwal');
Route::get('/jadwal-ruangan/cetak', [JadwalRuanganController::class, 'printJadwalPDF'])->name('jadwal_ruangan.cetak');
Route::post('/pemetaans/import-csv', [PemetaanMKController::class, 'importCSV'])->name('pemetaans.import-csv');

// Route untuk memilih ruangan
Route::get('/pilih_ruangan/{pemetaan_id}', [LogRuangController::class])->name('pilih_ruangan.index');

// Route::post('/tambah_ruangan', [tambahRuangController::class, 'store'])->name('tambah_ruangan.store');

// Route untuk menyimpan pemilihan ruangan
// Route::post('/log_ruangan', [LogRuangController::class, 'store'])->name('log_ruangan.store');
// Route::get('/ruangan_tersedia/{day?}', [RuanganTersediaController::class, 'index'])->name('ruangan_tersedia.index');

// Route::get('/tambah-ruangan', [tambahRuangController::class, 'index'])->name('tambah_ruangan.index');
// Route::post('/tambah_ruangan/{pemetaan_id}', [tambahRuangController::class, 'store'])->name('tambah_ruangan.store');





require __DIR__.'/auth.php';
