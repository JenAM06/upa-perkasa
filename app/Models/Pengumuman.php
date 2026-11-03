<?php

namespace App\Models;

use App\Models\Concerns\HasHtmlBody;
use App\Models\Concerns\HasUniqueSlug;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    use HasHtmlBody, HasUniqueSlug;

    protected $table = 'pengumuman';

    protected $fillable = ['title', 'text', 'post_by', 'slug'];

    protected string $slugSource = 'title';

    protected string $bodyColumn = 'text';
}
