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

use Devscast\Lugha\Assert;

/**
 * Class History.
 *
 * This class manages a collection of messages, allowing for the retrieval, modification, and addition of
 * messages within the history. The `History` class provides methods for filtering and appending messages,
 * as well as merging histories.
 *
 * The class allows for extending the message history by appending new messages or merging two histories.
 *
 * @package YourNamespace
 * @author bernard-ng <bernard@devscast.tech>
 */
class History
{
    /**
     * @param Message[] $messages An array of Message objects to initialize the history.
     */
    private function __construct(
        private array $messages = []
    ) {
        Assert::allIsInstanceOf($messages, Message::class);
    }

    /**
     * Create a History instance from an array of Message objects.
     *
     * @param Message[] $messages The array of Message objects to create the history from.
     *
     * @return History A new History instance.
     */
    public static function fromMessages(array $messages): self
    {
        return new self($messages);
    }

    /**
     * Get the history of messages as an array.
     *
     * Optionally, excludes system instruction messages.
     *
     * @param bool $excludeSystemInstruction Whether to exclude system instruction messages.
     *
     * @return array The array of message data.
     */
    public function getHistory(bool $excludeSystemInstruction = false): array
    {
        return \array_map(
            callback: fn (Message $message) => $message->toArray(),
            array: $excludeSystemInstruction ?
                \array_filter($this->messages, fn (Message $message) => $message->role !== Role::SYSTEM) :
                $this->messages
        );
    }

    /**
     * Get the system instruction message if it exists in the history.
     *
     * @todo to be replaced with `array_find` in PHP 8.4
     *
     * @return Message|null The system instruction message, or null if not found.
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

    /**
     * Append a new message to the history.
     *
     * @param Message|null $message The message to append. If null, nothing is added.
     */
    public function append(?Message $message): void
    {
        if ($message !== null) {
            $this->messages[] = $message;
        }
    }

    /**
     * Merge another history into the current history.
     *
     * @param History $history The history to merge.
     */
    public function merge(self $history): void
    {
        $this->messages = \array_merge($this->messages, $history->messages);
    }
}
