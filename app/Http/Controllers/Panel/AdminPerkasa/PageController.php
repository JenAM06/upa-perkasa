<?php

namespace App\Http\Controllers\Panel\AdminPerkasa;

use App\Http\Controllers\Panel\CrudController;
use App\Models\Page;
use App\Support\LegacyUrl;

class PageController extends CrudController
{
    protected function definition(): array
    {
        return [
            'model' => Page::class,
            'title' => 'Halaman',
            'description' => 'Isi halaman Profil, PPID, dan Report. Slug halaman yang tampil di menu diatur di config/perkasa.php.',
            'route' => 'panel.admin.pages',
            'search' => ['title', 'slug'],
            'author' => true,
            'columns' => [
                'Judul' => fn (Page $p) => view('panel.crud.title', ['title' => $p->title, 'url' => LegacyUrl::pageUrl($p->slug)]),
                'Slug' => fn (Page $p) => $p->slug,
                'Diperbarui' => fn (Page $p) => $p->updated_at?->format('d/m/Y'),
            ],
            'fields' => [
                'title' => ['label' => 'Judul', 'type' => 'text', 'rules' => ['required', 'string', 'max:255']],
                'text' => ['label' => 'Isi', 'type' => 'editor', 'rules' => ['required', 'string']],
            ],
        ];
    }
}
