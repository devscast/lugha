<?php

declare(strict_types=1);

namespace Devscast\Lugha\Provider\Service\Client;

use Devscast\Lugha\Assert;
use Devscast\Lugha\Exception\ServiceIntegrationException;
use Devscast\Lugha\Model\Completion\Chat\History;
use Devscast\Lugha\Model\Completion\CompletionConfig;
use Devscast\Lugha\Provider\Provider;
use Devscast\Lugha\Provider\Response\CompletionResponse;
use Devscast\Lugha\Provider\Service\Client;
use Devscast\Lugha\Provider\Service\Common\OpenAICompatibilitySupport;
use Devscast\Lugha\Provider\Service\Common\ToolCallingSupport;
use Devscast\Lugha\Provider\Service\HasCompletionSupport;

/**
 * Class DeepSeekClient.
 *
 * @see https://api-docs.deepseek.com/api/deepseek-api
 * @see https://api-docs.deepseek.com/api/create-chat-completion
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class DeepSeekClient extends Client implements HasCompletionSupport
{
    use OpenAICompatibilitySupport;
    use ToolCallingSupport;

    protected const string BASE_URI = 'https://api.deepseek.com';

    protected Provider $provider = Provider::DEEPSEEK;

    #[\Override]
    public function completion(History|string $input, CompletionConfig $config, array $tools = []): CompletionResponse
    {
        Assert::notEmpty($input);
        $this->buildReferences($tools);

        try {
            $response = $this->http->request('POST', 'chat/completions', [
                'json' => [
                    'model' => $config->model,
                    'messages' => match (true) {
                        $input instanceof History => $input->getHistory(),
                        default => [[
                            'content' => $input,
                            'role' => 'user',
                        ]],
                    },
                    'tools' => $this->getToolDefinitions(),
                    'max_completion_tokens' => $config->maxTokens,
                    'temperature' => $config->temperature,
                    'top_p' => $config->topP,
                    'stop' => $config->stopSequences,
                    'presence_penalty' => $config->presencePenalty,
                    'frequency_penalty' => $config->frequencyPenalty,
                    ...$config->additionalParameters,
                ],
            ])->toArray();

            return $this->handleToolCalls($response, $config);
        } catch (\Throwable $e) {
            throw new ServiceIntegrationException('Unable to generate completion.', previous: $e);
        }
    }
}
