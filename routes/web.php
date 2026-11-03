<?php

use App\Http\Controllers\Api\CekMahasiswaController;
use App\Http\Controllers\Public\AgendaController;
use App\Http\Controllers\Public\BeritaController;
use App\Http\Controllers\Public\EventController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\LegacyRedirectController;
use App\Http\Controllers\Public\LowonganController;
use App\Http\Controllers\Public\PageController;
use App\Http\Controllers\Public\PengumumanController;
use App\Support\LegacyUrl;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Portal publik UPA Perkasa
|--------------------------------------------------------------------------
| Nama rute sama dengan yang dipakai di Blade frontend.
*/

Route::get('/', HomeController::class)->name('home');

// Halaman berbasis tabel `pages` (slug diatur di config/perkasa.php, isi diedit admin)
Route::controller(PageController::class)->group(function () {
    Route::get('/profil/sejarah', 'section')->name('profile.sejarah');
    Route::get('/profil/visi-misi', 'section')->name('profile.visi-misi');
    Route::get('/profil/tujuan-sasaran', 'section')->name('profile.tujuan-sasaran');
    Route::get('/profil/struktur', 'section')->name('profile.struktur');
    Route::get('/profil/program-kerja', 'section')->name('profile.program-kerja');
    Route::get('/profil/logo', 'section')->name('profile.logo');
    Route::get('/profil/panduan-tracer-study', 'section')->name('profile.panduan-tracer-study');
    Route::get('/profil/pimpinan', 'pimpinan')->name('profile.pimpinan');

    Route::get('/report/laporan-tahunan', 'section')->name('report.laporan-tahunan');
    Route::get('/report/tracer-study', 'section')->name('report.tracer-study');
    Route::get('/report/capaian-iku1', 'section')->name('report.capaian-iku1');

    Route::get('/ppid/informasi-wajib-berkala', 'section')->name('ppid.wajib-berkala');
    Route::get('/ppid/informasi-tersedia-setiap-saat', 'section')->name('ppid.tersedia-setiap-saat');
    Route::get('/ppid/informasi-yang-dikecualikan', 'section')->name('ppid.dikecualikan');

    Route::get('/halaman/{slug}', 'show')->name('pages.show');
});

Route::view('/ppid/tentang', 'ppid.tentang-ppid')->name('ppid.tentang');

Route::prefix('publikasi')->name('publikasi.')->group(function () {
    Route::get('/berita', [BeritaController::class, 'index'])->name('berita');
    Route::get('/berita/{slug}', [BeritaController::class, 'show'])->name('berita-detail');
    Route::get('/agenda-kegiatan', [AgendaController::class, 'index'])->name('agenda');
    Route::get('/agenda-kegiatan/{slug}', [AgendaController::class, 'show'])->name('agenda-detail');
    Route::get('/event/{slug}', [EventController::class, 'show'])->name('event-detail');
    Route::get('/pengumuman', [PengumumanController::class, 'index'])->name('pengumuman');
    Route::get('/pengumuman/{slug}', [PengumumanController::class, 'show'])->name('pengumuman-detail');
    Route::get('/lowongan-pekerjaan', [LowonganController::class, 'index'])->name('lowongan');
    Route::get('/lowongan-pekerjaan/{slug}', [LowonganController::class, 'show'])->name('lowongan-detail');
});

// Tracer study publik: sementara diarahkan ke aplikasi lama sampai modul tracer dipindah
Route::get('/tracer-study', fn () => redirect()->away(config('perkasa.legacy_tracer_url')))->name('tracer.index');

// API lama yang mungkin dipakai sistem lain (respons JSON dipertahankan)
Route::get('/api-perkasa/{nim}/cek-mahasiswa', CekMahasiswaController::class)
    ->middleware('throttle:60,1')
    ->name('api.cek-mahasiswa');

// URL aplikasi lama (/perkasa2/..., /perkasa/pages/...) diarahkan 301 ke rute baru
Route::get('/perkasa2/{path?}', LegacyRedirectController::class)->where('path', '.*');
Route::get('/perkasa/pages/{slug}/read', fn (string $slug) => redirect(LegacyUrl::pageUrl($slug), 301));
Route::get('/start-session', fn () => redirect()->route('login', [], 301));
