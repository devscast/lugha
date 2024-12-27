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

namespace Devscast\Lugha;

use Devscast\Lugha\Exception\InvalidArgumentException;

/**
 * Class Assert.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class Assert extends \Webmozart\Assert\Assert
{
    #[\Override]
    protected static function reportInvalidArgument($message)
    {
        throw new InvalidArgumentException($message);
    }
}
