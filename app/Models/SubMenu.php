<?php

namespace App\Models;

use App\Support\LegacyUrl;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubMenu extends Model
{
    protected $table = 'submenus';

    protected $fillable = ['title', 'icon', 'menu_id', 'url'];

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    /** URL lama /perkasa2/pages/{slug}/read diubah ke rute baru. */
    public function getHrefAttribute(): string
    {
        return $this->url ? LegacyUrl::map(trim($this->url)) : '#';
    }

    /** Slug halaman jika submenu mengarah ke tabel pages. */
    public function getPageSlugAttribute(): ?string
    {
        return preg_match('#/pages/([^/]+)/read#', (string) $this->url, $m) ? $m[1] : null;
    }
}
