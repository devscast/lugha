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
        public ?array $toolCalls = null
    ) {
        Assert::notEmpty($content);
    }

    #[\Override]
    public function __toString(): string
    {
        return $this->content;
    }

    public static function fromResponse(array $message): self
    {
        return new self(
            content: $message['content'],
            role: Role::from($message['role']),
            toolCallId: $message['tool_call_id'] ?? null,
            toolCalls: $message['tool_calls'] ?? null
        );
    }

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
