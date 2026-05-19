<?php

use App\Http\Controllers\Anggota\BukuController as AnggotaBukuController;
use App\Http\Controllers\Anggota\DashboardController as AnggotaDashboardController;
use App\Http\Controllers\Anggota\PeminjamanController as AnggotaPeminjamanController;
use App\Http\Controllers\Anggota\ProfilController;
use App\Http\Controllers\Auth\AnggotaLoginController;
use App\Http\Controllers\Auth\PustakawanLoginController;
use App\Http\Controllers\Publik\BantuanController;
use App\Http\Controllers\Publik\BerandaController;
use App\Http\Controllers\Publik\BeritaController as PublikBeritaController;
use App\Http\Controllers\Publik\InformasiController;
use App\Http\Controllers\Publik\PustakawanPublikController;
use App\Http\Controllers\Pustakawan\AnggotaController;
use App\Http\Controllers\Pustakawan\BeritaController as PustakawanBeritaController;
use App\Http\Controllers\Pustakawan\BukuController as PustakawanBukuController;
use App\Http\Controllers\Pustakawan\DashboardController as PustakawanDashboardController;
use App\Http\Controllers\Pustakawan\InformasiPerpustakaanController;
use App\Http\Controllers\Pustakawan\KategoriController;
use App\Http\Controllers\Pustakawan\PeminjamanController as PustakawanPeminjamanController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;

/*
|--------------------------------------------------------------------------
| Rute Publik
|--------------------------------------------------------------------------
*/
Route::get('/', [BerandaController::class, 'index'])->name('beranda');
Route::get('/informasi', [InformasiController::class, 'index'])->name('informasi');
Route::get('/bantuan', [BantuanController::class, 'index'])->name('bantuan');
Route::get('/pustakawan', [PustakawanPublikController::class, 'index'])->name('pustakawan.profil');

Route::prefix('berita')->name('berita.')->group(function () {
    Route::get('/', [PublikBeritaController::class, 'index'])->name('index');
    Route::get('/{berita:slug}', [PublikBeritaController::class, 'show'])->name('show');
});

Route::get('/explore', [\App\Http\Controllers\Publik\ExploreController::class, 'index'])
    ->name('explore');


/*
|--------------------------------------------------------------------------
| Autentikasi Anggota
|--------------------------------------------------------------------------
*/

RateLimiter::for('login', function (Request $request) {
    return Limit::perMinute(5)->by($request->input('email') . '|' . $request->ip());
});


Route::middleware('guest')->group(function () {
    Route::get('/masuk', [AnggotaLoginController::class, 'tampilkan'])->name('anggota.login');
    Route::post('/masuk', [AnggotaLoginController::class, 'proses'])->name('anggota.login.proses');

    Route::get('/masuk-pustakawan', [PustakawanLoginController::class, 'tampilkan'])->name('pustakawan.login');
    Route::post('/masuk-pustakawan', [PustakawanLoginController::class, 'proses'])->name('pustakawan.login.proses');
});

Route::post('/keluar', function () {
    $userId = auth()->id();
    app(\App\Services\LogAktivitasService::class)->catatLogout($userId);
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| Area Anggota
|--------------------------------------------------------------------------
*/
Route::prefix('area-anggota')->name('anggota.')->middleware(['auth', 'anggota'])->group(function () {
    Route::get('/dasbor', [AnggotaDashboardController::class, 'index'])->name('dasbor');

    Route::prefix('buku')->name('buku.')->group(function () {
        Route::get('/', [AnggotaBukuController::class, 'index'])->name('index');
        Route::get('/{buku}', [AnggotaBukuController::class, 'show'])->name('show');
    });

    Route::prefix('peminjaman')->name('peminjaman.')->group(function () {
        Route::get('/', [AnggotaPeminjamanController::class, 'index'])->name('index');
        Route::post('/ajukan/{buku}', [AnggotaPeminjamanController::class, 'ajukan'])->name('ajukan');
    });

    Route::prefix('profil')->name('profil.')->group(function () {
        Route::get('/edit', [ProfilController::class, 'edit'])->name('edit');
        Route::patch('/perbarui', [ProfilController::class, 'perbarui'])->name('perbarui');
        Route::patch('/ganti-kata-sandi', [ProfilController::class, 'gantiKataSandi'])->name('ganti-kata-sandi');
    });
});

/*
|--------------------------------------------------------------------------
| Area Pustakawan
|--------------------------------------------------------------------------
*/
Route::prefix('area-pustakawan')->name('pustakawan.')->middleware(['auth', 'pustakawan'])->group(function () {
    Route::get('/dasbor', [PustakawanDashboardController::class, 'index'])->name('dasbor');

    // Manajemen Buku
    Route::resource('buku', PustakawanBukuController::class)->except(['show']);

    // Manajemen Kategori
    Route::resource('kategori', KategoriController::class)->except(['show']);

    // Manajemen Peminjaman
    Route::prefix('peminjaman')->name('peminjaman.')->group(function () {
        Route::get('/', [PustakawanPeminjamanController::class, 'index'])->name('index');
        Route::patch('/{peminjaman}/setujui', [PustakawanPeminjamanController::class, 'setujui'])->name('setujui');
        Route::patch('/{peminjaman}/tolak', [PustakawanPeminjamanController::class, 'tolak'])->name('tolak');
        Route::patch('/{peminjaman}/kembalikan', [PustakawanPeminjamanController::class, 'kembalikan'])->name('kembalikan');
    });

    // Manajemen Anggota
    Route::prefix('anggota')->name('anggota.')->group(function () {
        Route::get('/', [AnggotaController::class, 'index'])->name('index');
        Route::patch('/{user}/ubah-status', [AnggotaController::class, 'ubahStatus'])->name('ubah-status');
        Route::patch('/{user}/atur-ulang-kata-sandi', [AnggotaController::class, 'aturUlangKataSandi'])->name('atur-ulang-kata-sandi');
    });

    // Manajemen Berita
    Route::resource('berita', PustakawanBeritaController::class)->except(['show']);

    // Informasi Perpustakaan
    Route::get('/informasi', [InformasiPerpustakaanController::class, 'edit'])->name('informasi.edit');
    Route::patch('/informasi', [InformasiPerpustakaanController::class, 'perbarui'])->name('informasi.perbarui');
});