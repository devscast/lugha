<?php

declare(strict_types=1);

namespace Ngandu\Lugha\Provider\Service\Client;

use Ngandu\Lugha\Assert;
use Ngandu\Lugha\Exception\ServiceIntegrationException;
use Ngandu\Lugha\Model\Completion\Chat\History;
use Ngandu\Lugha\Model\Completion\CompletionConfig;
use Ngandu\Lugha\Provider\Provider;
use Ngandu\Lugha\Provider\Response\CompletionResponse;
use Ngandu\Lugha\Provider\Service\Client;
use Ngandu\Lugha\Provider\Service\Common\OpenAICompatibilitySupport;
use Ngandu\Lugha\Provider\Service\Common\ToolCallingSupport;
use Ngandu\Lugha\Provider\Service\HasCompletionSupport;

/**
 * Class DeepSeekClient.
 *
 * @see https://api-docs.deepseek.com/api/deepseek-api
 * @see https://api-docs.deepseek.com/api/create-chat-completion
 *
 * @author bernard-ng <bernard@ngandu.dev>
 */
final class DeepSeekClient extends Client implements HasCompletionSupport
{
    use OpenAICompatibilitySupport;
    use ToolCallingSupport;

    protected const string BASE_URI = 'https://api.deepseek.com';

    protected Provider $provider = Provider::DEEPSEEK;

    #[\Override]
    public function completion(History|string $input, CompletionConfig $config, ?array $tools = null): CompletionResponse
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
