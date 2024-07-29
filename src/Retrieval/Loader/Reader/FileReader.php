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

use Devscast\Lugha\Retrieval\Loader\Reader\Exception\UnsupportedFileException;
use Symfony\Component\Filesystem\Path;

/**
 * Class FileReader.
 *
 * @author bernard-ng <bernard@devscast.tech>
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
     * @return array<int, AbstractReader>
     */
    private function getSupportedReaders(): array
    {
        return [
            new TxtReader(),
            new PdfReader(),
        ];
    }
}
