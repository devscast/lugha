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

namespace Devscast\Lugha\Provider\Service\Client;

use Devscast\Lugha\Assert;
use Devscast\Lugha\Exception\ServiceIntegrationException;
use Devscast\Lugha\Model\Completion\Chat\History;
use Devscast\Lugha\Model\Completion\CompletionConfig;
use Devscast\Lugha\Model\Embedding\EmbeddingConfig;
use Devscast\Lugha\Provider\Provider;
use Devscast\Lugha\Provider\Response\CompletionResponse;
use Devscast\Lugha\Provider\Response\EmbeddingResponse;
use Devscast\Lugha\Provider\Service\Client;
use Devscast\Lugha\Provider\Service\Common\OpenAICompatibilitySupport;
use Devscast\Lugha\Provider\Service\Common\ToolCallingSupport;
use Devscast\Lugha\Provider\Service\HasCompletionSupport;
use Devscast\Lugha\Provider\Service\HasEmbeddingSupport;

/**
 * Class MistralClient.
 *
 * @see https://docs.mistral.ai/getting-started/quickstart/
 * @see https://docs.mistral.ai/api/#tag/embeddings/operation/embeddings_v1_embeddings_post
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class MistralClient extends Client implements HasEmbeddingSupport, HasCompletionSupport
{
    use ToolCallingSupport;
    use OpenAICompatibilitySupport;

    protected const string BASE_URI = 'https://api.mistral.ai/v1/';

    protected Provider $provider = Provider::MISTRAL;

    #[\Override]
    public function embeddings(string $prompt, EmbeddingConfig $config): EmbeddingResponse
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

            return new EmbeddingResponse(
                provider: $this->provider,
                model: $config->model,
                embedding: $response['data'][0]['embedding'],
                providerResponse: $this->config->providerResponse ? $response : [],
            );
        } catch (\Throwable $e) {
            throw new ServiceIntegrationException('Unable to generate embeddings.', previous: $e);
        }
    }

    #[\Override]
    public function completion(History|string $input, CompletionConfig $config, array $tools = []): CompletionResponse
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
