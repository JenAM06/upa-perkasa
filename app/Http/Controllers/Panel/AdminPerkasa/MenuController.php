<?php

namespace App\Http\Controllers\Panel\AdminPerkasa;

use App\Http\Controllers\Panel\CrudController;
use App\Models\Menu;
use App\View\Composers\NavigationComposer;

class MenuController extends CrudController
{
    protected function definition(): array
    {
        return [
            'model' => Menu::class,
            'title' => 'Menu',
            'description' => 'Grup menu navbar. Grup yang tampil diatur lewat id di config/perkasa.php (menus).',
            'route' => 'panel.admin.menus',
            'order' => ['id', 'asc'],
            'with' => ['submenus:id,menu_id,title'],
            'flush' => [NavigationComposer::CACHE_KEY],
            'columns' => [
                'ID' => fn (Menu $m) => $m->id,
                'Judul' => fn (Menu $m) => $m->title,
                'Sub Menu' => fn (Menu $m) => $m->submenus->pluck('title')->implode(', '),
            ],
            'fields' => [
                'title' => ['label' => 'Judul', 'type' => 'text', 'rules' => ['required', 'string', 'max:255']],
                'url' => ['label' => 'URL (opsional)', 'type' => 'url', 'rules' => ['nullable', 'string', 'max:2000']],
            ],
        ];
    }
}
