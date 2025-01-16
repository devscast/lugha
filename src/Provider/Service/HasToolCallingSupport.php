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

namespace Devscast\Lugha\Provider\Service;

use Devscast\Lugha\Model\Completion\Chat\History;
use Devscast\Lugha\Model\Completion\Chat\ToolCalled;

/**
 * Interface HasToolCallingSupport.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
interface HasToolCallingSupport
{
    public function addTool(object $tool): self;

    public function callTool(ToolCalled $tool): History;
}
