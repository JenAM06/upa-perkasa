<?php

namespace App\Models;

use App\Models\Concerns\HasHtmlBody;
use App\Models\Concerns\HasLegacyUpload;
use App\Models\Concerns\HasUniqueSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasHtmlBody, HasLegacyUpload, HasUniqueSlug;

    protected $table = 'event';

    protected $fillable = ['title', 'image', 'text', 'slug', 'post_by', 'user_id', 'slider'];

    protected $attributes = ['slider' => 'false'];

    protected string $slugSource = 'title';

    protected string $bodyColumn = 'text';

    protected string $uploadFolder = 'event';

    protected string $uploadColumn = 'image';

    public function scopeSlider(Builder $q): Builder
    {
        return $q->where('slider', 'true');
    }
}
