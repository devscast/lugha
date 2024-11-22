<?php

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
