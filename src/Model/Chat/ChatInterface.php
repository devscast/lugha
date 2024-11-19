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

namespace Devscast\Lugha\Model\Chat;

use Devscast\Lugha\Model\Chat\Completion\History;
use Devscast\Lugha\Model\Chat\Completion\Message;
use Devscast\Lugha\Model\Chat\Prompt\PromptTemplate;

/**
 * Interface ChatInterface.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
interface ChatInterface
{
    public function setSystemMessage(PromptTemplate|Message $message): void;

    public function completion(PromptTemplate|string $prompt, ?History $history = null): string;
}
