<?php

declare(strict_types=1);

namespace Devscast\Lugha\Provider\Service;

use Devscast\Lugha\Model\Reranking\RerankingConfig;
use Devscast\Lugha\Provider\Response\RerankingResponse;
use Devscast\Lugha\Retrieval\Document;

/**
 * Interface HasRerankingSupport.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
interface HasRerankingSupport
{
    /**
     * @param string $prompt The prompt to use for relevance scoring.
     * @param array<Document|string> $documents documents to be reranked.
     * @param RerankingConfig $config The configuration to use for reranking.
     *
     * @throws IntegrationException when any error occurs during the request.
     * @throws \InvalidArgumentException when the prompt or documents are empty.
     */
    public function rerank(string $prompt, array $documents, RerankingConfig $config): RerankingResponse;
}
