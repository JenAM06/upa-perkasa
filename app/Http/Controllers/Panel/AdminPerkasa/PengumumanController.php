<?php

namespace App\Http\Controllers\Panel\AdminPerkasa;

use App\Http\Controllers\Panel\CrudController;
use App\Http\Controllers\Public\HomeController;
use App\Models\Pengumuman;

class PengumumanController extends CrudController
{
    protected function definition(): array
    {
        return [
            'model' => Pengumuman::class,
            'title' => 'Pengumuman',
            'route' => 'panel.admin.pengumuman',
            'search' => ['title'],
            'author' => true,
            'flush' => [HomeController::CACHE_KEY],
            'columns' => [
                'Judul' => fn (Pengumuman $p) => view('panel.crud.title', ['title' => $p->title, 'url' => route('publikasi.pengumuman-detail', $p->route_slug)]),
                'Penulis' => fn (Pengumuman $p) => $p->post_by,
                'Tanggal' => fn (Pengumuman $p) => $p->created_at?->format('d/m/Y'),
            ],
            'fields' => [
                'title' => ['label' => 'Judul', 'type' => 'text', 'rules' => ['required', 'string', 'max:255']],
                'text' => ['label' => 'Isi', 'type' => 'editor', 'rules' => ['required', 'string'], 'help' => 'Lampiran: unggah ke Google Drive lalu tempelkan tautannya di isi.'],
            ],
        ];
    }
}
