<?php

/*
 * This file is part of the Lugha package.
 *
 * (c) Bernard Ngandu <bernard@ngandu.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Ngandu\Lugha\Retrieval\Loader;

use Ngandu\Lugha\Retrieval\Document;
use Ngandu\Lugha\Retrieval\Loader\Reader\AbstractReader;
use Ngandu\Lugha\Retrieval\Loader\Reader\FileReader;
use Ngandu\Lugha\Retrieval\Splitter\CharacterTextSplitter;
use Ngandu\Lugha\Retrieval\Splitter\SplitterInterface;

/**
 * Interface LoaderInterface.
 *
 * @author bernard-ng <bernard@ngandu.dev>
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
        SplitterInterface $splitter = new CharacterTextSplitter(),
        AbstractReader $reader = new FileReader()
    ): iterable;
}
