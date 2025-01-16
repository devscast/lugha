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

namespace Devscast\Lugha\Tests\Model\Tools\Stubs;

use Devscast\Lugha\Model\Completion\Tools\ToolDefinition;
use Devscast\Lugha\Model\Completion\Tools\ToolParameter;

#[ToolDefinition(
    name: 'get_weather',
    description: 'Get the weather for a location on a specific date.',
    parameters: [
        new ToolParameter('location', 'string', 'The location to get the weather for.', required: true),
        new ToolParameter('date', 'string', 'The date to get the weather for.', required: true),
    ]
)]
final readonly class WeatherProvider
{
    public function __invoke(string $location, string $date): string
    {
        return "The weather in {$location} on {$date} is sunny.";
    }
}
