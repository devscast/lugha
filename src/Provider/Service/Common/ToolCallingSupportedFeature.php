<?php

/*
 *  This file is part of the Lugha package.
 *
 * (c) Bernard Ngandu <bernard@devscast.tech>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Devscast\Lugha\Provider\Service\Common;

use Devscast\Lugha\Model\Completion\Chat\History;
use Devscast\Lugha\Model\Completion\Chat\ToolCalled;
use Devscast\Lugha\Model\Completion\Tools\ToolRunner;

/**
 * Trait ToolCallingSupportedFeature.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
trait ToolCallingSupportedFeature
{
    private array $references = [];

    public function addTool(object $tool): self
    {
        $this->references[] = ToolRunner::build($tool);

        return $this;
    }

    public function callTool(ToolCalled $tool): History
    {
        return ToolRunner::run($tool, $this->references);
    }
}
