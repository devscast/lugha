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

namespace Devscast\Lugha\Model\Embedding;

use Devscast\Lugha\Assert;

/**
 * Class Vector.
 * Represents a mathematical vector with various utility functions.
 *
 * This class provides fundamental operations for vectors, such as retrieving the dimension,
 * calculating the magnitude (L2 norm), and converting to string or JSON.
 * It enforces type safety by ensuring that the vector values are non-empty and all floats.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final readonly class Vector implements \Stringable, \JsonSerializable
{
    public function __construct(
        public array $values
    ) {
        Assert::notEmpty($this->values);
        Assert::allFloat($this->values);
    }

    #[\Override]
    public function __toString(): string
    {
        return \implode(',', $this->values);
    }

    /**
     * @param float[] $values
     */
    public static function from(array $values): self
    {
        return new self($values);
    }

    public function getDimension(): int
    {
        return \count($this->values);
    }

    /**
     * Compute the magnitude (L2 norm) of the vector.
     *
     * It represents the length of the vector in multi-dimensional space.
     *
     * @return float The magnitude of the vector.
     */
    public function getMagnitude(): float
    {
        return \sqrt(\array_sum(\array_map(fn (float $x): float => $x * $x, $this->values)));
    }

    #[\Override]
    public function jsonSerialize(): array
    {
        return [
            'values' => $this->values,
        ];
    }
}
