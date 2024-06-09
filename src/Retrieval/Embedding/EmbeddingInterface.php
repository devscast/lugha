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

namespace Devscast\Lugha\Retrieval\Embedding;

use Devscast\Lugha\Retrieval\Document;

/**
 * Interface EmbeddingInterface.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
interface EmbeddingInterface
{
    /**
     * @template T of Document|string
     * @param array<T> $documents
     * @return array<T>
     */
    public function embedDocuments(array $documents): array;

    public function embedQuery(string $query): array;
}
