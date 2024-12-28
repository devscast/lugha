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
use Devscast\Lugha\Provider\Provider;

/**
 * Class FunctionBuilder.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
abstract class FunctionBuilder
{
    /**
     * @param class-string<object>|object $function
     */
    public static function build(object|string $function, Provider $provider = Provider::OPENAI): array
    {
        $functionInfo = self::getFunctionInfo($function);

        return match ($provider) {
            Provider::OPENAI => self::buildOpenAICompatible($functionInfo),
            default => throw new InvalidArgumentException('Unsupported provider.')
        };
    }

    private static function buildOpenAICompatible(FunctionInfo $functionInfo): array
    {
        return [
            'type' => 'function',
            'function' => [
                'name' => $functionInfo->name,
                'description' => $functionInfo->description,
                'parameters' => [
                    'type' => 'object',
                    'required' => $functionInfo->getRequiredParameters(),
                    'properties' => \array_map(
                        fn (Parameter $parameter): array => $parameter->build(),
                        $functionInfo->parameters
                    ),
                ],
            ],
        ];
    }

    /**
     * @param class-string<object>|object $function
     */
    private static function getFunctionInfo(object|string $function): FunctionInfo
    {
        try {
            $class = new \ReflectionClass($function);
            $attributes = $class->getAttributes(FunctionInfo::class);

            if (! isset($attributes[0])) {
                $functionFqcn = \is_string($function) ? $function : $function::class;
                throw new InvalidArgumentException(
                    \sprintf('%s does not have the required %s attribute.', $functionFqcn, FunctionInfo::class)
                );
            }

            return $attributes[0]->newInstance();
        } catch (\ReflectionException $e) {
            throw new RuntimeException($e->getMessage(), previous: $e);
        }
    }
}
