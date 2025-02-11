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

namespace Devscast\Lugha\Exception;

/**
 * Class UnsupportedFileException.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class FileNotFoundException extends \RuntimeException
{
    public function __construct(string $file, int $code = 0, ?\Throwable $previous = null)
    {
        $message = \sprintf('Failed to open stream: No such file %s', $file);
        parent::__construct($message, $code, $previous);
    }
}
