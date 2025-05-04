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

namespace Devscast\Lugha\Retrieval\VectorStore;

use Devscast\Lugha\Model\Embeddings\Distance;
use Devscast\Lugha\Model\Embeddings\Vector;
use Devscast\Lugha\Retrieval\Document;

/**
 * Interface VectorStoreInterface.
 *
 * Represents a vector store that can be used to index and search documents.
 * This interface defines methods for adding documents to the store, performing similarity searches,
 * and searching using vectors. The `similaritySearch` and `similaritySearchByVector` methods
 * allow you to search for documents based on similarity, using either a text query or a vector representation.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
interface VectorStoreInterface
{
    /**
     * Add a single document to the vector store.
     *
     * @param Document $document The document to add to the store.
     */
    public function addDocument(Document $document): void;

    /**
     * Add multiple documents to the vector store.
     *
     * @param iterable<int, Document> $documents The documents to add to the store.
     */
    public function addDocuments(iterable $documents): void;

    /**
     * Perform a similarity search using a query string.
     *
     * This method will return the most similar documents to the query, based on a vector similarity measure.
     *
     * @param string $query The query string to search for.
     * @param int $k The number of documents to return (default is 4).
     * @param Distance $distance The distance metric to use for similarity calculation (default is `COSINE`).
     *
     * @return array<int, Document> The most similar documents to the query.
     */
    public function similaritySearch(string $query, int $k = 4, Distance $distance = Distance::COSINE): array;

    /**
     * Perform a similarity search using a vector representation of the query.
     *
     * This method allows searching by directly providing a vector for the query, instead of a string.
     * The vector is compared to the document vectors using a distance metric.
     *
     * @param Vector $vector The vector representing the query.
     * @param int $k The number of documents to return (default is 4).
     * @param Distance $distance The distance metric to use for similarity calculation (default is `COSINE`).
     *
     * @return array<int, Document> The most similar documents to the query vector.
     */
    public function similaritySearchByVector(
        Vector $vector,
        int $k = 4,
        Distance $distance = Distance::COSINE
    ): array;
}
