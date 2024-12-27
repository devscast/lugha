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

use Devscast\Lugha\Exception\UnreadableFileException;

/**
 * Class TxtReader.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final readonly class TxtReader extends AbstractReader
{
    public const string SUPPORTED_EXTENSIONS_PATTERN = '/txt/';

    #[\Override]
    public function readContent(string $path, bool $skipExtensionCheck = false): string
    {
        if ($skipExtensionCheck === false) {
            $this->ensureSupported($path);
            $this->ensureFileExists($path);
        }

        $content = file_get_contents($path);

        if ($content === false) {
            throw new UnreadableFileException($path);
        }

        return $content;
    }
}
