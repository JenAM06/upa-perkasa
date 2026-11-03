<?php

namespace App\Http\Controllers\Panel\AdminPerkasa;

use App\Http\Controllers\Panel\CrudController;
use App\Http\Controllers\Public\HomeController;
use App\Models\Agenda;

class AgendaController extends CrudController
{
    protected function definition(): array
    {
        return [
            'model' => Agenda::class,
            'title' => 'Agenda',
            'route' => 'panel.admin.agenda',
            'search' => ['title'],
            'order' => ['date', 'desc'],
            'author' => true,
            'flush' => [HomeController::CACHE_KEY],
            'columns' => [
                'Tanggal Kegiatan' => fn (Agenda $a) => $a->date?->format('d/m/Y'),
                'Judul' => fn (Agenda $a) => view('panel.crud.title', ['title' => $a->title, 'url' => route('publikasi.agenda-detail', $a->route_slug)]),
                'Penulis' => fn (Agenda $a) => $a->post_by,
            ],
            'fields' => [
                'title' => ['label' => 'Judul', 'type' => 'text', 'rules' => ['required', 'string', 'max:255']],
                'date' => ['label' => 'Tanggal Kegiatan', 'type' => 'date', 'rules' => ['required', 'date']],
                'text' => ['label' => 'Isi', 'type' => 'editor', 'rules' => ['required', 'string'], 'help' => 'Tuliskan waktu, lokasi, dan cara pendaftaran di sini.'],
            ],
        ];
    }
}
