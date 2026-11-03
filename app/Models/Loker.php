<?php

namespace App\Models;

use App\Models\Concerns\HasHtmlBody;
use App\Models\Concerns\HasLegacyUpload;
use App\Models\Concerns\HasUniqueSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Lowongan kerja. `tanggal_loker` diperlakukan sebagai batas lamaran
 * (213 dari 248 data bernilai setelah tanggal posting). `image` = poster.
 */
class Loker extends Model
{
    use HasHtmlBody, HasLegacyUpload, HasUniqueSlug;

    public const STATUS_OPEN = 'dibuka';

    public const STATUS_CLOSING = 'segera_berakhir';

    public const STATUS_CLOSED = 'ditutup';

    protected $table = 'loker';

    protected $fillable = ['name_loker', 'tanggal_loker', 'lokasi', 'gaji', 'fungsi', 'tingkatan_karir',
        'pengalaman', 'link', 'text', 'image', 'company_id', 'user_id', 'slug', 'post_by'];

    protected string $slugSource = 'name_loker';

    protected string $bodyColumn = 'text';

    protected string $uploadFolder = 'loker';

    protected string $uploadColumn = 'image';

    protected function casts(): array
    {
        return ['tanggal_loker' => 'date'];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function applies(): HasMany
    {
        return $this->hasMany(Apply::class);
    }

    public function getStatusAttribute(): string
    {
        $deadline = $this->tanggal_loker;

        return match (true) {
            ! $deadline, $deadline->isBefore(today()) => self::STATUS_CLOSED,
            $deadline->diffInDays(today(), true) <= config('perkasa.loker_segera_berakhir_hari') => self::STATUS_CLOSING,
            default => self::STATUS_OPEN,
        };
    }

    public function scopeStatus(Builder $q, ?string $status): Builder
    {
        $soon = today()->addDays(config('perkasa.loker_segera_berakhir_hari'));

        return match ($status) {
            self::STATUS_OPEN => $q->whereDate('tanggal_loker', '>', $soon),
            self::STATUS_CLOSING => $q->whereBetween('tanggal_loker', [today(), $soon]),
            self::STATUS_CLOSED => $q->whereDate('tanggal_loker', '<', today()),
            default => $q,
        };
    }

    public function scopeOpen(Builder $q): Builder
    {
        return $q->whereDate('tanggal_loker', '>=', today());
    }
}
