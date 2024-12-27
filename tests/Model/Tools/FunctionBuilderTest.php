<?php

/*
 * This file is part of the Lugha package.
 *
 * (c) Bernard Ngandu <bernard@devscast.tech>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Devscast\Lugha\Tests\Model\Tools;

use Devscast\Lugha\Exception\InvalidArgumentException;
use Devscast\Lugha\Model\Completion\Tools\FunctionBuilder;
use Devscast\Lugha\Tests\Model\Tools\Stubs\WeatherProvider;
use PHPUnit\Framework\TestCase;

/**
 * Class FunctionBuilderTest.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
class FunctionBuilderTest extends TestCase
{
    public function testBuildsOpenAICompatibleFunction(): void
    {
        $result = FunctionBuilder::build(WeatherProvider::class);

        $this->assertIsArray($result);
        $this->assertEquals('function', $result['type']);
        $this->assertEquals('get_weather', $result['function']['name']);
        $this->assertEquals('Get the weather for a location on a specific date.', $result['function']['description']);
    }

    public function testTrowsExceptionForMissingFunctionInfoAttribute(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $function = new class() {
            public function __invoke(string $location, string $date): string
            {
                return "The weather in {$location} on {$date} is sunny.";
            }
        };

        FunctionBuilder::build($function);
    }
}
