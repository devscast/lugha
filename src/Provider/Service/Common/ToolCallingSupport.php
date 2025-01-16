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

use Devscast\Lugha\Assert;
use Devscast\Lugha\Model\Completion\Chat\History;
use Devscast\Lugha\Model\Completion\Chat\ToolCalled;
use Devscast\Lugha\Model\Completion\Tools\ToolReference;
use Devscast\Lugha\Model\Completion\Tools\ToolRunner;

/**
 * Trait ToolCallingSupport.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
trait ToolCallingSupport
{
    /**
     * @param ToolReference[] $tools
     */
    private array $references = [];

    public function buildReferences(array $tools): void
    {
        foreach ($tools as $tool) {
            $this->references[] = ToolRunner::build($tool, $this->provider);
        }
    }

    public function getToolDefinitions(): array
    {
        return \array_map(
            fn (ToolReference $reference): array => $reference->definition,
            $this->references
        );
    }

    public function callTools(array $message): History
    {
        Assert::keyExists($message, 'tool_calls');

        $tools = \array_map(
            fn ($response) => ToolCalled::fromResponse($response),
            $message['tool_calls']
        );

        $history = new History();
        foreach ($tools as $tool) {
            $history->append(ToolRunner::run($tool, $this->references));
        }

        return $history;
    }
}
