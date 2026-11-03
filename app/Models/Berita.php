<?php

namespace App\Models;

use App\Models\Concerns\HasHtmlBody;
use App\Models\Concerns\HasLegacyUpload;
use App\Models\Concerns\HasUniqueSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    use HasHtmlBody, HasLegacyUpload, HasUniqueSlug;

    protected $table = 'berita';

    protected $fillable = ['image', 'judul', 'isi', 'slug', 'post_by', 'user_id', 'slider'];

    protected $attributes = ['slider' => 'false'];

    protected string $slugSource = 'judul';

    protected string $bodyColumn = 'isi';

    protected string $uploadFolder = 'berita';

    protected string $uploadColumn = 'image';

    public function scopeSlider(Builder $q): Builder
    {
        return $q->where('slider', 'true');
    }

    public function getTitleAttribute(): string
    {
        return (string) $this->judul;
    }
}
