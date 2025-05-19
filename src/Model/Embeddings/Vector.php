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

namespace Devscast\Lugha\Model\Embeddings;

use Devscast\Lugha\Assert;
use Devscast\Lugha\Exception\InvalidArgumentException;

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
        return \sprintf('[%s]', \implode(',', $this->values));
    }

    public function toString(): string
    {
        return (string) $this;
    }

    public function to32Bit(): string
    {
        $binary = '';
        foreach ($this->values as $float) {
            $binary .= pack('f', $float); // 'f' = 32-bit float
        }

        /** @var array $unpacked */
        $unpacked = unpack('H*', $binary);

        /** @var string $hex */
        $hex = $unpacked[1];

        return sprintf('%s', $hex);
    }

    /**
     * @param float[] $values
     */
    public static function from(array $values): self
    {
        return new self($values);
    }

    /**
     * Create a Vector instance from a string representation.
     *
     * The string should be in the format "[x1,x2,x3,...,xn]",
     * where x1, x2, ..., xn are float values.
     *
     * @throws InvalidArgumentException if the string is empty or not in the correct format.
     *
     * @return self A new Vector instance.
     */
    public static function fromString(string $value): self
    {
        Assert::notEmpty($value, 'Value cannot be empty');
        Assert::regex($value, '/^\[(\d+(\.\d+)?(,\s*\d+(\.\d+)?)*)?\]$/', 'Invalid vector string format');

        $values = \array_map(
            callback: \floatval(...),
            array: \explode(
                separator: ',',
                string: \str_replace(['[', ']'], '', $value))
        );

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
        return $this->values;
    }
}
