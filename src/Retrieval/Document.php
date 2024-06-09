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
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
class Document implements \Stringable
{
    public function __construct(
        public string $content,
        public array $embeddings = [],
        public ?DocumentMetadata $metadata = null,
    ) {
    }

    public function __toString(): string
    {
        return $this->content;
    }
}
