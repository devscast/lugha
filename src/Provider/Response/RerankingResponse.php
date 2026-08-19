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

namespace Ngandu\Lugha\Provider\Response;

use Ngandu\Lugha\Model\Reranking\RankedDocument;
use Ngandu\Lugha\Provider\Provider;

/**
 * Class RerankingResponse.
 *
 * @author bernard-ng <bernard@ngandu.dev>
 */
final readonly class RerankingResponse
{
    /**
     * @param array<RankedDocument> $documents
     */
    public function __construct(
        public Provider $provider,
        public string $model,
        public array $documents,
        public array $providerResponse = []
    ) {
    }
}
