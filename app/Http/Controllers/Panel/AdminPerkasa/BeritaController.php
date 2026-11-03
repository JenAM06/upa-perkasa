<?php

namespace App\Http\Controllers\Panel\AdminPerkasa;

use App\Http\Controllers\Panel\CrudController;
use App\Http\Controllers\Public\HomeController;
use App\Models\Berita;

class BeritaController extends CrudController
{
    protected function definition(): array
    {
        return [
            'model' => Berita::class,
            'title' => 'Berita',
            'route' => 'panel.admin.berita',
            'search' => ['judul'],
            'author' => true,
            'flush' => [HomeController::CACHE_KEY],
            'columns' => [
                'Gambar' => fn (Berita $b) => view('panel.crud.thumb', ['url' => $b->file_url]),
                'Judul' => fn (Berita $b) => view('panel.crud.title', ['title' => $b->judul, 'url' => route('publikasi.berita-detail', $b->route_slug)]),
                'Slider' => fn (Berita $b) => $b->slider === 'true' ? '✓' : '',
                'Penulis' => fn (Berita $b) => $b->post_by,
                'Tanggal' => fn (Berita $b) => $b->created_at?->format('d/m/Y H:i'),
            ],
            'fields' => [
                'judul' => ['label' => 'Judul', 'type' => 'text', 'rules' => ['required', 'string', 'max:255']],
                'image' => ['label' => 'Gambar', 'type' => 'image', 'folder' => 'berita', 'rules' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096']],
                'isi' => ['label' => 'Isi', 'type' => 'editor', 'rules' => ['required', 'string']],
                'slider' => ['label' => 'Tampilkan di slider beranda', 'type' => 'toggle'],
            ],
        ];
    }
}
