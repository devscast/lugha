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

namespace Devscast\Lugha\Retrieval;

/**
 * Class Document.
 * Represents a document that can be indexed and searched.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
class Document implements \Stringable
{
    public function __construct(
        public string $content,
        public array $embeddings = [],
        public ?Metadata $metadata = null,
    ) {
    }

    #[\Override]
    public function __toString(): string
    {
        return $this->content;
    }

    public static function from(array $document): self
    {
        return new self(
            $document['content'],
            $document['embeddings'],
            Metadata::from($document['metadata']),
        );
    }

    public function hasEmbeddings(): bool
    {
        return \count($this->embeddings) !== 0;
    }
}
