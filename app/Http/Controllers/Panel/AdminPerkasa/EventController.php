<?php

namespace App\Http\Controllers\Panel\AdminPerkasa;

use App\Http\Controllers\Panel\CrudController;
use App\Http\Controllers\Public\HomeController;
use App\Models\Event;

class EventController extends CrudController
{
    protected function definition(): array
    {
        return [
            'model' => Event::class,
            'title' => 'Event',
            'description' => 'Event bergambar, tampil di slider beranda jika ditandai.',
            'route' => 'panel.admin.event',
            'search' => ['title'],
            'author' => true,
            'flush' => [HomeController::CACHE_KEY],
            'columns' => [
                'Gambar' => fn (Event $e) => view('panel.crud.thumb', ['url' => $e->file_url]),
                'Judul' => fn (Event $e) => view('panel.crud.title', ['title' => $e->title, 'url' => route('publikasi.event-detail', $e->route_slug)]),
                'Slider' => fn (Event $e) => $e->slider === 'true' ? '✓' : '',
                'Tanggal' => fn (Event $e) => $e->created_at?->format('d/m/Y'),
            ],
            'fields' => [
                'title' => ['label' => 'Judul', 'type' => 'text', 'rules' => ['required', 'string', 'max:255']],
                'image' => ['label' => 'Gambar', 'type' => 'image', 'folder' => 'event', 'rules' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096']],
                'text' => ['label' => 'Isi', 'type' => 'editor', 'rules' => ['required', 'string']],
                'slider' => ['label' => 'Tampilkan di slider beranda', 'type' => 'toggle'],
            ],
        ];
    }
}
