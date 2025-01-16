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

namespace Devscast\Lugha\Model\Completion\Tools;

use Devscast\Lugha\Exception\InvalidArgumentException;
use Devscast\Lugha\Exception\RuntimeException;
use Devscast\Lugha\Model\Completion\Chat\History;
use Devscast\Lugha\Model\Completion\Chat\Message;
use Devscast\Lugha\Model\Completion\Chat\Role;
use Devscast\Lugha\Model\Completion\Chat\ToolCalled;
use Devscast\Lugha\Provider\Provider;

/**
 * Class ToolBuilder.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
abstract class ToolRunner
{
    public static function build(object $tool, Provider $provider = Provider::OPENAI): ToolReference
    {
        $definition = self::getToolDefinition($tool);

        return new ToolReference($definition->name, $tool, $definition->format($provider));
    }

    /**
     * @param array<ToolReference> $references
     */
    public static function run(ToolCalled $tool, array $references): History
    {
        $history = new History();

        foreach ($references as $reference) {
            if ($reference->definition['function']['name'] === $tool->name) {
                try {
                    if (! \method_exists($reference->instance, '__invoke')) {
                        throw new InvalidArgumentException(
                            \sprintf('%s must have an __invoke method.', $reference->instance::class)
                        );
                    }

                    $result = ($reference->instance)(...$tool->arguments);
                    $history->append(new Message($result, Role::TOOL, $tool->id));
                } catch (\Throwable $e) {
                    throw new RuntimeException($e->getMessage(), previous: $e);
                }
            }
        }

        return $history;
    }

    public static function getToolDefinition(object $tool): ToolDefinition
    {
        $class = new \ReflectionClass($tool);
        $attributes = $class->getAttributes(ToolDefinition::class);

        if (! isset($attributes[0])) {
            throw new InvalidArgumentException(
                \sprintf('%s does not have the required %s attribute.', $tool::class, ToolDefinition::class)
            );
        }

        return $attributes[0]->newInstance();
    }
}
