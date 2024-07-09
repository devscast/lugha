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

use Devscast\Lugha\Retrieval\Document;

/**
 * Interface VectorStoreInterface.
 * Represents a vector store that can be used to index and search documents.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
interface VectorStoreInterface
{
    public function addDocument(Document $document): void;

    /**
     * @param iterable<Document> $documents
     */
    public function addDocuments(iterable $documents): void;

    /**
     * @return array<Document> $documents
     */
    public function similaritySearch(string $query, int $k = 4, Distance $distance = Distance::L2): array;

    /**
     * @return array<Document> $documents
     */
    public function similaritySearchByVector(array $embeddings, int $k = 4, Distance $distance = Distance::L2): array;
}
