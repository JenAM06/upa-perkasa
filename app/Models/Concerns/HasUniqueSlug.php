<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

/**
 * Membuat slug otomatis dari kolom judul saat data dibuat (format sama dengan
 * aplikasi lama: Str::slug). Slug dibuat unik dengan akhiran -2, -3, dst.
 * Slug TIDAK diubah saat judul disunting, agar tautan yang sudah tersebar tetap hidup.
 *
 * Model wajib mendefinisikan: protected string $slugSource = 'judul';
 */
trait HasUniqueSlug
{
    public static function bootHasUniqueSlug(): void
    {
        static::creating(function ($model) {
            if (blank($model->slug)) {
                $model->slug = $model->uniqueSlug((string) $model->{$model->slugSource});
            }
        });
    }

    public function uniqueSlug(string $source): string
    {
        $base = Str::slug($source) ?: Str::lower(Str::random(8));
        $slug = $base;
        $i = 2;

        while (static::query()
            ->where('slug', $slug)
            ->when($this->exists, fn ($q) => $q->whereKeyNot($this->getKey()))
            ->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }

    /**
     * Parameter URL. Sebagian data lama slug-nya kosong (judul hanya berisi emoji/simbol),
     * sehingga dipakai id sebagai gantinya.
     */
    public function getRouteSlugAttribute(): string
    {
        return filled(trim((string) $this->slug)) ? $this->slug : (string) $this->getKey();
    }

    /** Cari berdasarkan slug (data lama punya slug ganda: ambil yang terbaru), atau id untuk slug kosong. */
    public function scopeWhereSlug($query, string $slug)
    {
        return $query->where(function ($q) use ($slug) {
            $q->where('slug', $slug);
            if (ctype_digit($slug)) {
                $q->orWhere(fn ($w) => $w->whereKey((int) $slug)->whereRaw("TRIM(COALESCE(slug, '')) = ''"));
            }
        })->latest('id');
    }
}
