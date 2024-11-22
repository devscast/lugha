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

use Devscast\Lugha\Model\Completion\Chat\History;
use Devscast\Lugha\Model\Completion\CompletionConfig;
use Devscast\Lugha\Model\Embedding\EmbeddingConfig;
use Devscast\Lugha\Provider\Response\CompletionResponse;
use Devscast\Lugha\Provider\Response\EmbeddingResponse;
use Devscast\Lugha\Provider\Service\AbstractClient;
use Devscast\Lugha\Provider\Service\HasCompletionSupport;
use Devscast\Lugha\Provider\Service\HasEmbeddingSupport;
use Devscast\Lugha\Provider\Service\IntegrationException;
use Webmozart\Assert\Assert;

/**
 * Class OllamaClient.
 *
 * @see https://github.com/ollama/ollama/blob/main/docs/api.md#generate-embeddings
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class OllamaClient extends AbstractClient implements HasEmbeddingSupport, HasCompletionSupport
{
    protected const string BASE_URI = 'http://localhost:11434/api/';

    #[\Override]
    public function embeddings(string $prompt, EmbeddingConfig $config): EmbeddingResponse
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
            ])->getContent();

            return new EmbeddingResponse($config->model, $response['embedding']);
        } catch (\Throwable $e) {
            throw new IntegrationException('Unable to generate embeddings.', previous: $e);
        }
    }

    #[\Override]
    public function completion(History|string $input, CompletionConfig $config): CompletionResponse
    {
        Assert::notEmpty($input);

        try {
            /**
             * @var array{
             *     model: string,
             *     created_at: string,
             *     message: array{role: string, content: string},
             *     done: bool,
             *     total_duration: int,
             *     load_duration: int,
             *     prompt_eval_count: int,
             *     prompt_eval_duration: int,
             *     eval_count: int,
             *     eval_duration: int
             * } $response
             */
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

            return new CompletionResponse(
                model: $config->model,
                completion: $response['message']['content'],
                providerResponse: $this->config->providerResponse ? $response : [],
            );
        } catch (\Throwable $e) {
            throw new IntegrationException('Unable to generate completion.', previous: $e);
        }
    }
}
