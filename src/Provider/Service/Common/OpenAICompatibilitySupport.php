<?php

declare(strict_types=1);

namespace Devscast\Lugha\Provider\Service\Common;

use Devscast\Lugha\Exception\ServiceIntegrationException;
use Devscast\Lugha\Model\Completion\Chat\History;
use Devscast\Lugha\Model\Completion\Chat\Message;
use Devscast\Lugha\Model\Completion\CompletionConfig;
use Devscast\Lugha\Provider\Response\CompletionResponse;

/**
 * Trait OpenAICompatibilitySupport.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
trait OpenAICompatibilitySupport
{
    /**
     * @throws ServiceIntegrationException
     */
    public function handleToolCalls(array $response, CompletionConfig $config): CompletionResponse
    {
        $message = $this->getLastMessage($response);
        if (! isset($message['tool_calls'])) {
            return $this->handleCompletion($response, $config);
        }

        $history = History::fromMessages([Message::fromResponse($message)]);
        $history->merge($this->callTools($message['tool_calls']));

        return $this->completion($history, $config);
    }

    public function handleCompletion(array $response, CompletionConfig $config): CompletionResponse
    {
        return new CompletionResponse(
            provider: $this->provider,
            model: $config->model,
            completion: $this->getLastMessage($response)['content'],
            providerResponse: $this->config->providerResponse ? $response : [],
        );
    }
}
