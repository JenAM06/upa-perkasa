<?php

namespace App\Models;

use App\Support\LegacyStorage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * Perusahaan mitra. Satu baris per akun `users` ber-role company.
 * Logo diambil dari users.foto (tabel company tidak punya kolom logo);
 * `file` adalah berkas dokumen perusahaan.
 */
class Company extends Model
{
    protected $table = 'company';

    protected $fillable = ['company_name', 'nama_pimpinan', 'alamat_pusat', 'alamat_cabang', 'bidang',
        'website', 'facebook', 'twitter', 'no_telp', 'file', 'deskripsi', 'user_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lokers(): HasMany
    {
        return $this->hasMany(Loker::class);
    }

    public function getLogoUrlAttribute(): ?string
    {
        return $this->user?->foto_url;
    }

    public function getInitialsAttribute(): string
    {
        $words = preg_split('/\s+/', trim(preg_replace('/^(PT|CV|UD|PD)\.?\s+/i', '', (string) $this->company_name)));

        return Str::upper(collect($words)->filter()->take(2)->map(fn ($w) => mb_substr($w, 0, 1))->implode(''));
    }

    public function getFileUrlAttribute(): ?string
    {
        return LegacyStorage::url(config('perkasa.upload_folders.company'), $this->file);
    }
}
