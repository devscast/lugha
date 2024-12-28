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

use Devscast\Lugha\Exception\FileNotFoundException;
use Devscast\Lugha\Exception\IOException;
use Devscast\Lugha\Exception\UnsupportedFileException;
use Devscast\Lugha\Retrieval\Loader\Reader\PdfReader;
use PHPUnit\Framework\TestCase;

/**
 * Class PdfReaderTest.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class PdfReaderTest extends TestCase
{
    private PdfReader $reader;

    #[\Override]
    protected function setUp(): void
    {
        $this->reader = new PdfReader();
        parent::setUp();
    }

    public function testReadContent(): void
    {
        $content = $this->reader->readContent(__DIR__ . '/../../../fixtures/document.pdf');
        $this->assertSame('helloworld', $content); // TODO: try to understand why whitespace is not taken into account
    }

    public function testCannotReadNonExistingFile(): void
    {
        $this->expectException(FileNotFoundException::class);
        $content = $this->reader->readContent(__DIR__ . '/../../../fixtures/does-not-exist.pdf');
    }

    public function testCannotReadFileWithoutReadPermission(): void
    {
        touch('/tmp/test.pdf');
        chmod('/tmp/test.pdf', 000);

        $this->expectException(IOException::class);
        $content = $this->reader->readContent('/tmp/test.pdf');
    }

    public function testReadContentOnUnsupportedFile(): void
    {
        $this->expectException(UnsupportedFileException::class);
        $content = $this->reader->readContent(__DIR__ . '/../../../fixtures/document.txt');
    }
}
