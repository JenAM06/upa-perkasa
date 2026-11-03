<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Semua halaman yang isinya dari tabel `pages` (Profil, Report, PPID, halaman lain).
 * Slug per rute diatur di config/perkasa.php.
 */
class PageController extends Controller
{
    public function section(Request $request): View
    {
        $routeName = $request->route()->getName();
        $config = config('perkasa.pages')[$routeName] ?? null;
        abort_unless($config, 404);

        $slugs = (array) $config['slug'];
        $pages = Page::whereIn('slug', $slugs)->get()
            ->sortBy(fn ($p) => array_search($p->slug, $slugs))->values();

        abort_if($pages->isEmpty(), 404);

        return view('pages.show', [
            'pages' => $pages,
            'title' => $pages->count() === 1 ? $pages->first()->title : $this->titleFromRoute($routeName),
            'group' => $config['group'],
        ]);
    }

    public function pimpinan(): View
    {
        $menu = Menu::with('submenus')->find(config('perkasa.menus.pimpinan'));
        $slugs = $menu?->submenus->pluck('page_slug')->filter()->values()->all() ?? [];

        $pages = Page::whereIn('slug', $slugs)->get()
            ->sortBy(fn ($p) => array_search($p->slug, $slugs))->values();

        return view('pages.show', [
            'pages' => $pages,
            'title' => $menu?->title ? ucwords(strtolower($menu->title)) : 'Profil Pimpinan',
            'group' => 'Profil',
            'stacked' => true,
        ]);
    }

    public function show(string $slug): View
    {
        $page = Page::whereSlug($slug)->firstOrFail();

        return view('pages.show', ['pages' => collect([$page]), 'title' => $page->title, 'group' => null]);
    }

    private function titleFromRoute(string $routeName): string
    {
        return ucwords(str_replace('-', ' ', last(explode('.', $routeName))));
    }
}
