<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prodi extends Model
{
    protected $table = 'prodi';

    protected $fillable = ['prodi', 'is_active', 'jenjang', 'kode', 'fakultas_id'];

    public function fakultas(): BelongsTo
    {
        return $this->belongsTo(Fakultas::class);
    }

    public function scopeActive(Builder $q): Builder
    {
        return $q->where('is_active', 1);
    }
}
