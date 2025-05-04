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

use Devscast\Lugha\Exception\InvalidArgumentException;

/**
 * Class Distance.
 * Represents the distance metric to use when comparing vectors.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
enum Distance: string
{
    /**
     * L1 distance.
     * @see https://en.wikipedia.org/wiki/Taxicab_geometry
     */
    case L1 = 'L1_DISTANCE';

    /**
     * L2 distance.
     * @see https://en.wikipedia.org/wiki/Euclidean_distance
     */
    case L2 = 'L2_DISTANCE';

    /**
     * Cosine similarity.
     * @see https://en.wikipedia.org/wiki/Cosine_similarity
     */
    case COSINE = 'COSINE_SIMILARITY';

    /**
     * Inner product.
     * @see https://en.wikipedia.org/wiki/Dot_product
     */
    case INNER_PRODUCT = 'INNER_PRODUCT';

    /**
     * Compute the distance or similarity between two vectors.
     *
     * This method calculates the distance or similarity between two vectors
     * based on the selected distance metric. Supported metrics include:
     * - **Cosine Similarity** (`Distance::COSINE`)
     * - **Euclidean Distance (L2 Norm)** (`Distance::L2`)
     * - **Manhattan Distance (L1 Norm)** (`Distance::L1`)
     * - **Inner Product** (`Distance::INNER_PRODUCT`)
     *
     * @param Vector $a The first vector.
     * @param Vector $b The second vector.
     *
     * @throws InvalidArgumentException If the vectors do not have the same dimension.
     *
     * @return float The computed distance or similarity score.
     */
    public function compute(Vector $a, Vector $b): float
    {
        if ($a->getDimension() !== $b->getDimension()) {
            throw new InvalidArgumentException('Vectors must have the same dimension.');
        }

        return match ($this) {
            Distance::COSINE => $this->cosine($a, $b),
            Distance::L2 => $this->l2($a, $b),
            Distance::L1 => $this->l1($a, $b),
            Distance::INNER_PRODUCT => $this->dotProduct($a, $b)
        };
    }

    /**
     * Compute the cosine distance between two vectors.
     *
     * The cosine distance is derived from the cosine similarity, which measures the cosine
     * of the angle between two vectors in a multi-dimensional space.
     *
     * A cosine distance of **0** means the vectors are identical in direction,
     * while a cosine distance of **1** means they are completely dissimilar (orthogonal).
     *
     * @param Vector $a The first vector.
     * @param Vector $b The second vector.
     *
     * @return float The cosine distance between the two vectors, ranging from 0 (similar) to 1 (dissimilar).
     */
    private function cosine(Vector $a, Vector $b): float
    {
        $dotProduct = $this->dotProduct($a, $b);
        $x = $a->getMagnitude();
        $y = $b->getMagnitude();

        // Avoid division by zero in case one of the vectors has zero magnitude.
        if ($x * $y == 0) {
            return 0;
        }

        return 1 - $dotProduct / ($x * $y);
    }

    /**
     * Compute the Euclidean distance (L2 norm) between two vectors.
     *
     * This metric measures the straight-line distance between two points in a multi-dimensional space.
     * It is widely used in **machine learning, facial recognition, and clustering algorithms (e.g., K-Means)**.
     *
     * @param Vector $a The first vector.
     * @param Vector $b The second vector.
     *
     * @return float The Euclidean distance between the two vectors.
     */
    private function l2(Vector $a, Vector $b): float
    {
        return \sqrt(\array_sum(\array_map(fn (float $x, float $y): float => ($x - $y) ** 2, $a->values, $b->values)));
    }

    /**
     * Compute the Manhattan distance (L1 norm) between two vectors.
     *
     * This metric calculates the sum of the absolute differences between the corresponding components
     * of two vectors. It is commonly used in **image processing and optimization problems where movements
     * are restricted to orthogonal axes**.
     *
     * @param Vector $a The first vector.
     * @param Vector $b The second vector.
     *
     * @return float The Manhattan distance between the two vectors.
     */
    private function l1(Vector $a, Vector $b): float
    {
        return (float) \array_sum(\array_map(fn (float $x, float $y): float => \abs($x - $y), $a->values, $b->values));
    }

    /**
     * Compute the dot product (inner product) of two vectors.
     *
     * This metric measures the alignment between two vectors:
     * - A high positive value means the vectors point in a similar direction.
     * - A negative value means they point in opposite directions.
     * - A value close to zero indicates near-orthogonality.
     *
     * The dot product is commonly used in **information retrieval (search engines),
     * neural networks, and vector geometry**.
     *
     * @param Vector $a The first vector.
     * @param Vector $b The second vector.
     *
     * @return float The dot product of the two vectors.
     */
    private function dotProduct(Vector $a, Vector $b): float
    {
        return (float) \array_sum(\array_map(fn (float $x, float $y): float => $x * $y, $a->values, $b->values));
    }
}
