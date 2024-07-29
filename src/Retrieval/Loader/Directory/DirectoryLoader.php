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
    public function load(): iterable
    {
        /** @var RecursiveDirectoryIterator|\DirectoryIterator $file */
        foreach (new WildcardDirectoryIterator($this->path) as $file) {
            if ($file->isFile()) {
                yield new Document(
                    content: (string) file_get_contents($file->getPathname()),
                    metadata: new Metadata(
                        sourceType: 'file',
                        sourceName: $file->getFilename(),
                    ),
                );
            }
        }
    }

    /**
     * @return iterable<Document>
     */
    #[\Override]
    public function loadAndSplit(SplitterInterface $splitter): iterable
    {
        foreach ($this->load() as $document) {
            yield from $splitter->createDocuments($document);
        }
    }
}
