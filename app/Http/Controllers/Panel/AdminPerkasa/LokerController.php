<?php

namespace App\Http\Controllers\Panel\AdminPerkasa;

use App\Http\Controllers\Panel\CrudController;
use App\Http\Controllers\Public\HomeController;
use App\Models\Loker;

class LokerController extends CrudController
{
    protected function definition(): array
    {
        return [
            'model' => Loker::class,
            'title' => 'Lowongan',
            'route' => 'panel.admin.loker',
            'search' => ['name_loker', 'lokasi'],
            'with' => ['company:id,company_name'],
            'author' => true,
            'flush' => [HomeController::CACHE_KEY],
            'columns' => [
                'Poster' => fn (Loker $l) => view('panel.crud.thumb', ['url' => $l->file_url]),
                'Posisi' => fn (Loker $l) => view('panel.crud.title', ['title' => $l->name_loker, 'url' => route('publikasi.lowongan-detail', $l->route_slug)]),
                'Perusahaan' => fn (Loker $l) => $l->company?->company_name,
                'Batas' => fn (Loker $l) => $l->tanggal_loker?->format('d/m/Y'),
                'Status' => fn (Loker $l) => str_replace('_', ' ', $l->status),
            ],
            'fields' => [
                'name_loker' => ['label' => 'Posisi', 'type' => 'text', 'rules' => ['required', 'string', 'max:255']],
                'company_id' => ['label' => 'Perusahaan', 'type' => 'remote-select', 'source' => 'panel.admin.company.search',
                    'display' => fn ($l) => $l->company?->company_name, 'rules' => ['required', 'integer', 'exists:company,id']],
                'tanggal_loker' => ['label' => 'Batas Lamaran', 'type' => 'date', 'rules' => ['required', 'date']],
                'lokasi' => ['label' => 'Lokasi', 'type' => 'text', 'rules' => ['required', 'string']],
                'gaji' => ['label' => 'Gaji', 'type' => 'text', 'rules' => ['required', 'string', 'max:255'], 'help' => 'Isi "Dirahasiakan" jika tidak dicantumkan.'],
                'fungsi' => ['label' => 'Fungsi Pekerjaan', 'type' => 'textarea', 'rules' => ['required', 'string']],
                'tingkatan_karir' => ['label' => 'Tingkatan Karir', 'type' => 'textarea', 'rules' => ['required', 'string']],
                'pengalaman' => ['label' => 'Pengalaman', 'type' => 'textarea', 'rules' => ['required', 'string']],
                'link' => ['label' => 'Tautan Lamaran', 'type' => 'url', 'rules' => ['required', 'url', 'max:2000']],
                'image' => ['label' => 'Poster', 'type' => 'image', 'folder' => 'loker', 'rules' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096']],
                'text' => ['label' => 'Deskripsi dan Persyaratan', 'type' => 'editor', 'rules' => ['required', 'string']],
            ],
        ];
    }
}
