<?php

declare(strict_types=1);

namespace Devscast\Lugha\Provider\Service\Client;

use Devscast\Lugha\Model\Embedding\EmbeddingConfig;
use Devscast\Lugha\Model\Reranking\RankedDocument;
use Devscast\Lugha\Model\Reranking\RerankingConfig;
use Devscast\Lugha\Provider\Response\EmbeddingResponse;
use Devscast\Lugha\Provider\Response\RerankingResponse;
use Devscast\Lugha\Provider\Service\AbstractClient;
use Devscast\Lugha\Provider\Service\HasEmbeddingSupport;
use Devscast\Lugha\Provider\Service\HasRerankingSupport;
use Devscast\Lugha\Provider\Service\IntegrationException;
use Devscast\Lugha\Retrieval\Document;
use Webmozart\Assert\Assert;

/**
 * Class VoyagerClient.
 *
 * @see https://docs.voyageai.com/docs/reranker
 * @see https://docs.voyageai.com/docs/embeddings
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class VoyagerClient extends AbstractClient implements HasRerankingSupport, HasEmbeddingSupport
{
    protected const string BASE_URI = 'https://api.voyageai.com/v1/';

    #[\Override]
    public function rerank(string $prompt, array $documents, RerankingConfig $config): RerankingResponse
    {
        Assert::notEmpty($prompt);
        Assert::allNotEmpty($documents);

        try {
            /** @var array{results: array<array{document: string, relevance_score: float}>} $response */
            $response = $this->http->request('POST', 'rerank', [
                'json' => [
                    'model' => $config->model,
                    'top_k' => $config->topK,
                    'documents' => array_map(
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
                model: $config->model,
                documents: array_map(
                    fn ($ranking) => new RankedDocument($ranking['document'], $ranking['relevance_score']),
                    $response['results']
                ),
                providerResponse: $this->config->providerResponse ? $response : [],
            );
        } catch (\Throwable $e) {
            throw new IntegrationException('Unable to rerank documents.', previous: $e);
        }
    }

    #[\Override]
    public function embeddings(string $prompt, EmbeddingConfig $config): EmbeddingResponse
    {
        Assert::notEmpty($prompt);

        try {
            /** @var array{embeddings: array<array<float>>} $response */
            $response = $this->http->request('POST', 'embeddings', [
                'json' => [
                    'model' => $config->model,
                    'input' => $prompt,
                    'input_type' => 'document',
                    ...$config->additionalParameters,
                ],
            ])->getContent();

            return new EmbeddingResponse($config->model, $response['embeddings'][0]);
        } catch (\Throwable $e) {
            throw new IntegrationException('Unable to generate embeddings.', previous: $e);
        }
    }
}
