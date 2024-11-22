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
use Devscast\Lugha\Provider\Provider;
use Devscast\Lugha\Provider\Response\CompletionResponse;
use Devscast\Lugha\Provider\Response\EmbeddingResponse;
use Devscast\Lugha\Provider\Service\Client;
use Devscast\Lugha\Provider\Service\HasCompletionSupport;
use Devscast\Lugha\Provider\Service\HasEmbeddingSupport;
use Devscast\Lugha\Provider\Service\IntegrationException;
use Webmozart\Assert\Assert;

/**
 * Class OpenAIClient.
 *
 * @see https://platform.openai.com/docs/api-reference/introduction
 * @see https://platform.openai.com/docs/api-reference/embeddings/create
 * @see https://platform.openai.com/docs/api-reference/chat/create
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class OpenAIClient extends Client implements HasEmbeddingSupport, HasCompletionSupport
{
    protected const string BASE_URI = 'https://api.openai.com/v1/';

    #[\Override]
    public function embeddings(string $prompt, EmbeddingConfig $config): EmbeddingResponse
    {
        Assert::notEmpty($prompt);

        try {
            /**
             * @var array{
             *     object: string,
             *     model: string,
             *     data: array<array{object: string, index: int, embedding: array<float>}>,
             *     usage: array{prompt_tokens: int, total_tokens: int}
             * } $response
             */
            $response = $this->http->request('POST', 'embeddings', [
                'json' => [
                    'model' => $config->model,
                    'input' => $prompt,
                    'dimensions' => $config->dimensions,
                    'encoding_format' => $config->encodingFormat,
                    ...$config->additionalParameters,
                ],
            ])->toArray();

            return new EmbeddingResponse(
                provider: Provider::OPENAI,
                model: $config->model,
                embedding: $response['data'][0]['embedding'],
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
             *     created: int,
             *     model: string,
             *     system_fingerprint: string,
             *     choices: array<array{
             *          index: int,
             *          logprobs: ?float,
             *          finish_reason: string,
             *          message: array{role: string, content: string}
             *     }>,
             *     usage: array{
             *          prompt_tokens: int,
             *          completion_tokens: int,
             *          total_tokens: int,
             *          completion_tokens_details: array{
             *              reasoning_tokens: int,
             *              accepted_prediction_tokens: int,
             *              rejected_prediction_tokens: int
             *          }
             *     }
             * } $response
             */
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
                    'max_completion_tokens' => $config->maxTokens,
                    'temperature' => $config->temperature,
                    'top_p' => $config->topP,
                    'stop' => $config->stopSequences,
                    'presence_penalty' => $config->presencePenalty,
                    'frequency_penalty' => $config->frequencyPenalty,
                    ...$config->additionalParameters,
                ],
            ])->toArray();

            return new CompletionResponse(
                provider: Provider::OPENAI,
                model: $config->model,
                completion: $response['choices'][0]['message']['content'],
                providerResponse: $this->config->providerResponse ? $response : [],
            );
        } catch (\Throwable $e) {
            throw new IntegrationException('Unable to generate completion.', previous: $e);
        }
    }
}
