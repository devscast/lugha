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
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
enum Distance
{
    case L1;
    case L2;
    case COSINE;
    case INNER_PRODUCT;
}
