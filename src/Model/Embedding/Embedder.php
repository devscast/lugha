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

    #[\Override]
    public function embedDocuments(iterable $documents): iterable
    {
        foreach ($documents as $document) {
            yield $this->embedDocument($document);
        }
    }

    #[\Override]
    public function embedDocument(Document $document): Document
    {
        $values = $this->client->embeddings($document->content, $this->config)->embedding;
        $document->embeddings = Vector::from($values);

        return $document;
    }

    #[\Override]
    public function embedQuery(string $query): Vector
    {
        $values = $this->client->embeddings($query, $this->config)->embedding;

        return Vector::from($values);
    }
}
