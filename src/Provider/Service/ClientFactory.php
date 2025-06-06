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

namespace Devscast\Lugha\Provider\Service;

use Devscast\Lugha\Exception\RuntimeException;
use Devscast\Lugha\Provider\Provider;
use Devscast\Lugha\Provider\ProviderConfig;

/**
 * Class ClientFactory.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
abstract readonly class ClientFactory
{
    public static function create(Provider $provider, ?ProviderConfig $config = null): Client
    {
        $config ??= new ProviderConfig();
        self::ensureApiKeyDefined($provider, $config);

        return match ($provider) {
            Provider::OLLAMA => new Client\OllamaClient($config),
            Provider::OPENAI => new Client\OpenAIClient($config),
            Provider::GOOGLE => new Client\GoogleClient($config),
            Provider::GITHUB => new Client\GithubClient($config),
            Provider::ANTHROPIC => new Client\AnthropicClient($config),
            Provider::VOYAGER => new Client\VoyagerClient($config),
            Provider::MISTRAL => new Client\MistralClient($config),
            Provider::DEEPSEEK => new Client\DeepSeekClient($config)
        };
    }

    private static function ensureApiKeyDefined(Provider $provider, ProviderConfig $config): void
    {
        if ($config->apiKey === null && $provider !== Provider::OLLAMA) {
            $apiKey = \getenv($provider->getEnvName());

            if (! \is_string($apiKey)) {
                throw new RuntimeException(
                    "Missing API key. Please define the {$provider->getEnvName()} environment variable."
                );
            }

            $config->apiKey = $apiKey;
        }
    }
}
