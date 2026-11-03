<?php

// "sync": tidak butuh tabel jobs. Email pengingat dikirim bertahap per permintaan.
return [
    'default' => env('QUEUE_CONNECTION', 'sync'),
];
