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

/**
 * Interface SplitterInterface.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
interface SplitterInterface
{
    /**
     * @return iterable<string>
     */
    public function splitText(string $text): iterable;

    /**
     * @param iterable<int, string> $splits
     * @return iterable<int, Document>
     */
    public function createDocuments(iterable $splits): iterable;

    /**
     * @return iterable<Document>
     */
    public function splitDocument(Document $document): iterable;
}
