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

namespace Ngandu\Lugha\Model\Completion\Tools;

/**
 * Class ToolReference.
 *
 * @internal
 * @author bernard-ng <bernard@ngandu.dev>
 */
final readonly class ToolReference
{
    public function __construct(
        public string $name,
        public object $instance,
        public array $definition
    ) {
    }
}
