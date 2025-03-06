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
use Devscast\Lugha\Retrieval\Loader\Reader\AbstractReader;
use Devscast\Lugha\Retrieval\Loader\Reader\FileReader;
use Devscast\Lugha\Retrieval\Splitter\SplitterInterface;
use Devscast\Lugha\Retrieval\Splitter\TextSplitter;

/**
 * Interface LoaderInterface.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
interface LoaderInterface
{
    /**
     * @return iterable<Document>
     */
    public function load(AbstractReader $reader = new FileReader()): iterable;

    /**
     * @return iterable<Document>
     */
    public function loadAndSplit(
        SplitterInterface $splitter = new TextSplitter(),
        AbstractReader $reader = new FileReader()
    ): iterable;
}
