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

namespace Ngandu\Lugha\Provider\Service\Client;

use Ngandu\Lugha\Assert;
use Ngandu\Lugha\Exception\ServiceIntegrationException;
use Ngandu\Lugha\Model\Completion\Chat\History;
use Ngandu\Lugha\Model\Completion\CompletionConfig;
use Ngandu\Lugha\Model\Embeddings\EmbeddingsConfig;
use Ngandu\Lugha\Provider\Provider;
use Ngandu\Lugha\Provider\Response\CompletionResponse;
use Ngandu\Lugha\Provider\Response\EmbeddingsResponse;
use Ngandu\Lugha\Provider\Service\Client;
use Ngandu\Lugha\Provider\Service\Common\OpenAICompatibilitySupport;
use Ngandu\Lugha\Provider\Service\Common\ToolCallingSupport;
use Ngandu\Lugha\Provider\Service\HasCompletionSupport;
use Ngandu\Lugha\Provider\Service\HasEmbeddingsSupport;

/**
 * Class MistralClient.
 *
 * @see https://docs.mistral.ai/getting-started/quickstart/
 * @see https://docs.mistral.ai/api/#tag/embeddings/operation/embeddings_v1_embeddings_post
 *
 * @author bernard-ng <bernard@ngandu.dev>
 */
final class MistralClient extends Client implements HasEmbeddingsSupport, HasCompletionSupport
{
    use ToolCallingSupport;
    use OpenAICompatibilitySupport;

    protected const string BASE_URI = 'https://api.mistral.ai/v1/';

    protected Provider $provider = Provider::MISTRAL;

    #[\Override]
    public function embeddings(string $prompt, EmbeddingsConfig $config): EmbeddingsResponse
    {
        Assert::notEmpty($prompt);

        try {
            $response = $this->http->request('POST', 'embeddings', [
                'json' => [
                    'model' => $config->model,
                    'input' => $prompt,
                    'encoding_format' => $config->encodingFormat,
                    ...$config->additionalParameters,
                ],
            ])->toArray();

            return new EmbeddingsResponse(
                provider: $this->provider,
                model: $config->model,
                embeddings: $response['data'][0]['embedding'],
                providerResponse: $this->config->providerResponse ? $response : [],
            );
        } catch (\Throwable $e) {
            throw new ServiceIntegrationException('Unable to generate embeddings.', previous: $e);
        }
    }

    #[\Override]
    public function completion(History|string $input, CompletionConfig $config, ?array $tools = null): CompletionResponse
    {
        Assert::notEmpty($input);
        $this->buildReferences($tools);

        try {
            $response = $this->http->request('POST', 'chat/completions', [
                'json' => [
                    'stream' => false, // TODO: add support for streaming
                    'model' => $config->model,
                    'messages' => match (true) {
                        $input instanceof History => $input->getHistory(),
                        default => [[
                            'content' => $input,
                            'role' => 'user',
                        ]],
                    },
                    'tools' => $this->getToolDefinitions(),
                    'max_tokens' => $config->maxTokens,
                    'temperature' => $config->temperature,
                    'top_p' => $config->topP ?? 1,
                    'stop' => $config->stopSequences ?? [],
                    'presence_penalty' => $config->presencePenalty ?? 0,
                    'frequency_penalty' => $config->frequencyPenalty ?? 0,
                    ...$config->additionalParameters,
                ],
            ])->toArray();

            return $this->handleToolCalls($response, $config);
        } catch (\Throwable $e) {
            throw new ServiceIntegrationException('Unable to generate completion.', previous: $e);
        }
    }
}
