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

namespace Devscast\Lugha\Retrieval\Embedder;

use Devscast\Lugha\Retrieval\Document;

/**
 * Interface EmbedderInterface.
 * Actually creates embeddings for documents using a model.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
interface EmbedderInterface
{
    /**
     * @template T of Document|string
     * @param array<T> $documents
     * @return array<T>
     */
    public function embedDocuments(array $documents, array $modelOptions = []): array;

    public function embedQuery(string $query, array $modelOptions = []): array;
}
