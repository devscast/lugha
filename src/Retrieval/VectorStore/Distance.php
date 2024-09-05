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

namespace Devscast\Lugha\Retrieval\VectorStore;

/**
 * Class Distance.
 * Represents the distance metric to use when comparing vectors.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
enum Distance
{
    /**
     * L1 distance.
     * @see https://en.wikipedia.org/wiki/Taxicab_geometry
     */
    case L1;

    /**
     * L2 distance.
     * @see https://en.wikipedia.org/wiki/Euclidean_distance
     */
    case L2;

    /**
     * Cosine similarity.
     * @see https://en.wikipedia.org/wiki/Cosine_similarity
     */
    case COSINE;

    /**
     * Inner product.
     * @see https://en.wikipedia.org/wiki/Dot_product
     */
    case INNER_PRODUCT;

    public function compute(array $a, array $b): float
    {
        if (count($a) !== count($b)) {
            throw new \InvalidArgumentException('Vectors must have the same dimension.');
        }

        return match ($this) {
            Distance::COSINE => $this->cosine($a, $b),
            Distance::L2 => $this->l2($a, $b),
            Distance::L1 => $this->l1($a, $b),
            Distance::INNER_PRODUCT => $this->dotProduct($a, $b)
        };
    }

    private function cosine(array $a, array $b): float
    {
        $dotProduct = $this->dotProduct($a, $b);
        $x = $this->magnitude($a);
        $y = $this->magnitude($b);

        if ($x * $y == 0) {
            return 0;
        }

        return 1 - $dotProduct / ($x * $y);
    }

    private function l2(array $a, array $b): float
    {
        return sqrt(array_sum(array_map(fn (float $x, float $y): float => ($x - $y) ** 2, $a, $b)));
    }

    private function l1(array $a, array $b): float
    {
        return (float) array_sum(array_map(fn (float $x, float $y): float => abs($x - $y), $a, $b));
    }

    private function dotProduct(array $a, array $b): float
    {
        return (float) array_sum(array_map(fn (float $x, float $y): float => $x * $y, $a, $b));
    }

    private function magnitude(array $vector): float
    {
        return sqrt(array_sum(array_map(fn (float $x): float => $x * $x, $vector)));
    }
}
