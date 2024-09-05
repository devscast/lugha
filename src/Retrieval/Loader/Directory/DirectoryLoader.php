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

namespace Devscast\Lugha\Retrieval\Loader\Directory;

use Devscast\Lugha\Retrieval\Document;
use Devscast\Lugha\Retrieval\Loader\LoaderInterface;
use Devscast\Lugha\Retrieval\Loader\Reader\AbstractReader;
use Devscast\Lugha\Retrieval\Loader\Reader\FileReader;
use Devscast\Lugha\Retrieval\Metadata;
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
        public string $path
    ) {
    }

    /**
     * @return iterable<Document>
     */
    #[\Override]
    public function load(AbstractReader $reader = new FileReader()): iterable
    {
        /** @var RecursiveDirectoryIterator|\DirectoryIterator $file */
        foreach (new WildcardDirectoryIterator($this->path) as $file) {
            if ($file->isFile()) {
                $content = $reader->readContent($file->getPathname());
                $contentHash = md5($file->getPathname());

                yield new Document(
                    content: $content,
                    metadata: new Metadata(
                        hash: $contentHash,
                        sourceType: 'file',
                        sourceName: $file->getBasename(),
                    ),
                );
            }
        }
    }

    /**
     * @return iterable<Document>
     */
    #[\Override]
    public function loadAndSplit(SplitterInterface $splitter, AbstractReader $reader = new FileReader()): iterable
    {
        foreach ($this->load($reader) as $document) {
            yield from $splitter->splitDocument($document);
        }
    }
}
