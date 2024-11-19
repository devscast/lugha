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
 * Class Message.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final readonly class Message implements \Stringable
{
    public function __construct(
        public string $content,
        public Role $role = Role::USER
    ) {
    }

    #[\Override]
    public function __toString(): string
    {
        return $this->content;
    }

    /**
     * Convert an array of messages to an array of Message objects.
     * @param array<array{content: string, role: string}> $data
     */
    public static function fromArray(array $data): array
    {
        return array_map(fn (array $message) => new self(
            content: $message['content'],
            role: Role::from($message['role'])
        ), $data);
    }

    /**
     * Convert a Message object to an array.
     * @return array{content: string, role: string}
     */
    public function toArray(): array
    {
        return [
            'content' => $this->content,
            'role' => $this->role->value,
        ];
    }
}
