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

namespace Devscast\Lugha\Model\Completion;

use Devscast\Lugha\Model\Completion\Chat\History;
use Devscast\Lugha\Model\Completion\Prompt\PromptTemplate;

/**
 * Interface RagInterface.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
interface RetrievalAugmentedInterface
{
    public function augmentedCompletion(string $query, PromptTemplate $prompt): string;

    public function augmentedCompletionWithHistory(string $query, PromptTemplate $prompt, History $history): string;
}
