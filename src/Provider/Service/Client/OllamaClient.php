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
use Devscast\Lugha\Model\Embeddings\EmbeddingsConfig;
use Devscast\Lugha\Provider\Provider;
use Devscast\Lugha\Provider\Response\CompletionResponse;
use Devscast\Lugha\Provider\Response\EmbeddingResponse;
use Devscast\Lugha\Provider\Service\Client;
use Devscast\Lugha\Provider\Service\Common\OpenAICompatibilitySupport;
use Devscast\Lugha\Provider\Service\Common\ToolCallingSupport;
use Devscast\Lugha\Provider\Service\HasCompletionSupport;
use Devscast\Lugha\Provider\Service\HasEmbeddingSupport;

/**
 * Class OllamaClient.
 *
 * @see https://github.com/ollama/ollama/blob/main/docs/api.md#generate-embeddings
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class OllamaClient extends Client implements HasEmbeddingSupport, HasCompletionSupport
{
    use ToolCallingSupport;
    use OpenAICompatibilitySupport;

    protected const string BASE_URI = 'http://localhost:11434/api/';

    protected Provider $provider = Provider::OLLAMA;

    #[\Override]
    public function embeddings(string $prompt, EmbeddingsConfig $config): EmbeddingResponse
    {
        Assert::notEmpty($prompt);

        try {
            /** @var array{embedding: array<float>} $response */
            $response = $this->http->request('POST', 'embeddings', [
                'json' => [
                    'model' => $config->model,
                    'prompt' => $prompt,
                    ...$config->additionalParameters,
                ],
            ])->toArray();

            return new EmbeddingResponse(
                provider: $this->provider,
                model: $config->model,
                embedding: $response['embedding']
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
            $response = $this->http->request('POST', 'chat', [
                'timeout' => -1,
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
                    'options' => [
                        'temperature' => $config->temperature,
                        'top_p' => $config->topP ?? 0.9,
                        'top_k' => $config->topK ?? 40,
                        'repeat_penalty' => $config->frequencyPenalty ?? 1.1,
                        'stop' => $config->stopSequences,
                    ],
                    ...$config->additionalParameters,
                ],
            ])->toArray();

            return $this->handleToolCalls($response, $config);
        } catch (\Throwable $e) {
            throw new ServiceIntegrationException('Unable to generate completion.', previous: $e);
        }
    }
}
