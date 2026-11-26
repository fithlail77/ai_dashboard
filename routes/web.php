<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Akses URL utama langsung menampilkan halaman login Tabler (atau redirect ke dashboard jika sudah login)
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::get('/login', [LoginController::class, 'showLoginForm']);

// Proses login dengan proteksi throttle rate-limiting (mendukung POST ke / maupun /login)
Route::post('/', [LoginController::class, 'login'])->middleware('throttle:10,1');
Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:10,1');

// Proses logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Halaman Admin Dashboard Tabler yang dilindungi middleware auth
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/curah-hujan', [DashboardController::class, 'curahHujan'])->name('dashboard.curah-hujan');
    Route::get('/dashboard/aws', [DashboardController::class, 'aws'])->name('dashboard.aws');
    Route::get('/dashboard/areal-statement', [DashboardController::class, 'arealStatement'])->name('dashboard.areal-statement');
    Route::get('/dashboard/produksi', [DashboardController::class, 'produksi'])->name('dashboard.produksi');
    Route::get('/dashboard/analisa-produksi-kebun', [DashboardController::class, 'analisaProduksiKebun'])->name('dashboard.analisa-produksi-kebun');
    Route::get('/dashboard/pemupukan-perawatan', [DashboardController::class, 'pemupukanPerawatan'])->name('dashboard.pemupukan-perawatan');
    Route::get('/dashboard/fleet-cartrack', [DashboardController::class, 'fleetCartrack'])->name('dashboard.fleet-cartrack');
    Route::get('/dashboard/operasional-unit', [DashboardController::class, 'operasionalUnit'])->name('dashboard.operasional-unit');
    Route::get('/dashboard/infrastruktur-jalan', [DashboardController::class, 'infrastrukturJalan'])->name('dashboard.infrastruktur-jalan');
    Route::get('/dashboard/sampel-daun-lsu', [DashboardController::class, 'sampelDaunLsu'])->name('dashboard.sampel-daun-lsu');
    Route::get('/dashboard/assessmen-blok', [DashboardController::class, 'assessmenBlok'])->name('dashboard.assessmen-blok');
    Route::get('/dashboard/pembibitan', [DashboardController::class, 'pembibitan'])->name('dashboard.pembibitan');
    Route::get('/dashboard/legal', [DashboardController::class, 'legal'])->name('dashboard.legal');

    // Profil Pengguna (Edit Profile & Password)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/lang/{locale}', function (string $locale) {
        if (in_array($locale, ['id', 'en', 'ko'], true)) {
            session(['locale' => $locale]);
        }

        return redirect()->back();
    })->name('lang.switch');

    // Manajemen Pengguna (CRUD)
    Route::prefix('settings')->name('admin.')->group(function () {
        Route::patch('users/{user}/inline', [UserController::class, 'inlineUpdate'])->name('users.inline');
        Route::resource('users', UserController::class)->except(['create', 'show', 'edit']);
    });
});
