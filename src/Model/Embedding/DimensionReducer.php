<?php

declare(strict_types=1);

namespace Devscast\Lugha\Model\Embedding;

use Webmozart\Assert\Assert;

/**
 * Class DimensionReducer.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final readonly class DimensionReducer
{
    /**
     * Reduce the dimensions of a vector.
     *
     * @param array<int, float>|array<array<int, float>> $vector The vector to reduce.
     * @param int $dimensions The number of dimensions to reduce to.
     *
     * @return array The reduced vector.
     */
    public function reduce(array $vector, int $dimensions): array
    {
        Assert::positiveInteger($dimensions);

        if ($this->isMultiDimensional($vector)) {
            Assert::allIsArray($vector);
            Assert::allCount($vector, count($vector[0]));
            if (count($vector[0]) <= $dimensions) {
                return $vector;
            }

            $vector = array_map(fn (array $v): array => array_slice($v, 0, $dimensions), $vector);
        } else {
            Assert::allFloat($vector);
            if (count($vector) <= $dimensions) {
                return $vector;
            }

            $vector = array_slice($vector, 0, $dimensions);
        }

        return $this->normalizeL2($vector);
    }

    private function normalizeL2(array $vector): array
    {
        if ($this->isMultiDimensional($vector) === false) {
            $norm = $this->magnitude($vector);

            return match (true) {
                $norm == 0 => $vector,
                default => array_map(fn (float $x): float => $x / $norm, $vector)
            };
        }
        return array_map(function (array $v): array {
            $norm = $this->magnitude($v);
            return $norm == 0 ? $v : array_map(fn (float $x): float => $x / $norm, $v);
        }, $vector);
    }

    private function isMultiDimensional(array $vector): bool
    {
        return count($vector) !== count($vector, COUNT_RECURSIVE);
    }

    private function magnitude(array $vector): float
    {
        return sqrt(array_sum(array_map(fn (float $x): float => $x * $x, $vector)));
    }
}
