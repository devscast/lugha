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

namespace Devscast\Lugha\Provider\Response;

use Devscast\Lugha\Model\Reranking\RankedDocument;
use Devscast\Lugha\Provider\Provider;

/**
 * Class RerankingResponse.
 *
 * @author bernard-ng <bernard@devscast.tech>
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
