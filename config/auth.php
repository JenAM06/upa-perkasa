<?php

use App\Models\User;

/*
| Memakai tabel lama apa adanya:
| - users              : akun semua peran
| - password_resets    : token reset password (nama tabel Laravel 5.8)
| - users.api_token    : token untuk endpoint cek mahasiswa (guard "token" bawaan)
*/

return [
    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
        'api' => [
            'driver' => 'token',
            'provider' => 'users',
            'storage_key' => 'api_token',
            'hash' => false,
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => User::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,
];
