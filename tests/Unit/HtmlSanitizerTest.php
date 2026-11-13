<?php

namespace Tests\Unit;

use App\Support\Html;
use PHPUnit\Framework\TestCase;

class HtmlSanitizerTest extends TestCase
{
    public function test_it_removes_scripts_and_event_handlers(): void
    {
        $out = Html::clean('<p onclick="alert(1)">Bonjour</p><script>alert(1)</script><img src="/a.jpg" onerror="alert(1)">');

        $this->assertStringNotContainsStringIgnoringCase('<script', $out);
        $this->assertStringNotContainsStringIgnoringCase('onclick', $out);
        $this->assertStringNotContainsStringIgnoringCase('onerror', $out);
        $this->assertStringContainsString('Bonjour', $out);
    }

    public function test_it_removes_javascript_links_and_iframes(): void
    {
        $out = Html::clean('<a href="javascript:alert(1)">x</a><iframe src="https://example.org"></iframe>');

        $this->assertStringNotContainsStringIgnoringCase('javascript:', $out);
        $this->assertStringNotContainsStringIgnoringCase('<iframe', $out);
    }

    public function test_it_keeps_editor_formatting(): void
    {
        $out = Html::clean('<h2 class="ck-align-center">Titre</h2><p><strong>gras</strong> <a href="https://example.org/p">lien</a></p><ul><li>un</li></ul><table><tr><td>B</td></tr></table><img src="/storage/a.jpg" alt="photo">');

        $this->assertStringContainsString('<h2 class="ck-align-center">Titre</h2>', $out);
        $this->assertStringContainsString('<strong>gras</strong>', $out);
        $this->assertStringContainsString('href="https://example.org/p"', $out);
        $this->assertStringContainsString('<li>un</li>', $out);
        $this->assertStringContainsString('<td>B</td>', $out);
        $this->assertStringContainsString('src="/storage/a.jpg"', $out);
    }

    public function test_it_handles_empty_input(): void
    {
        $this->assertSame('', Html::clean(null));
        $this->assertSame('', Html::clean(''));
    }
}
