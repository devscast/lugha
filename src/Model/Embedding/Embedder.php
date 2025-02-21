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

namespace Devscast\Lugha\Model\Embedding;

use Devscast\Lugha\Exception\ServiceIntegrationException;
use Devscast\Lugha\Provider\Service\HasEmbeddingSupport;
use Devscast\Lugha\Retrieval\Document;

/**
 * Class EmbeddingGenerator.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final readonly class Embedder implements EmbeddingInterface
{
    public function __construct(
        private HasEmbeddingSupport $client,
        private EmbeddingConfig $config
    ) {
    }

    /**
     * @throws ServiceIntegrationException
     */
    #[\Override]
    public function embedDocuments(iterable $documents): iterable
    {
        foreach ($documents as $document) {
            yield $this->embedDocument($document);
        }
    }

    /**
     * @throws ServiceIntegrationException
     */
    #[\Override]
    public function embedDocument(Document $document): Document
    {
        $document->embeddings = $this->client->embeddings(
            prompt: $document->content,
            config: $this->config
        )->embedding;

        return $document;
    }

    /**
     * @throws ServiceIntegrationException
     */
    #[\Override]
    public function embedQuery(string $query): array
    {
        return $this->client->embeddings($query, $this->config)->embedding;
    }
}
