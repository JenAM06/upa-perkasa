<?php

namespace App\Models\Kuisioner;

use App\Models\Mahasiswa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** Jawaban kuisioner tracer study versi 2: satu baris per mahasiswa, satu kolom per butir (f8, f5_04, ...). */
class JawabanMhsV2 extends Model
{
    protected $table = 'jawaban_mhs_v2';

    protected $guarded = ['id'];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class, 'mhs_id');
    }
}
