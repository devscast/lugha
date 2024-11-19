<?php

/*
 * This file is part of the Lugha package.
 *
 * (c) Bernard Ngandu <bernard@devscast.tech>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Devscast\Lugha\Tests\Model\Chat\Parser;

use Devscast\Lugha\Model\Chat\Parser\MarkdownOutputParser;
use PHPUnit\Framework\TestCase;

/**
 * Class JsonOutputParserTest.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class MarkdownOutputParserTest extends TestCase
{
    public function testConvertsToHtml(): void
    {
        $parser = new MarkdownOutputParser();
        $this->assertSame('<h1>Hello World</h1>', $parser('# Hello World'));
    }

    public function testConvertsToHtmlWithMultipleLines(): void
    {
        $parser = new MarkdownOutputParser();
        $this->assertSame(
            expected: "<h1>Hello World</h1>\n<p>This is a test</p>",
            actual: $parser("# Hello World\nThis is a test")
        );
    }

    public function testConvertsLinksToHtml(): void
    {
        $parser = new MarkdownOutputParser();
        $this->assertSame(
            expected: '<p><a href="https://example.com">Example</a></p>',
            actual: $parser('[Example](https://example.com)')
        );
    }

    public function testConvertsAutoLinksToHtml(): void
    {
        $parser = new MarkdownOutputParser();
        $this->assertSame(
            expected: '<p><a href="https://example.com">https://example.com</a></p>',
            actual: $parser('https://example.com')
        );
    }

    public function testEscapeHtmlTags(): void
    {
        $parser = new MarkdownOutputParser([
            'html_input' => 'escape',
        ]);
        $this->assertSame(
            expected: '&lt;title&gt;Strong&lt;/title&gt;',
            actual: $parser('<title>Strong</title>')
        );
    }

    public function testStripHtmlTags(): void
    {
        $parser = new MarkdownOutputParser([
            'html_input' => 'strip',
        ]);
        $this->assertSame(
            expected: '<p>Strong</p>',
            actual: $parser('<strong>Strong</strong>')
        );
    }
}
