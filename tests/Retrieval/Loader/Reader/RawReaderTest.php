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
use Devscast\Lugha\Retrieval\Loader\Reader\RawReader;
use PHPUnit\Framework\TestCase;

/**
 * Class RawReaderTest.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class RawReaderTest extends TestCase
{
    private RawReader $reader;

    #[\Override]
    protected function setUp(): void
    {
        $this->reader = new RawReader();
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

        $this->expectException(IOException::class);
        $content = $this->reader->readContent('/tmp/test.txt');
    }
}
