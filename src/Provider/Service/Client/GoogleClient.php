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

use Devscast\Lugha\Model\Embedding\EmbeddingConfig;
use Devscast\Lugha\Provider\Response\EmbeddingResponse;
use Devscast\Lugha\Provider\Service\AbstractClient;
use Devscast\Lugha\Provider\Service\HasEmbeddingSupport;
use Devscast\Lugha\Provider\Service\IntegrationException;
use Webmozart\Assert\Assert;

/**
 * Class OllamaClient.
 *
 * @see https://ai.google.dev/api
 * @see https://ai.google.dev/gemini-api/docs/embeddings#curl
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class GoogleClient extends AbstractClient implements HasEmbeddingSupport
{
    protected const string BASE_URI = 'https://generativelanguage.googleapis.com/v1beta/';

    #[\Override]
    public function embeddings(string $prompt, EmbeddingConfig $config): EmbeddingResponse
    {
        Assert::notEmpty($prompt);

        try {
            $response = $this->http->request('POST', "models/{$config->model}:embedContent?key={$this->config->apiKey}", [
                'json' => [
                    'model' => "models/{$config->model}",
                    'content' => [
                        'parts' => [
                            [
                                'text' => $prompt,
                            ],
                        ],
                    ],
                ],
            ])->toArray();

            return new EmbeddingResponse(
                model: $config->model,
                embedding: $response['embedding']['values'],
                providerResponse: [] // no special information to pass
            );
        } catch (\Throwable $e) {
            throw new IntegrationException('Unable to generate embeddings.', previous: $e);
        }
    }
}
