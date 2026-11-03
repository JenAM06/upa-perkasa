<?php

namespace App\Services\Auth;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Login mahasiswa Unmul lewat API OSM (osm.unmul.ac.id), sama dengan aplikasi lama.
 * Kredensial layanan disimpan di .env (OSM_API_KEY, OSM_USERNAME, OSM_PASSWORD),
 * tidak lagi ditulis di kode.
 *
 * Jika berhasil, akun users dibuat/diambil dengan id = id dari OSM, lalu diberi
 * role mahasiswa dan izin mhs_perkasa + tracer_study.
 */
class OsmLogin
{
    public function enabled(): bool
    {
        return filled(config('perkasa.osm.api_key')) && filled(config('perkasa.osm.username'));
    }

    public function attempt(string $nim, string $password): ?User
    {
        if (! $this->enabled()) {
            return null;
        }

        try {
            $data = $this->request($nim, $password);
        } catch (Throwable $e) {
            Log::warning('Login OSM gagal', ['nim' => $nim, 'error' => $e->getMessage()]);

            return null;
        }

        if (! $data || empty($data['id'])) {
            return null;
        }

        return DB::transaction(function () use ($data) {
            $user = User::firstOrCreate(['id' => $data['id']], [
                'name' => $data['name'] ?? (string) $data['id'],
                'email' => $data['email'] ?? null,
            ]);

            $user->assignRole(Role::MAHASISWA);
            $user->givePermission(Permission::MHS_PERKASA, Permission::TRACER_STUDY);

            return $user;
        });
    }

    private function request(string $nim, string $password): ?array
    {
        $cfg = config('perkasa.osm');
        $client = fn () => Http::baseUrl($cfg['base_url'])->timeout($cfg['timeout'])
            ->withHeaders(['apikey' => $cfg['api_key']]);

        $token = $client()->post('/auth', [
            'username' => $cfg['username'],
            'password' => $cfg['password'],
        ])->throw()->json('data.token');

        $response = $client()->withToken($token)->post('/login', [
            'username' => $nim,
            'password' => $password,
            'usertype' => 'MHS',
        ]);

        return $response->successful() ? $response->json('data') : null;
    }
}
