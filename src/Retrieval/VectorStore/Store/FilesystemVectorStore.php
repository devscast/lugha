<?php

declare(strict_types=1);

namespace Devscast\Lugha\Retrieval\VectorStore\Store;

use Devscast\Lugha\Retrieval\Document;
use Devscast\Lugha\Retrieval\Embedder\EmbedderInterface;

/**
 * Class FilesystemVectorStore.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
class FilesystemVectorStore extends MemoryVectorStore
{
    public function __construct(
        private readonly string $path,
        EmbedderInterface $embedder,
    ) {
        parent::__construct($embedder);
        $this->read();
    }

    #[\Override]
    public function addDocument(Document $document): void
    {
        parent::addDocument($document);
        $this->write();
    }

    #[\Override]
    public function addDocuments(iterable $documents): void
    {
        parent::addDocuments($documents);
        $this->write();
    }

    private function read(): void
    {
        $content = file_get_contents($this->path);
        if ($content === false) {
            throw new \RuntimeException('Could not read the vector store file.');
        }

        /** @var array<array{content: string, embeddings: array, metadata: array}> $data */
        $data = json_decode($content, true);
        $this->pool = array_map(fn (array $document) => Document::from($document), $data);
    }

    private function write(): void
    {
        $data = json_encode($this->pool, JSON_PRETTY_PRINT);
        $written = file_put_contents($this->path, $data);

        if ($written === false) {
            throw new \RuntimeException('Could not write the vector store file.');
        }
    }
}
