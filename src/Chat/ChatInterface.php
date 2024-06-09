<?php

declare(strict_types=1);

namespace Devscast\Lugha\Chat;

use Devscast\Lugha\PromptTemplate;

/**
 * Interface ChatInterface.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
interface ChatInterface
{
    public function setSystemMessage(PromptTemplate|Message $message): void;

    public function completion(PromptTemplate|string $prompt, array $messages = []): string;
}
