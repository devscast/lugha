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
use Devscast\Lugha\Provider\Service\HasCompletionSupport;
use Devscast\Lugha\Provider\Service\HasEmbeddingSupport;

/**
 * Class GoogleClient.
 *
 * @see https://ai.google.dev/api
 * @see https://ai.google.dev/gemini-api/docs/embeddings#curl
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class GoogleClient extends Client implements HasEmbeddingSupport, HasCompletionSupport
{
    protected const string BASE_URI = 'https://generativelanguage.googleapis.com/v1beta/';

    protected Provider $provider = Provider::GOOGLE;

    #[\Override]
    public function embeddings(string $prompt, EmbeddingConfig $config): EmbeddingResponse
    {
        Assert::notEmpty($prompt);

        try {
            $response = $this->http->request('POST', "models/{$config->model}:embedContent?key={$this->config->apiKey}", [
                'auth_bearer' => null, // Google uses API key in query instead of Bearer token (Come on Google!)
                'json' => [
                    'model' => "models/{$config->model}",
                    'content' => [
                        'parts' => [[
                            'text' => $prompt,
                        ]],
                    ],
                    ...$config->additionalParameters,
                ],
            ])->toArray();

            return new EmbeddingResponse(
                provider: $this->provider,
                model: $config->model,
                embedding: $response['embedding']['values'],
                providerResponse: $this->config->providerResponse ? $response : [],
            );
        } catch (\Throwable $e) {
            throw new ServiceIntegrationException('Unable to generate embeddings.', previous: $e);
        }
    }

    #[\Override]
    public function completion(string|History $input, CompletionConfig $config, array $tools = []): CompletionResponse
    {
        try {
            $response = $this->http->request('POST', "models/{$config->model}:generateContent?key={$this->config->apiKey}", [
                'auth_bearer' => null,
                'json' => [
                    ...$this->buildCompletionContents($input),
                    ...$config->additionalParameters,
                ],
            ])->toArray();

            return new CompletionResponse(
                provider: $this->provider,
                model: $config->model,
                completion: $response['candidates'][0]['content']['parts'][0]['text'],
                providerResponse: $this->config->providerResponse ? $response : [],
            );
        } catch (\Throwable $e) {
            throw new ServiceIntegrationException('Unable to generate completion.', previous: $e);
        }
    }

    private function buildCompletionContents(History|string $input): array
    {
        if (\is_string($input)) {
            return [
                'contents' => [
                    'parts' => [[
                        'text' => $input,
                    ]],
                ],
            ];
        }

        $systemInstruction = $input->getSystemInstruction();
        $messages = $input->getHistory(excludeSystemInstruction: true);
        $result = [];

        if ($systemInstruction) {
            $result['system_instruction'] = [
                'parts' => [[
                    'text' => $systemInstruction->content,
                ]],
            ];
        }

        $result['contents'] = \array_map(
            fn ($message) => [
                'role' => $message['role'] === 'assistant' ? 'model' : 'user',
                'parts' => [[
                    'text' => $message['content'],
                ]],
            ],
            $messages
        );

        return $result;
    }
}
