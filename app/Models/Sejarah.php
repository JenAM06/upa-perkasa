<?php

namespace App\Models;

use App\Models\Concerns\HasHtmlBody;
use Illuminate\Database\Eloquent\Model;

class Sejarah extends Model
{
    use HasHtmlBody;

    protected $table = 'sejarah';

    protected $fillable = ['text'];

    protected string $bodyColumn = 'text';
}
