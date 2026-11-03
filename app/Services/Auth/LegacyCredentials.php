<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * Memeriksa password akun lama. Kolom users.password berisi campuran:
 * - bcrypt ($2y$/$2a$) dari Laravel 5.8
 * - md5 (32 karakter heksa) dari sistem sebelumnya; langsung di-rehash ke bcrypt saat login berhasil
 * - NULL untuk akun yang hanya login lewat Google/OSM
 *
 * Auth::attempt() tidak dipakai karena hasher Laravel melempar exception untuk hash md5.
 */
class LegacyCredentials
{
    public function verify(User $user, string $password): bool
    {
        $hash = (string) $user->getAuthPassword();

        if ($hash === '') {
            return false;
        }

        if (str_starts_with($hash, '$2')) {
            if (! password_verify($password, $hash)) {
                return false;
            }
            if (Hash::needsRehash($hash)) {
                $user->forceFill(['password' => $password])->save();
            }

            return true;
        }

        if (preg_match('/^[a-f0-9]{32}$/i', $hash) && hash_equals(strtolower($hash), md5($password))) {
            $user->forceFill(['password' => $password])->save();

            return true;
        }

        return false;
    }
}
