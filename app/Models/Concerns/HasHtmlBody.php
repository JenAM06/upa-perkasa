<?php

namespace App\Models\Concerns;

use App\Support\HtmlContent;

/**
 * Model dengan kolom isi HTML. Model wajib mendefinisikan: protected string $bodyColumn = 'text';
 */
trait HasHtmlBody
{
    public function getBodyHtmlAttribute(): string
    {
        return HtmlContent::clean($this->{$this->bodyColumn});
    }

    public function getExcerptAttribute(): string
    {
        return HtmlContent::excerpt($this->{$this->bodyColumn});
    }
}
