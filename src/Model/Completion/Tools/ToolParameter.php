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

/**
 * Class ToolParameter.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final readonly class ToolParameter
{
    public const array SUPPORTED_TYPES = ['null', 'string', 'integer', 'number', 'boolean', 'array'];

    public function __construct(
        public string $name,
        public string|array $type,
        public ?string $description = null,
        public ?array $enum = null,
        public bool $required = false
    ) {
        Assert::notEmpty($name);
        Assert::notEmpty($description);

        match (\is_array($type)) {
            true => Assert::allInArray($type, self::SUPPORTED_TYPES),
            default => Assert::oneOf($type, self::SUPPORTED_TYPES)
        };
    }

    public function definition(): array
    {
        return \array_filter([
            'type' => $this->type,
            'enum' => $this->enum,
            'description' => $this->description,
        ], fn ($value) => $value !== null);
    }
}
