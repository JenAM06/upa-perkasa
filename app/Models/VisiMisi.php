<?php

namespace App\Models;

use App\Models\Concerns\HasHtmlBody;
use Illuminate\Database\Eloquent\Model;

class VisiMisi extends Model
{
    use HasHtmlBody;

    protected $table = 'visi_misi';

    protected $fillable = ['text'];

    protected string $bodyColumn = 'text';
}
