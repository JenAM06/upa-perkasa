<?php

// Hanya kunci yang berbeda dari bawaan Laravel. Driver "file": tidak butuh tabel sessions.
return [
    'driver' => env('SESSION_DRIVER', 'file'),
    'lifetime' => (int) env('SESSION_LIFETIME', 120),
];
