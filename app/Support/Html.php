<?php

namespace App\Support;

use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;

/**
 * Cleans rich-text HTML (CKEditor content) before it is printed with {!! !!}.
 *
 * Formatting, lists, tables, links and images are kept. Scripts, event
 * handler attributes (onclick...), inline styles, iframes, forms and
 * javascript: links are removed.
 */
class Html
{
    private static ?HtmlSanitizer $sanitizer = null;

    public static function clean(?string $html): string
    {
        if ($html === null || $html === '') {
            return '';
        }

        return self::sanitizer()->sanitize($html);
    }

    private static function sanitizer(): HtmlSanitizer
    {
        if (self::$sanitizer) {
            return self::$sanitizer;
        }

        $config = (new HtmlSanitizerConfig())
            ->allowSafeElements()
            // CKEditor uses class names for alignment, text size and image layout.
            ->allowAttribute('class', '*')
            ->allowLinkSchemes(['http', 'https', 'mailto', 'tel'])
            ->allowRelativeLinks()
            ->allowMediaSchemes(['http', 'https'])
            ->allowRelativeMedias()
            ->withMaxInputLength(500000);

        return self::$sanitizer = new HtmlSanitizer($config);
    }
}
