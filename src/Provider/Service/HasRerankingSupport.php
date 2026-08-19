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

namespace Ngandu\Lugha\Provider\Service;

use Ngandu\Lugha\Exception\InvalidArgumentException;
use Ngandu\Lugha\Exception\ServiceIntegrationException;
use Ngandu\Lugha\Model\Reranking\RerankingConfig;
use Ngandu\Lugha\Provider\Response\RerankingResponse;
use Ngandu\Lugha\Retrieval\Document;

/**
 * Interface HasRerankingSupport.
 *
 * @author bernard-ng <bernard@ngandu.dev>
 */
interface HasRerankingSupport
{
    /**
     * @param string $prompt The prompt to use for relevance scoring.
     * @param array<Document|string> $documents documents to be reranked.
     * @param RerankingConfig $config The configuration to use for reranking.
     *
     * @throws ServiceIntegrationException when any error occurs during the request.
     * @throws InvalidArgumentException when the prompt or documents are empty.
     */
    public function rerank(string $prompt, array $documents, RerankingConfig $config): RerankingResponse;
}
