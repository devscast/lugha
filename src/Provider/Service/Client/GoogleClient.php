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
use Ngandu\Lugha\Provider\Service\HasCompletionSupport;
use Ngandu\Lugha\Provider\Service\HasEmbeddingsSupport;

/**
 * Class GoogleClient.
 *
 * @see https://ai.google.dev/api
 * @see https://ai.google.dev/gemini-api/docs/embeddings#curl
 *
 * @author bernard-ng <bernard@ngandu.dev>
 */
final class GoogleClient extends Client implements HasEmbeddingsSupport, HasCompletionSupport
{
    protected const string BASE_URI = 'https://generativelanguage.googleapis.com/v1beta/';

    protected Provider $provider = Provider::GOOGLE;

    #[\Override]
    public function embeddings(string $prompt, EmbeddingsConfig $config): EmbeddingsResponse
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

            return new EmbeddingsResponse(
                provider: $this->provider,
                model: $config->model,
                embeddings: $response['embedding']['values'],
                providerResponse: $this->config->providerResponse ? $response : [],
            );
        } catch (\Throwable $e) {
            throw new ServiceIntegrationException('Unable to generate embeddings.', previous: $e);
        }
    }

    #[\Override]
    public function completion(string|History $input, CompletionConfig $config, ?array $tools = null): CompletionResponse
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
