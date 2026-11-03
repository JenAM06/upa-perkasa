<?php

namespace App\Models;

use App\Models\Concerns\HasHtmlBody;
use App\Models\Concerns\HasUniqueSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/** Kolom `date` = tanggal kegiatan. Tabel ini tidak punya kolom gambar. */
class Agenda extends Model
{
    use HasHtmlBody, HasUniqueSlug;

    protected $table = 'agenda';

    protected $fillable = ['title', 'text', 'date', 'slug', 'post_by', 'user_id'];

    protected string $slugSource = 'title';

    protected string $bodyColumn = 'text';

    protected function casts(): array
    {
        return ['date' => 'date'];
    }

    public function scopeUpcoming(Builder $q): Builder
    {
        return $q->whereDate('date', '>=', today())->orderBy('date');
    }

    public function isPast(): bool
    {
        return $this->date?->isBefore(today()) ?? false;
    }
}
