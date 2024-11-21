<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\DataDiriController;
use App\Http\Controllers\DataRuanganController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\JadwalRuanganController;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\PemetaanMKController;
use illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
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

// Route::middleware('roles:admin,operator,user')->group(function () {
//     Route::resource('dashboard', DashboardController::class);
// });


Route::middleware('roles:admin')->group(function () {
    Route::resource('data_diri', DataDiriController::class);
});

Route::middleware('roles:admin,operator')->group(function () {
});

Route::middleware('roles:admin,operator')->group(function () {
    Route::resource('data_dosen', DosenController::class);
    Route::resource('data_mk', MataKuliahController::class);
    Route::resource('data_ruangan', DataRuanganController::class);
});

Route::middleware('roles:admin,operator')->group(function () {
});

Route::middleware('roles:admin,operator')->group(function () {
    Route::resource('pemetaan_mk', PemetaanMKController::class);
});

Route::middleware('roles:admin,operator,user')->group(function () {
    Route::resource('jadwal_ruangan', JadwalRuanganController::class);
});

Route::get('/filter-jadwal', [JadwalRuanganController::class, 'filterJadwal'])->name('filter.jadwal');



require __DIR__.'/auth.php';
