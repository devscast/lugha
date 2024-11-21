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

namespace Devscast\Lugha\Tests\Model\Completion\Prompt;

use Devscast\Lugha\Model\Completion\Prompt\PromptTemplate;
use PHPUnit\Framework\TestCase;

/**
 * Class PromptTemplateTest.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class PromptTemplateTest extends TestCase
{
    public function testItCanCreateAPromptTemplate(): void
    {
        $template = PromptTemplate::from('Hello {context}');

        $this->assertInstanceOf(PromptTemplate::class, $template);
    }

    public function testItCanFormatAPromptTemplate(): void
    {
        $template = PromptTemplate::from('Hello {context}');
        $prompt = $template->format([
            '{context}' => 'some context...',
        ]);

        $this->assertInstanceOf(PromptTemplate::class, $prompt);
        $this->assertSame('Hello some context...', (string) $prompt);
    }

    public function testItCanFormatAPromptTemplateWithMultiplePlaceholders(): void
    {
        $template = PromptTemplate::from('Hello {context}, welcome to {place}');
        $prompt = $template->format([
            '{context}' => 'some context...',
            '{place}' => 'some place...',
        ]);

        $this->assertInstanceOf(PromptTemplate::class, $prompt);
        $this->assertSame('Hello some context..., welcome to some place...', (string) $prompt);
    }

    public function testItFailsToCreateAPromptTemplateWithEmptyTemplate(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Template cannot be empty');

        new PromptTemplate('');
    }

    public function testItFailsToFormatAPromptTemplateWithEmptyValues(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Values cannot be empty');

        $template = PromptTemplate::from('Hello {context}');
        $template->format([]);
    }

    public function testItFailsToFormatAPromptTemplateWithoutFormatting(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('The template has not been formatted yet');
        $template = PromptTemplate::from('Hello {context}');
        $prompt = (string) $template;
    }
}
