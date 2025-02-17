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

use Devscast\Lugha\Retrieval\Document;

/**
 * Interface EmbeddingInterface.
 * Actually creates embeddings for documents using a model.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
interface EmbeddingInterface
{
    /**
     * @param iterable<int, Document> $documents
     * @return iterable<int, Document>
     */
    public function embedDocuments(iterable $documents): iterable;

    public function embedDocument(Document $document): Document;

    public function embedQuery(string $query): array;
}
