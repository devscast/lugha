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

namespace Devscast\Lugha\Model\Completion;

use Devscast\Lugha\Exception\ServiceIntegrationException;
use Devscast\Lugha\Model\Completion\Chat\History;
use Devscast\Lugha\Model\Completion\Chat\Message;
use Devscast\Lugha\Model\Completion\Chat\Role;
use Devscast\Lugha\Model\Completion\Prompt\PromptTemplate;
use Devscast\Lugha\Provider\Service\HasCompletionSupport;
use Devscast\Lugha\Retrieval\VectorStore\VectorStoreInterface;

/**
 * Class RetrievalAugmentedChatter.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final readonly class RetrievalAugmentedChat implements RetrievalAugmentedInterface
{
    public function __construct(
        private HasCompletionSupport $client,
        private CompletionConfig $config,
        private VectorStoreInterface $vectorStore,
    ) {
    }

    /**
     * @throws ServiceIntegrationException
     */
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

    /**
     * @throws ServiceIntegrationException
     */
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

    private function createContext(string $query): string
    {
        $documents = $this->vectorStore->similaritySearch(
            query: $query,
            k: $this->config->similarityK,
            distance: $this->config->similarityDistance
        );

        $context = '';
        foreach ($documents as $document) {
            $context .= $document->content . "\n";
        }

        return $context;
    }
}
