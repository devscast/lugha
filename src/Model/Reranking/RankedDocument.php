<?php

/*
 * This file is part of the Lugha package.
 *
 * (c) Bernard Ngandu <bernard@ngandu.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Ngandu\Lugha\Model\Reranking;

/**
 * Class RankedDocument.
 *
 * @author bernard-ng <bernard@ngandu.dev>
 */
final class RankedDocument
{
    public function __construct(
        public string $content,
        public float $score
    ) {
    }
}
