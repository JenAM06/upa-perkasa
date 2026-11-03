<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Provinsi extends Model
{
    protected $table = 'provinsi';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = ['id', 'provinsi', 'upah_minimum'];

    public function kabupaten(): HasMany
    {
        return $this->hasMany(Kabupaten::class);
    }
}
