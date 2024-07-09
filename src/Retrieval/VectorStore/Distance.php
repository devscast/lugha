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
}
