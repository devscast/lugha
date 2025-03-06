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

/**
 * Interface ChatInterface.
 * Interface for chat-based interactions with generative models.
 *
 * This interface defines the methods required for a system that supports chat-like completions,
 * including the ability to generate responses based on an input query and maintain conversation history.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
interface ChatInterface
{
    /**
     * Generate a response to the provided input, potentially utilizing tools.
     *
     * This method generates a completion based on a given input string. It may use external tools,
     * provided in the `$tools` argument, to augment the model's response.
     *
     * @param string $input The user input or query for which a response is generated.
     * @param array|null $tools Optional list of tools to assist in generating the response.
     *                           If no tools are provided, the model will generate the response based solely on the input.
     *
     * @throws ServiceIntegrationException when any error occurs during the request.
     *
     * @return string The generated response to the provided input.
     */
    public function completion(string $input, ?array $tools = null): string;

    /**
     * Generate a response to the provided input, considering the conversation history and potentially utilizing tools.
     *
     * This method generates a completion based on the input string and takes into account previous interactions
     * stored in the `$history` argument. The model uses this history to generate a more informed and coherent response.
     * Additionally, external tools may be used to enrich the response.
     *
     * @param string $input The user input or query for which a response is generated.
     * @param History $history The conversation history, providing context for the current interaction.
     * @param array|null $tools Optional list of tools to assist in generating the response.
     *                           If no tools are provided, the model will generate the response based on the input and history.
     *
     * @throws ServiceIntegrationException when any error occurs during the request.
     *
     * @return string The generated response considering both the input and history.
     */
    public function completionWithHistory(string $input, History $history, ?array $tools = null): string;
}
