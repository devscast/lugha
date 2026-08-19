<?php

/*
 * This file is part of the Lugha package.
 *
 * (c) Bernard Ngandu <bernard@ngandu.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Ngandu\Lugha\Model\Completion;

use Ngandu\Lugha\Model\Completion\Chat\History;
use Ngandu\Lugha\Model\Completion\Chat\Message;
use Ngandu\Lugha\Model\Completion\Chat\Role;
use Ngandu\Lugha\Model\Completion\Prompt\PromptTemplate;
use Ngandu\Lugha\Provider\Service\HasCompletionSupport;
use Ngandu\Lugha\Retrieval\VectorStore\VectorStoreInterface;

/**
 * Class RetrievalAugmentedChatter.
 *
 * @author bernard-ng <bernard@ngandu.dev>
 */
final class RetrievalAugmentedChat implements RetrievalAugmentedInterface
{
    private array $context = [];

    public function __construct(
        private readonly HasCompletionSupport $client,
        private readonly CompletionConfig $config,
        private readonly VectorStoreInterface $vectorStore,
    ) {
    }

    #[\Override]
    public function augmentedCompletion(string $query, PromptTemplate $prompt): string
    {
        $prompt->setParameter(':CONTEXT', $this->createContext($query));

        $history = History::fromMessages([
            new Message((string) $prompt, Role::SYSTEM),
            new Message($query, Role::USER),
        ]);

        return $this->client->completion($history, $this->config)->completion;
    }

    #[\Override]
    public function augmentedCompletionWithHistory(string $query, PromptTemplate $prompt, History $history): string
    {
        $prompt->setParameter(':CONTEXT', $this->createContext($query));

        // TODO: not sure if a chat history can have multiple system messages
        // TODO: further investigation needed
        $history->append(new Message((string) $prompt, Role::SYSTEM));
        $history->append(new Message($query, Role::USER));

        return $this->client->completion($history, $this->config)->completion;
    }

    #[\Override]
    public function getContext(): array
    {
        return $this->context;
    }

    private function createContext(string $query): string
    {
        $this->context = $this->vectorStore->similaritySearch(
            query: $query,
            k: $this->config->similarityK,
            distance: $this->config->similarityDistance
        );

        $context = '';
        foreach ($this->context as $document) {
            $context .= $document->content . "\n";
        }

        return $context;
    }
}
