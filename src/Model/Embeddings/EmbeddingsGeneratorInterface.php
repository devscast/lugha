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

namespace Devscast\Lugha\Model\Embeddings;

use Devscast\Lugha\Exception\ServiceIntegrationException;
use Devscast\Lugha\Retrieval\Document;

/**
 * Interface EmbeddingsGeneratorInterface.
 * Handles the embedding of documents and queries using an embedding client.
 *
 * The `EmbeddingsGenerator` class interacts with a client that supports embeddings to transform textual
 * content into vector representations. These embeddings are useful for **machine learning,
 * natural language processing (NLP), and similarity searches**.
 *
 * Provide embedding functionalities for:
 * - Documents (`embedDocuments` and `embedDocument`)
 * - Queries (`embedQuery`)
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
interface EmbeddingsGeneratorInterface
{
    /**
     * Embed multiple documents and return them as an iterable of embedded documents.
     *
     * Each document is processed individually through the `embedDocument` method.
     *
     * @param iterable<Document> $documents A collection of documents to embed.
     *
     * @return iterable<Document> The embedded documents.
     *
     * @throws ServiceIntegrationException If an error occurs during the embedding process.
     */
    public function embedDocuments(iterable $documents): iterable;

    /**
     * Embed a single document by converting its content into a vector representation.
     * The embedding is retrieved from the configured embedding client and stored in the document.
     *
     * @param Document $document The document to embed.
     *
     * @return Document The document with its embedding vector set.
     *
     * @throws ServiceIntegrationException If an error occurs while obtaining the embedding.
     */
    public function embedDocument(Document $document): Document;

    /**
     * Embed a query string into a vector representation.
     *
     * This is useful for performing **semantic search or similarity-based retrieval**, where
     * a query is converted into a vector and compared against embedded documents.
     *
     * @param string $query The query string to embed.
     *
     * @return Vector The vector representation of the query.
     *
     * @throws ServiceIntegrationException If an error occurs while obtaining the embedding.
     */
    public function embedQuery(string $query): Vector;
}
