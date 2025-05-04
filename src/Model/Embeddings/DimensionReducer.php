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
 * Class DimensionReducer.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final readonly class DimensionReducer
{
    /**
     * Reduce the dimensions of a vector while preserving its essential characteristics.
     *
     * This method ensures that the vector is truncated to the desired number of dimensions
     * while maintaining numerical stability through L2 normalization.
     *
     * @param Vector $vector The input vector to be reduced.
     * @param int $dimensions The target number of dimensions.
     *
     * @throws InvalidArgumentException If the dimensions parameter is not a positive integer.
     * @throws InvalidArgumentException If the vector contains non-float values.
     *
     * @return Vector The reduced and normalized vector.
     */
    public function reduce(Vector $vector, int $dimensions): Vector
    {
        Assert::positiveInteger($dimensions);
        if ($vector->getDimension() <= $dimensions) {
            return $vector;
        }

        $reducedValues = \array_slice($vector->values, 0, $dimensions);
        return $this->normalizeL2(Vector::from($reducedValues));
    }

    /**
     * Apply L2 normalization to a vector.
     *
     * L2 normalization scales the vector so that its magnitude (Euclidean norm) becomes 1,
     * unless the magnitude is zero, in which case the vector remains unchanged.
     *
     * @param Vector $vector The vector to normalize.
     *
     * @return Vector The L2-normalized vector.
     */
    private function normalizeL2(Vector $vector): Vector
    {
        $norm = $vector->getMagnitude();

        return match (true) {
            $norm == 0 => $vector, // Avoid division by zero.
            default => Vector::from(\array_map(fn (float $x): float => $x / $norm, $vector->values))
        };
    }
}
