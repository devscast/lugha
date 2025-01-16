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
 * Class Message.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final readonly class Message implements \Stringable
{
    public function __construct(
        public string $content,
        public Role $role = Role::USER,
        public ?string $toolCallId = null,
    ) {
    }

    #[\Override]
    public function __toString(): string
    {
        return $this->content;
    }

    /**
     * Convert an array of messages to an array of Message objects.
     * @param array<array{content: string, role: string, tool_call_id?: string}> $data
     */
    public static function fromArray(array $data): array
    {
        return \array_map(fn (array $message) => new self(
            content: $message['content'],
            role: Role::from($message['role']),
            toolCallId: $message['tool_call_id'] ?? null
        ), $data);
    }

    /**
     * Convert a Message object to an array.
     * @return array{content: string, role: string, tool_call_id?: string}
     */
    public function toArray(): array
    {
        return \array_filter([
            'content' => $this->content,
            'role' => $this->role->value,
            'tool_call_id' => $this->toolCallId,
        ], fn ($value) => $value !== null);
    }
}
