<?php

use App\Http\Controllers\Panel\AdminPerkasa;
use App\Http\Controllers\Panel\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Panel (semua peran)
|--------------------------------------------------------------------------
| Didaftarkan di bootstrap/app.php dengan prefix /panel, nama "panel.",
| middleware web + auth + not_suspended.
*/

Route::get('/', [DashboardController::class, 'home'])->name('home');
Route::get('/dashboard', [DashboardController::class, 'show'])->name('dashboard');

// Admin Perkasa (superadmin ikut diberi akses)
Route::middleware('role:admin_perkasa|superadmin')->prefix('admin-perkasa')->name('admin.')->group(function () {
    $crud = fn (string $uri, string $controller, string $name) => Route::resource($uri, $controller)
        ->except('show')->parameters([$uri => 'record'])->names($name);

    $crud('berita', AdminPerkasa\BeritaController::class, 'berita');
    $crud('agenda', AdminPerkasa\AgendaController::class, 'agenda');
    $crud('event', AdminPerkasa\EventController::class, 'event');
    $crud('pengumuman', AdminPerkasa\PengumumanController::class, 'pengumuman');
    $crud('lowongan', AdminPerkasa\LokerController::class, 'loker');
    $crud('pages', AdminPerkasa\PageController::class, 'pages');
    $crud('menus', AdminPerkasa\MenuController::class, 'menus');
    $crud('sub-menus', AdminPerkasa\SubMenuController::class, 'submenus');

    Route::get('company/search', [AdminPerkasa\CompanyController::class, 'search'])->name('company.search');
    Route::post('company/{company}/suspend', [AdminPerkasa\CompanyController::class, 'toggleSuspend'])->name('company.suspend');
    $crud('company', AdminPerkasa\CompanyController::class, 'company');

    Route::get('pelamar', [AdminPerkasa\JobApplyController::class, 'index'])->name('apply.index');
    Route::get('mahasiswa-non-unmul', [AdminPerkasa\MahasiswaNonUnmulController::class, 'index'])->name('non-unmul.index');

    Route::get('profil/{type}', [AdminPerkasa\ProfileTextController::class, 'edit'])->whereIn('type', ['sejarah', 'visi-misi'])->name('profile-text.edit');
    Route::put('profil/{type}', [AdminPerkasa\ProfileTextController::class, 'update'])->whereIn('type', ['sejarah', 'visi-misi'])->name('profile-text.update');

    Route::get('alumni-lama', [AdminPerkasa\AlumniLamaController::class, 'index'])->name('alumni-lama.index');
    Route::get('alumni-lama/export', [AdminPerkasa\AlumniLamaController::class, 'export'])->name('alumni-lama.export');
    Route::post('alumni-lama/{alumni}/verify', [AdminPerkasa\AlumniLamaController::class, 'verify'])->name('alumni-lama.verify');
    Route::delete('alumni-lama/{alumni}', [AdminPerkasa\AlumniLamaController::class, 'destroy'])->name('alumni-lama.destroy');
});

/*
| Menyusul (tahap berikutnya PRD): super-admin, company, mahasiswa, tracer-admin,
| admin-fakultas, admin-prodi, dan laporan bersama.
*/
