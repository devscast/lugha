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

namespace Devscast\Lugha\Retrieval\Loader;

use Devscast\Lugha\Retrieval\Document;
use Devscast\Lugha\Retrieval\Splitter\SplitterInterface;

/**
 * Class DirectoryLoader.
 * Allows loading documents from a local directory.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
readonly class DirectoryLoader implements LoaderInterface
{
    public function __construct(
        public string $directory,
        public ?string $glob = null
    ) {
    }

    /**
     * @return iterable<Document>
     */
    public function load(): iterable
    {
        return [];
    }

    /**
     * @return iterable<Document>
     */
    public function loadAndSplit(SplitterInterface $splitter): iterable
    {
        return [];
    }
}
