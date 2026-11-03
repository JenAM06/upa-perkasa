<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** Data dari form alumni publik tracer study. Diverifikasi Admin Perkasa. */
class AlumniLama extends Model
{
    protected $table = 'alumni_lama';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return ['tanggal_lahir' => 'date', 'is_verified' => 'boolean'];
    }

    public function fakultas(): BelongsTo
    {
        return $this->belongsTo(Fakultas::class);
    }

    public function prodi(): BelongsTo
    {
        return $this->belongsTo(Prodi::class);
    }
}
