<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** status: active | disactive */
class AdminFakultas extends Model
{
    protected $table = 'admin_fakultas';

    protected $fillable = ['user_id', 'fak_id', 'status'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function fakultas(): BelongsTo
    {
        return $this->belongsTo(Fakultas::class, 'fak_id');
    }
}
