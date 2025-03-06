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
use Devscast\Lugha\Model\Completion\Prompt\PromptTemplate;
use Devscast\Lugha\Retrieval\Document;

/**
 * Interface RetrievalAugmentedInterface.
 * Interface for retrieval-augmented generation (RAG) systems.
 *
 * This interface defines the methods that a retrieval-augmented model must implement.
 * Retrieval-augmented generation combines **retrieval** of relevant documents with **generative models**
 * to provide more context-aware, accurate responses to user queries.
 *
 * The methods here support:
 * - Generating responses with augmented context from documents.
 * - Keeping track of query history for improved context.
 * - Retrieving relevant context for a given query.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
interface RetrievalAugmentedInterface
{
    /**
     * Generate a completion based on the provided query and prompt template.
     * The model uses relevant context documents for augmented generation.
     *
     * Your prompt template should include placeholder for the context, :CONTEXT
     *
     * @param string $query The query or prompt for which a response is generated.
     * @param PromptTemplate $prompt The template that structures the prompt for generation.
     *
     * @throws ServiceIntegrationException when any error occurs during the request.
     *
     * @return string The generated response based on the query and prompt.
     */
    public function augmentedCompletion(string $query, PromptTemplate $prompt): string;

    /**
     * Generate a completion based on the provided query, prompt template, and history.
     * The model uses both relevant context documents and previous history to generate a more informed response.
     *
     * Your prompt template should include placeholder for the context, :CONTEXT
     *
     * @param string $query The query or prompt for which a response is generated.
     * @param PromptTemplate $prompt The template that structures the prompt for generation.
     * @param History $history The history of previous interactions to provide more context.
     *
     * @throws ServiceIntegrationException when any error occurs during the request.
     *
     * @return string The generated response based on the query, prompt, and history.
     */
    public function augmentedCompletionWithHistory(string $query, PromptTemplate $prompt, History $history): string;

    /**
     * Retrieve the context documents that are relevant to the current query.
     * This method helps fetch the documents that will be used for augmented generation.
     *
     * @return Document[] An array of documents that serve as context for the query.
     */
    public function getContext(): array;
}
