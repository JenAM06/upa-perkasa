<?php

namespace App\Support;

use Illuminate\Support\Str;

/**
 * Mengubah URL aplikasi lama (perkasa.unmul.ac.id/perkasa2/...) ke rute aplikasi baru.
 * Dipakai untuk tautan di konten HTML lama, URL di tabel submenus, dan redirect 301.
 */
class LegacyUrl
{
    public static function map(string $url): string
    {
        $parts = parse_url($url);
        $host = strtolower($parts['host'] ?? '');

        if ($host === '' || ! in_array($host, config('perkasa.legacy_hosts', []), true)) {
            return $url;
        }

        return static::fromPath($parts['path'] ?? '/') ?? $url;
    }

    /** Kembalikan URL baru untuk path lama, atau null jika tidak dikenali. */
    public static function fromPath(string $path): ?string
    {
        $path = '/'.trim($path, '/');
        $path = preg_replace('#^/perkasa2?(?=/|$)#', '', $path) ?: '/';

        // /storages/... tetap path yang sama (dilayani symlink public/storages)
        if (Str::startsWith($path, '/storages/')) {
            return url($path);
        }

        $patterns = [
            '#^/?$#' => fn () => route('home'),
            '#^/pages/([^/]+)/read$#' => fn ($m) => static::pageUrl($m[1]),
            '#^/read/([^/]+)/berita$#' => fn ($m) => route('publikasi.berita-detail', $m[1]),
            '#^/read/([^/]+)/agenda$#' => fn ($m) => route('publikasi.agenda-detail', $m[1]),
            '#^/read/([^/]+)/event$#' => fn ($m) => route('publikasi.event-detail', $m[1]),
            '#^/read/([^/]+)/pengumuman$#' => fn ($m) => route('publikasi.pengumuman-detail', $m[1]),
            '#^/read/([^/]+)/lowongan$#' => fn ($m) => route('publikasi.lowongan-detail', $m[1]),
            '#^/list/berita$#' => fn () => route('publikasi.berita'),
            '#^/list/agenda$#' => fn () => route('publikasi.agenda'),
            '#^/list/pengumuman$#' => fn () => route('publikasi.pengumuman'),
            '#^/list/loker$#' => fn () => route('publikasi.lowongan'),
            '#^/sejarah$#' => fn () => route('profile.sejarah'),
            '#^/visi-misi$#' => fn () => route('profile.visi-misi'),
            '#^/tracer-study$#' => fn () => route('tracer.index'),
            '#^/start-session$#' => fn () => route('login'),
        ];

        foreach ($patterns as $regex => $resolver) {
            if (preg_match($regex, $path, $m)) {
                return $resolver($m);
            }
        }

        return null;
    }

    /** Halaman dari tabel pages: pakai rute khusus jika slug terdaftar di config, selain itu /halaman/{slug}. */
    public static function pageUrl(string $slug): string
    {
        foreach (config('perkasa.pages', []) as $routeName => $page) {
            if (in_array($slug, (array) $page['slug'], true)) {
                return route($routeName);
            }
        }

        return route('pages.show', $slug);
    }
}
