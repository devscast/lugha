<?php

declare(strict_types=1);

namespace Devscast\Lugha\Tests\Model\Completion;

use Devscast\Lugha\Exception\InvalidArgumentException;
use Devscast\Lugha\Model\Completion\CompletionConfig;
use Devscast\Lugha\Model\Embeddings\Distance;
use PHPUnit\Framework\TestCase;

/**
 * Class CompletionConfigTest.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class CompletionConfigTest extends TestCase
{
    public function testValidConfigWithAllParameters(): void
    {
        $config = new CompletionConfig(
            'gpt-3',
            0.7,
            100,
            0.9,
            10,
            0.5,
            -1.5,
            ['end', 'stop'],
            5,
            Distance::L2,
            [
                'param1' => 'value1',
            ]
        );

        $this->assertSame('gpt-3', $config->model);
        $this->assertSame(0.7, $config->temperature);
        $this->assertSame(100, $config->maxTokens);
        $this->assertSame(0.9, $config->topP);
        $this->assertSame(10, $config->topK);
        $this->assertSame(0.5, $config->frequencyPenalty);
        $this->assertSame(-1.5, $config->presencePenalty);
        $this->assertSame(['end', 'stop'], $config->stopSequences);
        $this->assertSame(5, $config->similarityK);
        $this->assertSame(Distance::L2, $config->similarityDistance);
        $this->assertSame([
            'param1' => 'value1',
        ], $config->additionalParameters);
    }

    public function testValidConfigWithDefaults(): void
    {
        $config = new CompletionConfig('gpt-3', similarityK: 3, similarityDistance: Distance::L2);

        $this->assertSame('gpt-3', $config->model);
        $this->assertNull($config->temperature);
        $this->assertNull($config->maxTokens);
        $this->assertNull($config->topP);
        $this->assertNull($config->topK);
        $this->assertNull($config->frequencyPenalty);
        $this->assertNull($config->presencePenalty);
        $this->assertNull($config->stopSequences);
        $this->assertSame(3, $config->similarityK);
        $this->assertSame(Distance::L2, $config->similarityDistance);
        $this->assertEmpty($config->additionalParameters);
    }

    public function testInvalidModelEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new CompletionConfig('', 0.7, 100);
    }

    public function testInvalidTemperatureOutOfRange(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new CompletionConfig('gpt-3', 2.5, 100);
    }

    public function testInvalidTemperatureNegative(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new CompletionConfig('gpt-3', -0.5, 100);
    }

    public function testInvalidMaxTokensNegative(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new CompletionConfig('gpt-3', 0.7, -100);
    }

    public function testInvalidTopPOutOfRange(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new CompletionConfig('gpt-3', 0.7, 100, 1.5);
    }

    public function testInvalidTopKNegative(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new CompletionConfig('gpt-3', 0.7, 100, 0.9, -10);
    }

    public function testInvalidFrequencyPenaltyOutOfRange(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new CompletionConfig('gpt-3', 0.7, 100, 0.9, 10, 3);
    }

    public function testInvalidPresencePenaltyOutOfRange(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new CompletionConfig('gpt-3', 0.7, 100, 0.9, 10, 0.5, 3);
    }

    public function testInvalidSimilarityKZeroOrNegative(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new CompletionConfig('gpt-3', 0.7, 100, 0.9, 10, 0.5, -1, null, 0, Distance::L2);
    }
}
