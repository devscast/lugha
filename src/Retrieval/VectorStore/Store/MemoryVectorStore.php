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

namespace Devscast\Lugha\Retrieval\VectorStore\Store;

use Devscast\Lugha\Assert;
use Devscast\Lugha\Model\Embedding\Distance;
use Devscast\Lugha\Model\Embedding\EmbeddingInterface;
use Devscast\Lugha\Model\Embedding\Vector;
use Devscast\Lugha\Retrieval\Document;
use Devscast\Lugha\Retrieval\VectorStore\VectorStoreInterface;

/**
 * Class MemoryVectorStore.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
class MemoryVectorStore implements VectorStoreInterface
{
    /**
     * @param Document[] $pool
     */
    public function __construct(
        protected readonly EmbeddingInterface $embedding,
        protected array $pool = []
    ) {
    }

    #[\Override]
    public function addDocument(Document $document): void
    {
        if ($document->hasEmbeddings() === false) {
            $document = $this->embedding->embedDocument($document);
        }

        $this->pool[] = $document;
    }

    #[\Override]
    public function addDocuments(iterable $documents): void
    {
        $documents = iterator_to_array($documents);

        /** @var Document $document */
        foreach ($documents as $index => $document) {
            if ($document->hasEmbeddings() === false) {
                $documents[$index] = $this->embedding->embedDocument($document);
            }
        }

        $this->pool = array_merge($this->pool, $documents);
    }

    #[\Override]
    public function similaritySearch(string $query, int $k = 4, Distance $distance = Distance::COSINE): array
    {
        $queryEmbeddings = $this->embedding->embedQuery($query);

        return $this->search($queryEmbeddings, $k, $distance);
    }

    #[\Override]
    public function similaritySearchByVector(Vector $vector, int $k = 4, Distance $distance = Distance::COSINE): array
    {
        return $this->search($vector, $k, $distance);
    }

    /**
     * @return array<int, Document>
     */
    private function search(Vector $vector, int $k, Distance $distance): array
    {
        $distances = [];

        foreach ($this->pool as $index => $document) {
            if ($document->hasEmbeddings()) {
                Assert::notNull($document->embeddings);
                $score = $distance->compute($vector, $document->embeddings);
                $distances[$index] = $score;
            }
        }

        asort($distances); // Sort by distance (ascending).
        $topK = \array_slice(\array_keys($distances), 0, $k, true);

        return \array_map(fn (int $index) => $this->pool[$index], $topK);
    }
}
