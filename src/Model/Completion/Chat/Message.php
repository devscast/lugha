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
 * This class represents a message in a conversation or interaction. The message contains content (text),
 * a role (such as the sender or receiver of the message), and optional information related to tool calls.
 *
 * @package YourNamespace
 * @author bernard-ng <bernard@devscast.tech>
 */
final readonly class Message implements \Stringable
{
    /**
     * @param string $content The content of the message.
     * @param Role $role The role of the sender (e.g., user, assistant).
     * @param string|null $toolCallId The ID of the tool call (optional).
     * @param array|null $toolCalls The tool calls related to the message (optional).
     */
    public function __construct(
        public string $content,
        public Role $role = Role::USER,
        public ?string $toolCallId = null,
        public ?array $toolCalls = null
    ) {
        Assert::notEmpty($content);
    }

    /**
     * Convert the message content to a string.
     *
     * @return string The message content.
     */
    #[\Override]
    public function __toString(): string
    {
        return $this->content;
    }

    /**
     * Create a Message instance from an array response.
     *
     * @param array $message The message data array.
     *
     * @return Message A new Message instance.
     */
    public static function fromResponse(array $message): self
    {
        return new self(
            content: $message['content'],
            role: Role::from($message['role']),
            toolCallId: $message['tool_call_id'] ?? null,
            toolCalls: $message['tool_calls'] ?? null
        );
    }

    /**
     * Convert the message to an associative array.
     *
     * @return array The message represented as an array.
     */
    public function toArray(): array
    {
        return \array_filter([
            'content' => $this->content,
            'role' => $this->role->value,
            'tool_call_id' => $this->toolCallId,
            'tool_calls' => $this->toolCalls,
        ], fn ($value) => $value !== null);
    }
}
