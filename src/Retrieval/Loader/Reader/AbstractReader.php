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

namespace Devscast\Lugha\Retrieval\Loader\Reader;

use Devscast\Lugha\Exception\FileNotFoundException;
use Devscast\Lugha\Exception\IOException;
use Devscast\Lugha\Exception\UnsupportedFileException;
use Symfony\Component\Filesystem\Path;

/**
 * Interface AbstractReader.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
abstract readonly class AbstractReader
{
    /**
     * Supported extensions regex pattern
     */
    public const string SUPPORTED_EXTENSIONS_PATTERN = '';

    /**
     * @throws UnsupportedFileException If the file extension is not supported and the check is not skipped.
     * @throws IOException When the content cannot be read for any other reason
     * @throws FileNotFoundException When the given file does not exist
     */
    abstract public function readContent(string $path, bool $skipExtensionCheck = false): string;

    final public function supports(string $path): bool
    {
        $extension = Path::getExtension($path, forceLowerCase: true);
        return (bool) \preg_match(static::SUPPORTED_EXTENSIONS_PATTERN, $extension);
    }

    final public function ensureSupported(string $path): void
    {
        $extension = Path::getExtension($path, forceLowerCase: true);
        if ($this->supports($path) === false) {
            throw new UnsupportedFileException([$extension, static::SUPPORTED_EXTENSIONS_PATTERN]);
        }
    }

    final public function ensureFileExists(string $path): void
    {
        if (\file_exists($path) === false) {
            throw new FileNotFoundException($path);
        }

        if (\is_readable($path) === false) {
            throw new IOException($path);
        }
    }
}
