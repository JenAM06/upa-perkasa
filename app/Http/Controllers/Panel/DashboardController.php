<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use App\Models\AlumniLama;
use App\Models\Berita;
use App\Models\Loker;
use App\Models\Pengumuman;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /** /panel: arahkan ke panel sesuai peran. */
    public function home(Request $request): RedirectResponse
    {
        return redirect()->route('panel.dashboard');
    }

    public function show(Request $request): View
    {
        $user = $request->user();

        $stats = $user->hasRole([Role::ADMIN_PERKASA, Role::SUPERADMIN]) ? [
            ['label' => 'Berita', 'value' => Berita::count(), 'icon' => 'bi-newspaper', 'url' => route('panel.admin.berita.index')],
            ['label' => 'Agenda akan datang', 'value' => Agenda::whereDate('date', '>=', today())->count(), 'icon' => 'bi-calendar-event', 'url' => route('panel.admin.agenda.index')],
            ['label' => 'Pengumuman', 'value' => Pengumuman::count(), 'icon' => 'bi-megaphone', 'url' => route('panel.admin.pengumuman.index')],
            ['label' => 'Lowongan dibuka', 'value' => Loker::open()->count(), 'icon' => 'bi-briefcase', 'url' => route('panel.admin.loker.index')],
            ['label' => 'Alumni lama belum diverifikasi', 'value' => AlumniLama::where('is_verified', false)->count(), 'icon' => 'bi-person-check', 'url' => route('panel.admin.alumni-lama.index', ['verifikasi' => '0'])],
        ] : [];

        return view('panel.dashboard', [
            'stats' => $stats,
            'roles' => $user->roleNames(),
        ]);
    }
}
