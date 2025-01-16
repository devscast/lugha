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

use Devscast\Lugha\Assert;
use Devscast\Lugha\Provider\Provider;

/**
 * Class ToolDefinition.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
readonly class ToolDefinition
{
    /**
     * @param array<ToolParameter> $parameters
     */
    public function __construct(
        public string $name,
        public string $description,
        public array $parameters,
        public string $type = 'function',
        public bool $strict = true,
        public bool $additionalProperties = false
    ) {
        Assert::notEmpty($name);
        Assert::notEmpty($description);
        Assert::allIsInstanceOf($parameters, ToolParameter::class);
    }

    public function getRequiredParameters(): array
    {
        return \array_map(
            fn (ToolParameter $parameter) => $parameter->name,
            \array_filter($this->parameters, fn (ToolParameter $parameter) => $parameter->required)
        );
    }

    public function format(Provider $provider = Provider::OPENAI): array
    {
        return [
            'type' => $this->type,
            'function' => [
                'name' => $this->name,
                'description' => $this->description,
                'parameters' => [
                    'type' => 'object',
                    'required' => $this->getRequiredParameters(),
                    'properties' => $this->getProperties(),
                    'additionalProperties' => $this->additionalProperties,
                ],
                'strict' => $this->strict,
            ],
        ];
    }

    private function getProperties(): array|\stdClass
    {
        $properties = \array_combine(
            \array_map(fn ($property) => $property->name, $this->parameters),
            \array_map(fn ($property) => $property->definition(), $this->parameters)
        );

        // returning stdClass to avoid empty array in JSON
        return empty($properties) ? new \stdClass() : $properties;
    }
}
