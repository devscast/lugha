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

namespace Devscast\Lugha\Tests\Retrieval\Loader\Reader;

use Devscast\Lugha\Retrieval\Loader\Reader\Exception\FileNotFoundException;
use Devscast\Lugha\Retrieval\Loader\Reader\Exception\UnreadableFileException;
use Devscast\Lugha\Retrieval\Loader\Reader\Exception\UnsupportedFileException;
use Devscast\Lugha\Retrieval\Loader\Reader\TxtReader;
use PHPUnit\Framework\TestCase;

/**
 * Class TxtReaderTest.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class TxtReaderTest extends TestCase
{
    private TxtReader $reader;

    #[\Override]
    protected function setUp(): void
    {
        $this->reader = new TxtReader();
        parent::setUp();
    }

    public function testReadContent(): void
    {
        $content = $this->reader->readContent(__DIR__ . '/../../../fixtures/document.txt');
        $this->assertSame('hello world', $content);
    }

    public function testCannotReadNonExistingFile(): void
    {
        $this->expectException(FileNotFoundException::class);
        $content = $this->reader->readContent(__DIR__ . '/../../../fixtures/does-not-exist.txt');
    }

    public function testCannotReadFileWithoutReadPermission(): void
    {
        touch('/tmp/test.txt');
        chmod('/tmp/test.txt', 000);

        $this->expectException(UnreadableFileException::class);
        $content = $this->reader->readContent('/tmp/test.txt');
    }

    public function testReadContentOnUnsupportedFile(): void
    {
        $this->expectException(UnsupportedFileException::class);
        $content = $this->reader->readContent(__DIR__ . '/../../../fixtures/document.pdf');
    }
}
