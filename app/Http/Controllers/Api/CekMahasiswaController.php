<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use Illuminate\Http\JsonResponse;

/**
 * GET /api-perkasa/{nim}/cek-mahasiswa
 * Respons sama persis dengan aplikasi lama (kode 0/1/2) karena kemungkinan dipakai sistem lain.
 */
class CekMahasiswaController extends Controller
{
    public function __invoke(string $nim): JsonResponse
    {
        $mahasiswa = Mahasiswa::with('user:id,name')->withExists('jawabanV2')
            ->where('nim', $nim)->where('user_id', $nim)->first();

        if (! $mahasiswa) {
            return response()->json(['message' => 'mahasiswa tidak ditemukan', 'status' => 'tidak terdaftar', 'kode' => 0]);
        }

        $sudah = (bool) $mahasiswa->jawaban_v2_exists;

        return response()->json([
            'nama' => $mahasiswa->user?->name,
            'nim' => $mahasiswa->nim,
            'status' => $sudah ? 'sudah mengisi kuisioner' : 'belum mengisi kuisioner',
            'kode' => $sudah ? 1 : 2,
        ]);
    }
}
