<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Akses folder upload lama (disk "legacy"). Nama file dan struktur folder
 * dipertahankan persis seperti aplikasi lama supaya kolom di database tetap berlaku.
 */
class LegacyStorage
{
    public static function disk()
    {
        return Storage::disk('legacy');
    }

    public static function url(string $folder, ?string $file): ?string
    {
        if (blank($file)) {
            return null;
        }

        // Konten lama kadang sudah menyimpan URL penuh
        if (Str::startsWith($file, ['http://', 'https://'])) {
            return $file;
        }

        $path = trim($folder, '/').'/'.ltrim($file, '/');

        // File lokal belum disalin dari backup: kembalikan null agar tampil gambar pengganti.
        // Jika LEGACY_ASSET_URL diisi (gambar dari server lama), pemeriksaan dilewati.
        if (blank(config('filesystems.disks.legacy.remote')) && ! static::disk()->exists($path)) {
            return null;
        }

        return static::disk()->url($path);
    }

    public static function exists(string $folder, ?string $file): bool
    {
        return filled($file) && static::disk()->exists(trim($folder, '/').'/'.$file);
    }

    /** Simpan file baru dengan pola nama lama, kembalikan nama file untuk disimpan di kolom. */
    public static function store(UploadedFile $file, string $folder): string
    {
        $name = Str::random(10).'.'.strtolower($file->getClientOriginalExtension());
        static::disk()->putFileAs(trim($folder, '/'), $file, $name);

        return $name;
    }

    public static function delete(string $folder, ?string $file): void
    {
        if (filled($file) && ! Str::startsWith($file, ['http://', 'https://'])) {
            static::disk()->delete(trim($folder, '/').'/'.$file);
        }
    }
}
