<?php

namespace App\Http\Controllers\Panel\AdminPerkasa;

use App\Http\Controllers\Panel\CrudController;
use App\Models\Apply;

class JobApplyController extends CrudController
{
    protected function definition(): array
    {
        return [
            'model' => Apply::class,
            'title' => 'Pelamar Kerja',
            'route' => 'panel.admin.apply',
            'readonly' => true,
            'with' => ['user:id,name,email', 'loker:id,name_loker,slug,company_id', 'loker.company:id,company_name'],
            'columns' => [
                'Nama' => fn (Apply $a) => $a->user?->name,
                'Lowongan' => fn (Apply $a) => $a->loker?->name_loker,
                'Perusahaan' => fn (Apply $a) => $a->loker?->company?->company_name,
                'Status' => fn (Apply $a) => ['pending' => 'Menunggu', 'reject' => 'Ditolak', 'accepted' => 'Diterima'][$a->status] ?? $a->status,
                'Tanggal' => fn (Apply $a) => $a->created_at?->format('d/m/Y'),
            ],
            'fields' => [],
        ];
    }
}
