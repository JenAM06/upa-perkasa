<?php

namespace App\View\Composers;

use App\Models\Menu;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

/**
 * Data navbar dan footer portal. Dropdown Profil, PPID, dan Report dibangun dari
 * tabel menus/submenus (diedit Admin Perkasa); Publikasi tetap. Di-cache 10 menit,
 * dibersihkan saat admin menyimpan menu atau submenu.
 */
class NavigationComposer
{
    public const CACHE_KEY = 'nav:dropdowns';

    public function compose(View $view): void
    {
        $dropdowns = Cache::remember(self::CACHE_KEY, now()->addMinutes(10), fn () => $this->build());

        $view->with([
            'profilDropdown' => $dropdowns['profil'],
            'publikasiDropdown' => $dropdowns['publikasi'],
            'ppidDropdown' => $dropdowns['ppid'],
            'reportDropdown' => $dropdowns['report'],
            'footerLinks' => array_merge(config('perkasa.footer_links'), [
                ['label' => 'Tracer Study', 'url' => route('tracer.index')],
            ]),
            'footerSocial' => config('perkasa.social'),
            'contact' => config('perkasa.contact'),
        ]);
    }

    private function build(): array
    {
        $ids = config('perkasa.menus');
        $menus = Menu::with('submenus')->whereIn('id', array_values($ids))->get()->keyBy('id');

        $items = fn (string $key) => $menus->get($ids[$key])?->submenus
            ->map(fn ($s) => ['label' => $s->title, 'url' => $s->href])
            ->values()->all() ?? [];

        $profil = $items('profil');
        if ($menus->has($ids['pimpinan'])) {
            $profil[] = ['label' => 'Profil Pimpinan', 'url' => route('profile.pimpinan')];
        }

        return [
            'profil' => $profil,
            'publikasi' => [
                ['label' => 'Lowongan Kerja', 'url' => route('publikasi.lowongan')],
                ['label' => 'Berita', 'url' => route('publikasi.berita')],
                ['label' => 'Agenda Kegiatan', 'url' => route('publikasi.agenda')],
                ['label' => 'Pengumuman', 'url' => route('publikasi.pengumuman')],
            ],
            'ppid' => array_merge([['label' => 'Tentang PPID', 'url' => route('ppid.tentang')]], $items('ppid')),
            'report' => $items('report'),
        ];
    }
}
