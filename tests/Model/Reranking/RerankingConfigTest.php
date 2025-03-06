<?php

declare(strict_types=1);

namespace Devscast\Lugha\Tests\Model\Reranking;

use Devscast\Lugha\Exception\InvalidArgumentException;
use Devscast\Lugha\Model\Reranking\RerankingConfig;
use PHPUnit\Framework\TestCase;

/**
 * Class RerankingConfigTest.
 *
 * @author bernard-ng <bernard@devscast.tech>
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
