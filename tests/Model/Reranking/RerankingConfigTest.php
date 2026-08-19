<?php

declare(strict_types=1);

namespace Ngandu\Lugha\Tests\Model\Reranking;

use Ngandu\Lugha\Exception\InvalidArgumentException;
use Ngandu\Lugha\Model\Reranking\RerankingConfig;
use PHPUnit\Framework\TestCase;

/**
 * Class RerankingConfigTest.
 *
 * @author bernard-ng <bernard@ngandu.dev>
 */
final class RerankingConfigTest extends TestCase
{
    public function testValidConfig(): void
    {
        $config = new RerankingConfig('bert-base', 10, [
            'param1' => 'value1',
        ]);

        $this->assertSame('bert-base', $config->model);
        $this->assertSame(10, $config->topK);
        $this->assertSame([
            'param1' => 'value1',
        ], $config->additionalParameters);
    }

    public function testValidConfigWithDefaultAdditionalParameters(): void
    {
        $config = new RerankingConfig('bert-base', 10);

        $this->assertSame('bert-base', $config->model);
        $this->assertSame(10, $config->topK);
        $this->assertEmpty($config->additionalParameters); // Default to an empty array
    }

    public function testInvalidModelEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new RerankingConfig('', 10);
    }

    public function testInvalidTopKZero(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new RerankingConfig('bert-base', 0);
    }

    public function testInvalidTopKNegative(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new RerankingConfig('bert-base', -5);
    }
}
