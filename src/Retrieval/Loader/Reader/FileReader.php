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

namespace Ngandu\Lugha\Retrieval\Loader\Reader;

use Ngandu\Lugha\Exception\UnsupportedFileException;
use Symfony\Component\Filesystem\Path;

/**
 * Class FileReader.
 *
 * @author bernard-ng <bernard@ngandu.dev>
 */
final readonly class FileReader extends AbstractReader
{
    public const string SUPPORTED_EXTENSIONS_PATTERN = '/txt|pdf|docx?/';

    #[\Override]
    public function readContent(string $path, bool $skipExtensionCheck = false): string
    {
        foreach ($this->getSupportedReaders() as $reader) {
            if ($reader->supports($path)) {
                $this->ensureFileExists($path);
                return $reader->readContent($path, skipExtensionCheck: true);
            }
        }

        $extension = Path::getExtension($path, forceLowerCase: true);
        throw new UnsupportedFileException([$extension, self::SUPPORTED_EXTENSIONS_PATTERN]);
    }

    /**
     * @return AbstractReader[]
     */
    private function getSupportedReaders(): array
    {
        return [
            new PdfReader(),
            new RawReader(),
        ];
    }
}
