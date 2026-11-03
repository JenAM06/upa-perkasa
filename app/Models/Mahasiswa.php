<?php

namespace App\Models;

use App\Models\Kuisioner\JawabanMhsV2;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Data alumni Unmul (49 ribu baris). Memuat data pribadi (NIK, No. KK, NPWP):
 * jangan tampilkan di daftar atau ekspor publik.
 */
class Mahasiswa extends Model
{
    protected $table = 'mahasiswa';

    protected $guarded = ['id'];

    protected $hidden = ['nik', 'no_kk', 'npwp'];

    protected function casts(): array
    {
        return ['tanggal_lahir' => 'date', 'tgl_lulus' => 'date', 'send_email_at' => 'date'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function fakultas(): BelongsTo
    {
        return $this->belongsTo(Fakultas::class);
    }

    public function prodi(): BelongsTo
    {
        return $this->belongsTo(Prodi::class);
    }

    public function jawabanV2(): HasOne
    {
        return $this->hasOne(JawabanMhsV2::class, 'mhs_id');
    }
}
