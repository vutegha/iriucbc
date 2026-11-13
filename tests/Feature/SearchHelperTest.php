<?php

namespace Tests\Feature;

use App\Helpers\SearchHelper;
use Tests\TestCase;

class SearchHelperTest extends TestCase
{
    public function test_snippet_is_escaped_when_keyword_is_found(): void
    {
        $out = SearchHelper::extractAndHighlight('Etude des mines &lt;script&gt;alert(1)&lt;/script&gt;', 'mines');

        $this->assertStringContainsString('<mark class="search-highlight">mines</mark>', $out);
        $this->assertStringNotContainsString('<script>', $out);
    }

    public function test_snippet_is_escaped_when_keyword_is_absent(): void
    {
        $out = SearchHelper::extractAndHighlight('Texte &lt;script&gt;alert(1)&lt;/script&gt;', 'absent');

        $this->assertStringNotContainsString('<script>', $out);
        $this->assertStringContainsString('&lt;script&gt;', $out);
    }
}
