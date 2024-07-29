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

namespace Devscast\Lugha\Retrieval\Splitter;

use Devscast\Lugha\Retrieval\Document;
use Devscast\Lugha\Retrieval\Metadata;

/**
 * Class TextSplitter.
 * Represents a text splitter that can be used to split text into chunks.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final readonly class TextSplitter implements SplitterInterface
{
    public function __construct(
        public int $chunkSize = 200,
        public int $chunkOverlap = 0,
        public array $separators = ["\n\n", "\n", ' ', '']
    ) {
    }

    #[\Override]
    public function splitText(string $text): iterable
    {
        $currentPosition = 0;
        $textLength = strlen($text);

        while ($currentPosition < $textLength) {
            $chunkEnd = min($currentPosition + $this->chunkSize, $textLength);

            // Try to find the best separator within the chunk
            $bestSeparatorPos = -1;
            $bestSeparator = '';

            foreach ($this->separators as $separator) {
                $separatorPos = strrpos(substr($text, $currentPosition, $chunkEnd - $currentPosition), $separator);
                if ($separatorPos !== false && ($bestSeparatorPos === -1 || $separatorPos > $bestSeparatorPos)) {
                    $bestSeparatorPos = $separatorPos;
                    $bestSeparator = $separator;
                }
            }

            // If a separator is found, adjust chunk end
            if ($bestSeparatorPos !== -1) {
                $chunkEnd = $currentPosition + $bestSeparatorPos + strlen($bestSeparator);
            }

            // Create the chunk and add to the list
            $chunk = substr($text, $currentPosition, $chunkEnd - $currentPosition);
            yield $chunk;

            // Move the current position forward by chunk size minus overlap
            $currentPosition += $this->chunkSize - $this->chunkOverlap;

            // If the next chunk size is too small, break the loop
            if ($this->chunkOverlap > 0 && $currentPosition + $this->chunkSize - $this->chunkOverlap >= $textLength) {
                break;
            }
        }
    }

    #[\Override]
    public function createDocuments(Document|string $text): iterable
    {
        if ($text instanceof Document) {
            $text = $text->content;
        }

        /**
         * @var int $index
         */
        foreach ($this->splitText($text) as $index => $split) {
            yield new Document($split, metadata: new Metadata(
                hash: md5($split),
                chunkNumber: $index
            ));
        }
    }
}
