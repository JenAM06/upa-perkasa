<?php

namespace App\Models;

use App\Models\Concerns\HasHtmlBody;
use App\Models\Concerns\HasUniqueSlug;
use App\Support\LegacyUrl;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** Halaman statis (profil, PPID, report, dll). Kolom `url` warisan lama, diisi URL halaman. */
class Page extends Model
{
    use HasHtmlBody, HasUniqueSlug;

    protected $table = 'pages';

    protected $fillable = ['title', 'text', 'url', 'slug', 'post_by', 'submenu_id'];

    protected string $slugSource = 'title';

    protected string $bodyColumn = 'text';

    protected static function booted(): void
    {
        // Kolom url NOT NULL di tabel lama
        static::saving(function (Page $page) {
            $page->url = $page->url ?: '';
        });
        static::saved(function (Page $page) {
            if ($page->url === '' && $page->slug) {
                $page->url = LegacyUrl::pageUrl($page->slug);
                $page->saveQuietly();
            }
        });
    }

    public function submenu(): BelongsTo
    {
        return $this->belongsTo(SubMenu::class, 'submenu_id');
    }
}
