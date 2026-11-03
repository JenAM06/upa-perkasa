<?php

return [
    'default' => env('FILESYSTEM_DISK', 'local'),

    'disks' => [
        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'serve' => true,
            'throw' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
            'throw' => false,
        ],

        /*
        | File upload dari aplikasi lama (storages/{berita,event,loker,company,user,mahasiswa}).
        | Database hanya menyimpan nama file. Isi folder ini dari backup, lalu jalankan
        | `php artisan storage:link`. Selama pengembangan, LEGACY_ASSET_URL boleh diarahkan
        | ke https://perkasa.unmul.ac.id/storages agar gambar tampil tanpa menyalin file.
        */
        'legacy' => [
            'driver' => 'local',
            'root' => storage_path('app/legacy'),
            'url' => env('LEGACY_ASSET_URL') ?: rtrim((string) env('APP_URL'), '/').'/storages',
            'remote' => env('LEGACY_ASSET_URL'),
            'visibility' => 'public',
            'throw' => false,
        ],
    ],

    'links' => [
        public_path('storage') => storage_path('app/public'),
        public_path('storages') => storage_path('app/legacy'),
    ],
];
