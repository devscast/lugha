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
use Devscast\Lugha\Model\Completion\Chat\Message;
use Devscast\Lugha\Model\Completion\Chat\Role;
use Devscast\Lugha\Model\Completion\Chat\ToolCalled;
use Devscast\Lugha\Provider\Provider;

/**
 * Class ToolBuilder.
 *
 * This class provides functionality for running tools, invoking their methods dynamically,
 * and retrieving the associated tool definitions. It allows for managing tools, invoking them
 * with the correct arguments, and handling exceptions that may arise during execution.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
abstract class ToolRunner
{
    /**
     * Builds a ToolReference instance for a given tool and provider.
     *
     * This method retrieves the tool's definition and creates a ToolReference, which includes the tool's name,
     * the tool instance itself, and its formatted definition based on the specified provider.
     *
     * @param object $tool The tool object to reference.
     * @param Provider $provider The provider to format the tool's definition for.
     *
     * @return ToolReference The generated ToolReference.
     */
    public static function build(object $tool, Provider $provider = Provider::OPENAI): ToolReference
    {
        $definition = self::getDefinition($tool);

        return new ToolReference($definition->name, $tool, $definition->format($provider));
    }

    /**
     * Runs a tool with the provided references.
     *
     * This method searches through the references, looking for the tool's definition that matches the tool's name.
     * If a match is found, it invokes the tool's method (via the `__invoke` method) with the arguments provided by the tool.
     * It returns a message containing the result of the tool invocation.
     *
     * @param ToolCalled $tool The tool to run, including its name and arguments.
     * @param array<ToolReference> $references The list of available tool references to search through.
     *
     * @return Message|null The result message, or null if no matching tool is found or an error occurs.
     *
     * @throws InvalidArgumentException If a tool does not have the required `__invoke` method.
     * @throws RuntimeException If an exception occurs during tool invocation.
     */
    public static function run(ToolCalled $tool, array $references): ?Message
    {
        foreach ($references as $reference) {
            if ($reference->definition['function']['name'] === $tool->name) {
                try {
                    if (! \method_exists($reference->instance, '__invoke')) {
                        throw new InvalidArgumentException(
                            \sprintf('%s must have an __invoke method.', $reference->instance::class)
                        );
                    }

                    // Invoke the tool method dynamically with the provided arguments.
                    $result = (string) ($reference->instance)(...$tool->arguments);
                    return new Message($result, Role::TOOL, $tool->id);
                } catch (\Throwable $e) {
                    throw new RuntimeException($e->getMessage(), previous: $e);
                }
            }
        }

        return null;
    }

    /**
     * Retrieves the definition of a tool, including its associated attributes.
     *
     * This method uses reflection to check if the tool has a `ToolDefinition` attribute and returns it.
     * If the tool does not have this attribute, an exception is thrown.
     *
     * @param object $tool The tool instance for which to retrieve the definition.
     *
     * @return ToolDefinition The definition of the tool.
     *
     * @throws InvalidArgumentException If the tool does not have the required `ToolDefinition` attribute.
     */
    private static function getDefinition(object $tool): ToolDefinition
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
