<?php

declare(strict_types=1);

namespace Devscast\Lugha\Provider\Service;

use Devscast\Lugha\Provider\Provider;
use Devscast\Lugha\Provider\ProviderConfig;

/**
 * Class ClientFactory.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
abstract readonly class ClientFactory
{
    public static function create(Provider $provider, ProviderConfig $config): Client
    {
        if ($config->apiKey === null) {
            $apiKey = getenv("{$provider->value}_API_KEY");

            if (! is_string($apiKey)) {
                throw new \RuntimeException(
                    "Missing API key. Please define the {$provider->value}_API_KEY environment variable."
                );
            }

            $config->apiKey = $apiKey;
        }

        return match ($provider) {
            Provider::OLLAMA => new Client\OllamaClient($config),
            Provider::OPENAI => new Client\OpenAIClient($config),
            Provider::GOOGLE => new Client\GoogleClient($config),
            Provider::GITHUB => new Client\GithubClient($config),
            Provider::ANTHROPIC => new Client\AnthropicClient($config),
            Provider::VOYAGER => new Client\VoyagerClient($config),
            Provider::MISTRAL => new Client\MistralClient($config),
        };
    }
}
