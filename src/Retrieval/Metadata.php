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
 * Class Metadata.
 * You can use this class to store metadata about a document.
 * add your own metadata fields as needed by extending this class.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
class Metadata
{
    public function __construct(
        public ?string $hash = null,
        public ?string $sourceType = null,
        public ?string $sourceName = null,
        public ?int $chunkNumber = null,
    ) {
    }

    public static function from(array $metadata): self
    {
        return new self(
            $metadata['hash'],
            $metadata['sourceType'],
            $metadata['sourceName'],
            $metadata['chunkNumber'],
        );
    }
}
