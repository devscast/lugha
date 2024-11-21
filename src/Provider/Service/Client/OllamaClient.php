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
 * @see https://github.com/ollama/ollama/blob/main/docs/api.md#generate-embeddings
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class OllamaClient extends AbstractClient implements HasEmbeddingSupport
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
                ],
            ])->getContent();

            return new EmbeddingResponse($config->model, $response['embedding']);
        } catch (\Throwable $e) {
            throw new IntegrationException('Unable to generate embeddings.', previous: $e);
        }
    }
}
