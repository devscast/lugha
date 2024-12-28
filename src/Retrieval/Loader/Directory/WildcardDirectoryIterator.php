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

use DirectoryIterator;
use FilterIterator;

/**
 * Class WildcardDirectoryIterator.
 *
 * @extends FilterIterator<int, string, RecursiveDirectoryIterator|DirectoryIterator>
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class WildcardDirectoryIterator extends FilterIterator
{
    private string $regex;

    public function __construct(string $path)
    {
        $recursive = false;

        if (\str_starts_with($path, '-R ')) {
            $recursive = true;
            $path = \substr($path, 3);
        }

        if (\preg_match('~/?([^/]*\*[^/]*)$~', $path, $matches)) { // matched wildcards in filename
            $path = \substr($path, 0, -\mb_strlen($matches[1]) - 1); // strip wildcards part from path
            $this->regex = '~^' . \str_replace('*', '.*', \str_replace('.', '\.', $matches[1])) . '$~'; // convert wildcards to regex

            if (! $path) {
                $path = '.'; // if no path given, we assume CWD;
            }
        }

        parent::__construct($recursive ? new RecursiveDirectoryIterator($path) : new DirectoryIterator($path));
    }

    /**
     * Checks for regex in current filename, or matches all if no regex specified
     */
    #[\Override]
    public function accept(): bool
    {
        /** @var RecursiveDirectoryIterator|DirectoryIterator $iterator */
        $iterator = $this->getInnerIterator();

        return (bool) \preg_match($this->regex, $iterator->getFilename());
    }
}
