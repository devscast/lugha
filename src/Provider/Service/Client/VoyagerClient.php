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

namespace Ngandu\Lugha\Provider\Service\Client;

use Ngandu\Lugha\Assert;
use Ngandu\Lugha\Exception\ServiceIntegrationException;
use Ngandu\Lugha\Model\Embeddings\EmbeddingsConfig;
use Ngandu\Lugha\Model\Reranking\RankedDocument;
use Ngandu\Lugha\Model\Reranking\RerankingConfig;
use Ngandu\Lugha\Provider\Provider;
use Ngandu\Lugha\Provider\Response\EmbeddingsResponse;
use Ngandu\Lugha\Provider\Response\RerankingResponse;
use Ngandu\Lugha\Provider\Service\Client;
use Ngandu\Lugha\Provider\Service\HasEmbeddingsSupport;
use Ngandu\Lugha\Provider\Service\HasRerankingSupport;
use Ngandu\Lugha\Retrieval\Document;

/**
 * Class VoyagerClient.
 *
 * @see https://docs.voyageai.com/docs/reranker
 * @see https://docs.voyageai.com/docs/embeddings
 *
 * @author bernard-ng <bernard@ngandu.dev>
 */
final class VoyagerClient extends Client implements HasRerankingSupport, HasEmbeddingsSupport
{
    protected const string BASE_URI = 'https://api.voyageai.com/v1/';

    protected Provider $provider = Provider::VOYAGER;

    #[\Override]
    public function rerank(string $prompt, array $documents, RerankingConfig $config): RerankingResponse
    {
        Assert::notEmpty($prompt);
        Assert::allNotEmpty($documents);

        try {
            $response = $this->http->request('POST', 'rerank', [
                'json' => [
                    'model' => $config->model,
                    'top_k' => $config->topK,
                    'documents' => \array_map(
                        fn (Document|string $document): string => match (true) {
                            $document instanceof Document => $document->content,
                            default => $document,
                        },
                        $documents
                    ),
                    'query' => $prompt,
                    ...$config->additionalParameters,
                ],
            ])->toArray();

            return new RerankingResponse(
                provider: $this->provider,
                model: $config->model,
                documents: \array_map(
                    fn ($ranking) => new RankedDocument($ranking['document'], $ranking['relevance_score']),
                    $response['results']
                ),
                providerResponse: $this->config->providerResponse ? $response : [],
            );
        } catch (\Throwable $e) {
            throw new ServiceIntegrationException('Unable to rerank documents.', previous: $e);
        }
    }

    #[\Override]
    public function embeddings(string $prompt, EmbeddingsConfig $config): EmbeddingsResponse
    {
        Assert::notEmpty($prompt);

        try {
            $response = $this->http->request('POST', 'embeddings', [
                'json' => [
                    'model' => $config->model,
                    'input' => $prompt,
                    'input_type' => 'document',
                    ...$config->additionalParameters,
                ],
            ])->toArray();

            return new EmbeddingsResponse(
                provider: $this->provider,
                model: $config->model,
                embeddings: $response['embeddings'][0]
            );
        } catch (\Throwable $e) {
            throw new ServiceIntegrationException('Unable to generate embeddings.', previous: $e);
        }
    }
}
