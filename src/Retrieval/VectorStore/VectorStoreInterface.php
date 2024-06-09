<?php

declare(strict_types=1);

namespace Devscast\Lugha\Retrieval\VectorStore;

use Devscast\Lugha\Retrieval\Document;

/**
 * Interface VectorStoreInterface.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
interface VectorStoreInterface
{
    public function addDocument(Document $document): void;

    /**
     * @param array<Document> $documents
     */
    public function addDocuments(iterable $documents): void;

    /**
     * @return array<Document> $documents
     */
    public function similaritySearch(string $query, int $k = 4, Distance $distance = Distance::L2): array;

    /**
     * @return array<Document> $documents
     */
    public function similaritySearchByVector(array $embedding, int $k = 4, Distance $distance = Distance::L2): array;
}
