<?php

namespace App\Models\Concerns;

use App\Support\LegacyStorage;

/**
 * Model yang menyimpan nama file upload (pola lama: 10 karakter acak + ekstensi)
 * di salah satu kolomnya. Model wajib mendefinisikan:
 *   protected string $uploadFolder = 'berita';   // folder di disk legacy
 *   protected string $uploadColumn = 'image';    // kolom nama file
 */
trait HasLegacyUpload
{
    public function getFileUrlAttribute(): ?string
    {
        return LegacyStorage::url($this->uploadFolder, $this->{$this->uploadColumn});
    }

    public function getImageUrlAttribute(): string
    {
        return $this->file_url ?? asset('img/placeholder.svg');
    }
}
