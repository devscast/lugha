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
