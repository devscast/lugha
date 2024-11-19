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

namespace Devscast\Lugha\Model\Chat\Completion;

/**
 * Class History.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
class History
{
    /**
     * @param Message[] $messages
     */
    private array $messages = [];

    /**
     * @param Message[] $messages
     */
    public function fromMessages(array $messages): void
    {
        $this->messages = $messages;
    }

    public function getHistory(): array
    {
        return $this->messages;
    }

    public function addUserMessage(string $message): void
    {
        $this->addMessage(new Message($message, Role::USER));
    }

    public function addAssistantMessage(string $message): void
    {
        $this->addMessage(new Message($message, Role::ASSISTANT));
    }

    public function addSystemMessage(string $message): void
    {
        $this->addMessage(new Message($message, Role::SYSTEM));
    }

    public function addToolMessage(string $message): void
    {
        $this->addMessage(new Message($message, Role::TOOL));
    }

    private function addMessage(Message $message): void
    {
        $this->messages[] = $message;
    }
}
