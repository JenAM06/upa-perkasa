<?php

namespace App\Models;

use App\Models\Concerns\HasLegacyRoles;
use App\Support\LegacyStorage;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Tabel `users` (78 ribu akun semua peran). Kolom `email` untuk mahasiswa sering berisi NIM.
 * `suspend` berisi string 'true'/'false' (enum lama).
 */
class User extends Authenticatable
{
    use HasLegacyRoles, Notifiable;

    protected $fillable = ['name', 'email', 'socialite_id', 'socialite_name', 'foto', 'kontak', 'password', 'suspend'];

    protected $hidden = ['password', 'remember_token', 'api_token'];

    protected $attributes = ['suspend' => 'false'];

    protected function casts(): array
    {
        return ['password' => 'hashed'];
    }

    public function isSuspended(): bool
    {
        return $this->suspend === 'true';
    }

    public function getFotoUrlAttribute(): ?string
    {
        return LegacyStorage::url(config('perkasa.upload_folders.user'), $this->foto);
    }

    public function company(): HasOne
    {
        return $this->hasOne(Company::class);
    }

    public function mahasiswa(): HasOne
    {
        return $this->hasOne(Mahasiswa::class);
    }

    public function mahasiswaNonUnmul(): HasOne
    {
        return $this->hasOne(MahasiswaNonUnmul::class);
    }

    public function adminProdi(): HasOne
    {
        return $this->hasOne(AdminProdi::class);
    }

    public function adminFakultas(): HasOne
    {
        return $this->hasOne(AdminFakultas::class);
    }
}
