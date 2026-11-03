<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Data alumni dari database kedua `tracerstudy` (koneksi mysql2). */
class Alumni extends Model
{
    protected $connection = 'mysql2';

    protected $table = 'talumni_unmul';

    protected $guarded = [];
}
