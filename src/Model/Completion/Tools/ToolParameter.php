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
    public const array SUPPORTED_TYPES = ['string', 'integer', 'number', 'boolean', 'array'];

    public function __construct(
        public string $name,
        public string $type,
        public ?string $description = null,
        public ?array $enum = null,
        public bool $required = false
    ) {
        Assert::notEmpty($name);
        Assert::oneOf($type, self::SUPPORTED_TYPES);
        Assert::notEmpty($description);
    }

    public function build(): array
    {
        return \array_filter([
            'name' => $this->name,
            'type' => $this->type,
            'enum' => $this->enum,
            'description' => $this->description,
        ], fn ($value) => $value !== null);
    }
}
