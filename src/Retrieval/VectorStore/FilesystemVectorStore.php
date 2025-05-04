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

use Devscast\Lugha\Exception\IOException;
use Devscast\Lugha\Model\Embeddings\EmbeddingsGeneratorInterface;
use Devscast\Lugha\Retrieval\Document;

/**
 * Class FilesystemVectorStore.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
class FilesystemVectorStore extends MemoryVectorStore
{
    public function __construct(
        private readonly string $path,
        EmbeddingsGeneratorInterface $embedding,
    ) {
        parent::__construct($embedding);
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
        $content = \file_get_contents($this->path);
        if ($content === false) {
            throw new IOException($this->path);
        }

        /** @var array $data */
        $data = \json_decode($content, true);
        $this->pool = \array_map(fn (array $document) => Document::from($document), $data);
    }

    /**
     * @throws  IOException if the file cannot be written to
     */
    private function write(): void
    {
        $data = \json_encode($this->pool, JSON_PRETTY_PRINT);
        $written = \file_put_contents($this->path, $data);

        if ($written === false) {
            throw new IOException($this->path);
        }
    }
}
