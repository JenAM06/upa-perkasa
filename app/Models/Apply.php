<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** Lamaran kerja. status: pending | reject | accepted */
class Apply extends Model
{
    protected $table = 'apply';

    protected $fillable = ['user_id', 'loker_id', 'status'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function loker(): BelongsTo
    {
        return $this->belongsTo(Loker::class);
    }
}
