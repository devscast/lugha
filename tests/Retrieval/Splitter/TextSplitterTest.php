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

namespace Devscast\Lugha\Tests\Retrieval\Splitter;

use Devscast\Lugha\Retrieval\Splitter\CharacterTextSplitter;
use PHPUnit\Framework\TestCase;

/**
 * Class TextSplitterTest.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
class TextSplitterTest extends TestCase
{
    public function testSingleChunkNoSeparator(): void
    {
        $splitter = new CharacterTextSplitter(200);
        $text = 'This is a short text that does not exceed the chunk size.';
        $chunks = iterator_to_array($splitter->splitText($text));

        $this->assertCount(1, $chunks);
        $this->assertEquals($text, $chunks[0]);
    }

    public function testChunksWithOverlap(): void
    {
        $splitter = new CharacterTextSplitter(10, 5);
        $text = 'abcdefghijklmnopqrstuvwxyz';
        $expectedChunks = [
            'abcdefghij',
            'fghijklmno',
            'klmnopqrst',
            'pqrstuvwxy',
            'uvwxyz',
        ];
        $chunks = iterator_to_array($splitter->splitText($text));

        $this->assertCount(5, $chunks);
        $this->assertEquals($expectedChunks, $chunks);
    }

    public function testEmptyText(): void
    {
        $splitter = new CharacterTextSplitter(200);
        $text = '';
        $chunks = iterator_to_array($splitter->splitText($text));

        $this->assertCount(0, $chunks);
    }

    public function testVerySmallChunkSize(): void
    {
        $splitter = new CharacterTextSplitter(5);
        $text = 'This is a test.';
        $expectedChunks = [
            'This ',
            'is a ',
            'test.',
        ];
        $chunks = iterator_to_array($splitter->splitText($text));

        $this->assertCount(3, $chunks);
        $this->assertEquals($expectedChunks, $chunks);
    }
}
