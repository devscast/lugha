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
class Metadata implements \Stringable
{
    public function __construct(
        public ?string $hash = null,
        public ?string $sourceType = null,
        public ?string $sourceName = null,
        public ?int $chunkNumber = null,
    ) {
    }

    /**
     * @throws \JsonException
     */
    #[\Override]
    public function __toString(): string
    {
        return json_encode([
            'hash' => $this->hash,
            'sourceType' => $this->sourceType,
            'sourceName' => $this->sourceName,
            'chunkNumber' => $this->chunkNumber,
        ], JSON_THROW_ON_ERROR);
    }

    /**
     * @throws \JsonException
     */
    public static function fromJson(string $metadata): self
    {
        /** @var array $data */
        $data = json_decode($metadata, true, flags: JSON_THROW_ON_ERROR);

        return new self(
            $data['hash'] ?? null,
            $data['sourceType'] ?? null,
            $data['sourceName'] ?? null,
            $data['chunkNumber'] ?? null,
        );
    }
}
