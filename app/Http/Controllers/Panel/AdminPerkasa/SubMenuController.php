<?php

namespace App\Http\Controllers\Panel\AdminPerkasa;

use App\Http\Controllers\Panel\CrudController;
use App\Models\Menu;
use App\Models\SubMenu;
use App\View\Composers\NavigationComposer;
use Illuminate\Support\Str;

class SubMenuController extends CrudController
{
    protected function definition(): array
    {
        return [
            'model' => SubMenu::class,
            'title' => 'Sub Menu',
            'description' => 'Isi URL dengan alamat halaman, misalnya /halaman/program-kerja-2027.',
            'route' => 'panel.admin.submenus',
            'search' => ['title'],
            'order' => ['menu_id', 'asc'],
            'with' => ['menu:id,title'],
            'flush' => [NavigationComposer::CACHE_KEY],
            'columns' => [
                'Menu' => fn (SubMenu $s) => $s->menu?->title ?? '(tanpa menu)',
                'Judul' => fn (SubMenu $s) => $s->title,
                'Tujuan' => fn (SubMenu $s) => view('panel.crud.title', ['title' => Str::limit($s->href, 60), 'url' => $s->href]),
            ],
            'fields' => [
                'title' => ['label' => 'Judul', 'type' => 'text', 'rules' => ['required', 'string', 'max:255']],
                'menu_id' => ['label' => 'Menu', 'type' => 'select', 'options' => fn () => Menu::orderBy('id')->pluck('title', 'id')->all(), 'rules' => ['nullable', 'integer', 'exists:menus,id']],
                'url' => ['label' => 'URL', 'type' => 'text', 'rules' => ['required', 'string', 'max:2000']],
            ],
        ];
    }
}
