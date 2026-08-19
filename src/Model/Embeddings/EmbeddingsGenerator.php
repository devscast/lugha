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

namespace Ngandu\Lugha\Model\Embeddings;

use Ngandu\Lugha\Provider\Service\HasEmbeddingsSupport;
use Ngandu\Lugha\Retrieval\Document;

/**
 * Class EmbeddingGenerator.
 *
 * @author bernard-ng <bernard@ngandu.dev>
 */
final readonly class EmbeddingsGenerator implements EmbeddingsGeneratorInterface
{
    public function __construct(
        private HasEmbeddingsSupport $client,
        private EmbeddingsConfig $config,
        private DimensionReducer $dimensionReducer = new DimensionReducer()
    ) {
    }

    #[\Override]
    public function embedDocuments(iterable $documents, ?int $dimensions = null): iterable
    {
        foreach ($documents as $document) {
            yield $this->embedDocument($document, $dimensions);
        }
    }

    #[\Override]
    public function embedDocument(Document $document, ?int $dimensions = null): Document
    {
        $values = $this->client->embeddings($document->content, $this->config)->embeddings;
        $vector = Vector::from($values);

        $document->embeddings = $dimensions !== null
            ? $this->dimensionReducer->reduce($vector, $dimensions)
            : $vector;

        return $document;
    }

    #[\Override]
    public function embedQuery(string $query, ?int $dimensions = null): Vector
    {
        $values = $this->client->embeddings($query, $this->config)->embeddings;
        $vector = Vector::from($values);

        return $dimensions !== null
            ? $this->dimensionReducer->reduce($vector, $dimensions)
            : $vector;
    }
}
