<?php

namespace App\View\Composers;

use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/** Menu sidebar panel, disusun dari peran pengguna yang login. */
class PanelComposer
{
    public function compose(View $view): void
    {
        $user = Auth::user();
        $menu = [['label' => 'Dashboard', 'icon' => 'bi-speedometer2', 'route' => 'panel.dashboard']];

        if ($user?->hasRole([Role::ADMIN_PERKASA, Role::SUPERADMIN])) {
            $menu[] = ['heading' => 'Konten Portal'];
            array_push($menu,
                ['label' => 'Berita', 'icon' => 'bi-newspaper', 'route' => 'panel.admin.berita.index'],
                ['label' => 'Agenda', 'icon' => 'bi-calendar-event', 'route' => 'panel.admin.agenda.index'],
                ['label' => 'Event', 'icon' => 'bi-images', 'route' => 'panel.admin.event.index'],
                ['label' => 'Pengumuman', 'icon' => 'bi-megaphone', 'route' => 'panel.admin.pengumuman.index'],
                ['label' => 'Halaman (Pages)', 'icon' => 'bi-file-earmark-richtext', 'route' => 'panel.admin.pages.index'],
                ['label' => 'Menu', 'icon' => 'bi-list', 'route' => 'panel.admin.menus.index'],
                ['label' => 'Sub Menu', 'icon' => 'bi-list-nested', 'route' => 'panel.admin.submenus.index'],
                ['label' => 'Sejarah', 'icon' => 'bi-hourglass-split', 'route' => 'panel.admin.profile-text.edit', 'params' => ['type' => 'sejarah']],
                ['label' => 'Visi Misi', 'icon' => 'bi-bullseye', 'route' => 'panel.admin.profile-text.edit', 'params' => ['type' => 'visi-misi']],
            );
            $menu[] = ['heading' => 'Karir'];
            array_push($menu,
                ['label' => 'Lowongan', 'icon' => 'bi-briefcase', 'route' => 'panel.admin.loker.index'],
                ['label' => 'Perusahaan', 'icon' => 'bi-buildings', 'route' => 'panel.admin.company.index'],
                ['label' => 'Pelamar Kerja', 'icon' => 'bi-person-lines-fill', 'route' => 'panel.admin.apply.index'],
                ['label' => 'Mahasiswa Non Unmul', 'icon' => 'bi-people', 'route' => 'panel.admin.non-unmul.index'],
                ['label' => 'Data Alumni Lama', 'icon' => 'bi-person-check', 'route' => 'panel.admin.alumni-lama.index'],
            );
        }

        $view->with('panelMenu', $menu);
    }
}
