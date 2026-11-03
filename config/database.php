<?php

/*
| Database TIDAK diubah oleh aplikasi ini. Jangan jalankan `php artisan migrate`
| di database produksi. Folder database/migrations sengaja dikosongkan.
*/

return [
    'default' => env('DB_CONNECTION', 'mysql'),

    'connections' => [
        'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'db_perkasa'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => false, // data lama banyak memakai tanggal/enum yang tidak lolos strict mode
            'engine' => null,
        ],

        // Database kedua (tracerstudy.talumni_unmul), dipakai App\Models\Alumni
        'mysql2' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST_2', env('DB_HOST', '127.0.0.1')),
            'port' => env('DB_PORT_2', env('DB_PORT', '3306')),
            'database' => env('DB_DATABASE_2', 'tracerstudy'),
            'username' => env('DB_USERNAME_2', env('DB_USERNAME', 'root')),
            'password' => env('DB_PASSWORD_2', env('DB_PASSWORD', '')),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => false,
            'engine' => null,
        ],

        // Hanya untuk tes otomatis
        'sqlite' => [
            'driver' => 'sqlite',
            'database' => env('DB_SQLITE_DATABASE', ':memory:'),
            'prefix' => '',
            'foreign_key_constraints' => true,
        ],
    ],

    'migrations' => [
        'table' => 'migrations_v2',
        'update_date_on_publish' => true,
    ],
];
