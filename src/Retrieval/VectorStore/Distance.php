<?php

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
