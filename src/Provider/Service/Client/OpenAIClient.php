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
 * @see https://platform.openai.com/docs/api-reference/introduction
 * @see https://platform.openai.com/docs/api-reference/embeddings/create
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class OpenAIClient extends AbstractClient implements HasEmbeddingSupport
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
}
