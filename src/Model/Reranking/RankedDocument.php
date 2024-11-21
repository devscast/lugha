<?php

declare(strict_types=1);

namespace Devscast\Lugha\Model\Reranking;

/**
 * Class RankedDocument.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class RankedDocument
{
    public function __construct(
        public string $content,
        public float $score
    ) {
    }
}
