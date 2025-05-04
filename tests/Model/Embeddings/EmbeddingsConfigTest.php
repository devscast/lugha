<?php

declare(strict_types=1);

namespace Devscast\Lugha\Tests\Model\Embeddings;

use Devscast\Lugha\Exception\InvalidArgumentException;
use Devscast\Lugha\Model\Embeddings\EmbeddingsConfig;
use PHPUnit\Framework\TestCase;

/**
 * Class EmbeddingsConfigTest.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class EmbeddingsConfigTest extends TestCase
{
    public function testValidConfigWithAllParameters(): void
    {
        $config = new EmbeddingsConfig(
            'bert-base',
            512,
            'float',
            [
                'param1' => 'value1',
                'param2' => 'value2',
            ]
        );

        $this->assertSame('bert-base', $config->model);
        $this->assertSame(512, $config->dimensions);
        $this->assertSame('float', $config->encodingFormat);
        $this->assertSame([
            'param1' => 'value1',
            'param2' => 'value2',
        ], $config->additionalParameters);
    }

    public function testValidConfigWithDefaultEncodingFormat(): void
    {
        $config = new EmbeddingsConfig('bert-base', 512);

        $this->assertSame('bert-base', $config->model);
        $this->assertSame(512, $config->dimensions);
        $this->assertSame('float', $config->encodingFormat); // Default encoding format
        $this->assertEmpty($config->additionalParameters); // Default to an empty array
    }

    public function testValidConfigWithoutDimensions(): void
    {
        $config = new EmbeddingsConfig('bert-base', null, 'base64');

        $this->assertSame('bert-base', $config->model);
        $this->assertNull($config->dimensions);
        $this->assertSame('base64', $config->encodingFormat);
        $this->assertEmpty($config->additionalParameters);
    }

    public function testInvalidModelEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new EmbeddingsConfig('', 512, 'float');
    }

    public function testInvalidDimensionsNegative(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new EmbeddingsConfig('bert-base', -512, 'float');
    }

    public function testInvalidDimensionsZero(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new EmbeddingsConfig('bert-base', 0, 'float');
    }

    public function testInvalidEncodingFormat(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new EmbeddingsConfig('bert-base', 512, 'invalidFormat');
    }
}
