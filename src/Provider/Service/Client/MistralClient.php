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
 * @see https://docs.mistral.ai/getting-started/quickstart/
 * @see https://docs.mistral.ai/api/#tag/embeddings/operation/embeddings_v1_embeddings_post
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class MistralClient extends AbstractClient implements HasEmbeddingSupport, HasCompletionSupport
{
    protected const string BASE_URI = 'https://api.mistral.ai/v1/';

    #[\Override]
    public function embeddings(string $prompt, EmbeddingConfig $config): EmbeddingResponse
    {
        Assert::notEmpty($prompt);

        try {
            /**
             * @var array{
             *     id: string,
             *     object: string,
             *     model: string,
             *     data: array<array{object: string, index: int, embedding: array<float>}>,
             *     usage: array{prompt_tokens: int, total_tokens: int, completion_tokens: int}
             * } $response
             */
            $response = $this->http->request('POST', 'embeddings', [
                'json' => [
                    'model' => $config->model,
                    'input' => $prompt,
                    'encoding_format' => $config->encodingFormat,
                    ...$config->additionalParameters,
                ],
            ])->toArray();

            return new EmbeddingResponse(
                model: $config->model,
                embedding: $response['data']['embedding'],
                providerResponse: $this->config->providerResponse ? $response : [],
            );
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
             *     id: string,
             *     object: string,
             *     model: string,
             *     created: int,
             *     choices: array<array{
             *          index: int,
             *          finish_reason: string,
             *          message: array{role: string, content: string}
             *     }>,
             *     usage: array{
             *          prompt_tokens: int,
             *          completion_tokens: int,
             *          total_tokens: int
             *     }
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
                    'max_tokens' => $config->maxTokens,
                    'temperature' => $config->temperature,
                    'top_p' => $config->topP,
                    'stop' => $config->stopSequences,
                    'presence_penalty' => $config->presencePenalty ?? 0,
                    'frequency_penalty' => $config->frequencyPenalty ?? 0,
                    ...$config->additionalParameters,
                ],
            ])->toArray();

            return new CompletionResponse(
                model: $config->model,
                completion: $response['choices'][0]['message']['content'],
                providerResponse: $this->config->providerResponse ? $response : [],
            );
        } catch (\Throwable $e) {
            throw new IntegrationException('Unable to generate completion.', previous: $e);
        }
    }
}
