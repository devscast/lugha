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

use RecursiveDirectoryIterator as NativeRecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * Class RealRecursiveDirectoryIterator.
 *
 * @extends RecursiveIteratorIterator<NativeRecursiveDirectoryIterator>
 * @see https://www.php.net/manual/en/class.recursivedirectoryiterator.php
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class RecursiveDirectoryIterator extends RecursiveIteratorIterator
{
    public function __construct(string $path)
    {
        parent::__construct(new NativeRecursiveDirectoryIterator($path));
    }
}
