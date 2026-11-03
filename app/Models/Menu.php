<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    protected $table = 'menus';

    protected $fillable = ['title', 'url', 'icon'];

    public function submenus(): HasMany
    {
        return $this->hasMany(SubMenu::class, 'menu_id')->orderBy('id');
    }
}
