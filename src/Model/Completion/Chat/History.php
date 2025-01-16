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

namespace Devscast\Lugha\Model\Completion\Chat;

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

    public function getHistory(bool $excludeSystemInstruction = false): array
    {
        return \array_map(
            callback: fn (Message $message) => $message->toArray(),
            array: $excludeSystemInstruction ?
                array_filter($this->messages, fn (Message $message) => $message->role !== Role::SYSTEM) :
                $this->messages
        );
    }

    /**
     * @todo to be replaced with `array_find` in PHP 8.4
     */
    public function getSystemInstruction(): ?Message
    {
        $instruction = null;
        foreach ($this->messages as $message) {
            if ($message->role === Role::SYSTEM) {
                $instruction = $message;
                break;
            }
        }

        return $instruction;
    }

    public function append(?Message $message): void
    {
        if ($message !== null) {
            $this->messages[] = $message;
        }
    }

    public function merge(self $history): void
    {
        $this->messages = \array_merge($this->messages, $history->messages);
    }
}
