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

use Devscast\Lugha\Model\Embeddings\Vector;

/**
 * Class Document.
 * Represents a document that can be embedded, indexed and searched.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
class Document implements \Stringable, \JsonSerializable
{
    public function __construct(
        public string $content,
        public ?Vector $embeddings = null,
        public Metadata $metadata = new Metadata(),
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
            Vector::from($document['embeddings']),
            Metadata::from($document['metadata']),
        );
    }

    public function hasEmbeddings(): bool
    {
        return $this->embeddings !== null;
    }

    #[\Override]
    public function jsonSerialize(): array
    {
        return [
            'content' => $this->content,
            'embeddings' => $this->embeddings,
            'metadata' => $this->metadata,
        ];
    }
}
