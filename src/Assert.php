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

namespace Ngandu\Lugha;

use Ngandu\Lugha\Exception\InvalidArgumentException;

/**
 * Class Assert.
 *
 * @author bernard-ng <bernard@ngandu.dev>
 */
final class Assert extends \Webmozart\Assert\Assert
{
    #[\Override]
    protected static function reportInvalidArgument($message)
    {
        throw new InvalidArgumentException($message);
    }
}
