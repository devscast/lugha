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
use Devscast\Lugha\Model\Completion\Tools\ToolReference;
use Devscast\Lugha\Model\Completion\Tools\ToolRunner;
use Devscast\Lugha\Tests\Model\Tools\Stubs\WeatherProvider;
use PHPUnit\Framework\TestCase;

/**
 * Class ToolRunnerTest.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
class ToolRunnerTest extends TestCase
{
    public function testBuild(): void
    {
        $result = ToolRunner::build(new WeatherProvider());

        $this->assertInstanceOf(ToolReference::class, $result);
        $this->assertInstanceOf(WeatherProvider::class, $result->instance);
        $this->assertEquals('function', $result->definition['type']);
        $this->assertEquals('get_weather', $result->definition['function']['name']);
        $this->assertEquals('Get the weather for a location on a specific date.', $result->definition['function']['description']);
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

        ToolRunner::build($function);
    }
}
