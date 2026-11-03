<?php

namespace App\Http\Controllers\Panel\AdminPerkasa;

use App\Http\Controllers\Panel\CrudController;
use App\Models\MahasiswaNonUnmul;

class MahasiswaNonUnmulController extends CrudController
{
    protected function definition(): array
    {
        return [
            'model' => MahasiswaNonUnmul::class,
            'title' => 'Mahasiswa Non Unmul',
            'route' => 'panel.admin.non-unmul',
            'readonly' => true,
            'search' => ['email', 'universitas', 'prodi'],
            'with' => ['user:id,name'],
            'columns' => [
                'Nama' => fn ($m) => $m->user?->name,
                'Email' => fn ($m) => $m->email,
                'Universitas' => fn ($m) => $m->universitas,
                'Prodi' => fn ($m) => $m->prodi,
                'Jenjang' => fn ($m) => $m->jenjang_pendidikan,
                'No. HP' => fn ($m) => $m->no_hp,
            ],
            'fields' => [],
        ];
    }
}
