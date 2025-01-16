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

namespace Devscast\Lugha\Model\Completion\Chat;

use Devscast\Lugha\Assert;
use Devscast\Lugha\Exception\InvalidArgumentException;

/**
 * Class ToolCalled.
 * When a model decides to call a tool, it returns the name and input arguments.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final readonly class ToolCalled
{
    private function __construct(
        public string $id,
        public string $type,
        public string $name,
        public array $arguments
    ) {
        Assert::notEmpty($name);
    }

    public static function fromResponse(array $data): self
    {
        try {
            /** @var array<string, mixed> $arguments */
            $arguments = json_decode($data['function']['arguments'], true, flags: JSON_THROW_ON_ERROR);

            return new self($data['id'], $data['type'], $data['function']['name'], $arguments);
        } catch (\JsonException $e) {
            throw new InvalidArgumentException('Cannot instantiate ToolCalled from response', previous: $e);
        }
    }
}
