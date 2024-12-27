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
use Devscast\Lugha\Exception\UnsupportedFileException;
use Devscast\Lugha\Retrieval\Loader\Reader\FileReader;
use PHPUnit\Framework\TestCase;

/**
 * Class FileReaderTest.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class FileReaderTest extends TestCase
{
    private FileReader $reader;

    #[\Override]
    protected function setUp(): void
    {
        $this->reader = new FileReader();
        parent::setUp();
    }

    public function testReadContentWithTheBestReader(): void
    {
        $text = $this->reader->readContent(__DIR__ . '/../../../fixtures/document.txt');
        $pdf = $this->reader->readContent(__DIR__ . '/../../../fixtures/document.pdf');

        $this->assertSame('hello world', $text);
        $this->assertSame('helloworld', $pdf);
    }

    public function testCannotReadUnsupportedFile(): void
    {
        $this->expectException(UnsupportedFileException::class);
        $content = $this->reader->readContent(__DIR__ . '/../../../fixtures/document.docx');
    }

    public function testCannotReadNonExistingFile(): void
    {
        $this->expectException(FileNotFoundException::class);
        $content = $this->reader->readContent(__DIR__ . '/../../../fixtures/does-not-exist.pdf');
    }
}
