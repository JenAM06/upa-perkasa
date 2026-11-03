<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/** Filter daftar publik: ?q= (judul), ?bulan=1-12, ?tahun=YYYY */
class ListFilter
{
    public const MONTHS = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli',
        'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    public static function apply(Builder $query, Request $request, string $titleColumn, string $dateColumn = 'created_at'): Builder
    {
        $q = trim((string) $request->query('q'));
        $bulan = (int) $request->query('bulan');
        $tahun = (int) $request->query('tahun');

        return $query
            ->when($q !== '', fn ($w) => $w->where($titleColumn, 'like', "%{$q}%"))
            ->when($bulan >= 1 && $bulan <= 12, fn ($w) => $w->whereMonth($dateColumn, $bulan))
            ->when($tahun > 2000, fn ($w) => $w->whereYear($dateColumn, $tahun));
    }
}
