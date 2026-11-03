<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kabupaten extends Model
{
    protected $table = 'kabupaten';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = ['id', 'kabupaten', 'provinsi_id'];

    public function provinsi(): BelongsTo
    {
        return $this->belongsTo(Provinsi::class);
    }
}
