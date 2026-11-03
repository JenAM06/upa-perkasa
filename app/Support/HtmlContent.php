<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMXPath;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * Konten HTML dari editor Summernote (berita, agenda, event, pengumuman, loker, pages).
 *
 * - clean():   buang tag/atribut berbahaya, izinkan iframe hanya dari YouTube dan Google,
 *              ubah tautan lama perkasa.unmul.ac.id/perkasa2/... ke rute baru.
 * - excerpt(): teks polos untuk cuplikan kartu.
 *
 * Ditulis tanpa paket tambahan agar tidak menambah dependensi.
 */
class HtmlContent
{
    private const DROP_WITH_CONTENT = ['script', 'style', 'object', 'embed', 'applet', 'form', 'input',
        'button', 'select', 'textarea', 'link', 'meta', 'base', 'frame', 'frameset', 'svg', 'math', 'template'];

    private const ALLOWED_TAGS = ['a', 'abbr', 'b', 'blockquote', 'br', 'caption', 'cite', 'code', 'col',
        'colgroup', 'dd', 'del', 'div', 'dl', 'dt', 'em', 'figcaption', 'figure', 'font', 'h1', 'h2', 'h3',
        'h4', 'h5', 'h6', 'hr', 'i', 'iframe', 'img', 'ins', 'li', 'mark', 'ol', 'p', 'pre', 'q', 's', 'small',
        'span', 'strike', 'strong', 'sub', 'sup', 'table', 'tbody', 'td', 'tfoot', 'th', 'thead', 'tr', 'u',
        'ul', 'video', 'source', 'center'];

    private const ALLOWED_ATTRS = ['href', 'src', 'alt', 'title', 'width', 'height', 'style', 'class',
        'colspan', 'rowspan', 'target', 'rel', 'align', 'border', 'cellpadding', 'cellspacing', 'color',
        'face', 'size', 'allow', 'allowfullscreen', 'frameborder', 'controls', 'type', 'start'];

    private const IFRAME_HOSTS = ['www.youtube.com', 'youtube.com', 'www.youtube-nocookie.com',
        'drive.google.com', 'docs.google.com', 'www.google.com', 'datastudio.google.com',
        'lookerstudio.google.com', 'player.vimeo.com'];

    public static function clean(?string $html): string
    {
        if (blank($html)) {
            return '';
        }

        // Konten bisa sampai ±1 MB (gambar base64); hasil disimpan di cache.
        return Cache::rememberForever('html:'.md5($html), fn () => static::sanitize($html));
    }

    public static function excerpt(?string $html, int $limit = 150): string
    {
        // Buang tag <img> dulu supaya base64 tidak ikut diproses strip_tags
        $text = preg_replace(['/<(script|style)\b[^>]*>.*?<\/\1>/is', '/<img[^>]*>/i'], ' ', (string) $html);
        $text = html_entity_decode(strip_tags($text), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = preg_replace('/\s+/u', ' ', str_replace("\u{00A0}", ' ', $text));

        return Str::limit(trim($text), $limit);
    }

    private static function sanitize(string $html): string
    {
        $doc = new DOMDocument('1.0', 'UTF-8');
        $previous = libxml_use_internal_errors(true);
        $doc->loadHTML('<?xml encoding="UTF-8"><div id="__root">'.$html.'</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_PARSEHUGE);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $root = (new DOMXPath($doc))->query('//*[@id="__root"]')->item(0);
        if (! $root) {
            return e(strip_tags($html));
        }

        static::walk($root);

        $out = '';
        foreach ($root->childNodes as $child) {
            $out .= $doc->saveHTML($child);
        }

        return $out;
    }

    private static function walk(DOMNode $node): void
    {
        // Salin daftar anak karena akan dimodifikasi
        foreach (iterator_to_array($node->childNodes) as $child) {
            if ($child->nodeType === XML_COMMENT_NODE) {
                $node->removeChild($child);

                continue;
            }

            if (! $child instanceof DOMElement) {
                continue;
            }

            $tag = strtolower($child->tagName);

            if (in_array($tag, self::DROP_WITH_CONTENT, true)) {
                $node->removeChild($child);

                continue;
            }

            if (! in_array($tag, self::ALLOWED_TAGS, true)) {
                // Tag tidak dikenal: buang tag-nya, pertahankan isinya
                static::walk($child);
                while ($child->firstChild) {
                    $node->insertBefore($child->firstChild, $child);
                }
                $node->removeChild($child);

                continue;
            }

            static::cleanAttributes($child, $tag);

            if ($tag === 'iframe' && ! static::allowedIframe($child->getAttribute('src'))) {
                $node->removeChild($child);

                continue;
            }

            static::walk($child);
        }
    }

    private static function cleanAttributes(DOMElement $el, string $tag): void
    {
        foreach (iterator_to_array($el->attributes) as $attr) {
            $name = strtolower($attr->name);
            $value = trim($attr->value);

            if (! in_array($name, self::ALLOWED_ATTRS, true)) {
                $el->removeAttribute($attr->name);

                continue;
            }

            if (in_array($name, ['href', 'src'], true)) {
                if (! static::safeUrl($value, $tag === 'img' && $name === 'src')) {
                    $el->removeAttribute($attr->name);

                    continue;
                }
                $el->setAttribute($name, LegacyUrl::map($value));
            }

            if ($name === 'style' && preg_match('/expression|javascript:|url\s*\(|behavior|@import/i', $value)) {
                $el->removeAttribute('style');
            }
        }

        if ($tag === 'a' && $el->getAttribute('target') === '_blank') {
            $el->setAttribute('rel', 'noopener noreferrer');
        }

        if ($tag === 'img' && ! $el->hasAttribute('loading')) {
            $el->setAttribute('loading', 'lazy');
        }
    }

    private static function safeUrl(string $url, bool $allowDataImage): bool
    {
        if ($url === '' || str_starts_with($url, '#') || str_starts_with($url, '/')) {
            return true;
        }

        if ($allowDataImage && preg_match('#^data:image/(png|jpe?g|gif|webp);base64,#i', $url)) {
            return true;
        }

        $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));

        return in_array($scheme, ['http', 'https', 'mailto', 'tel', ''], true)
            && ! preg_match('/^\s*(javascript|vbscript|data):/i', $url);
    }

    private static function allowedIframe(string $src): bool
    {
        $host = strtolower((string) parse_url($src, PHP_URL_HOST));

        return in_array($host, self::IFRAME_HOSTS, true);
    }
}
