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

use Devscast\Lugha\Provider\ProviderConfig;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\Retry\GenericRetryStrategy;
use Symfony\Component\HttpClient\RetryableHttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Class AbstractClient.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
abstract class AbstractClient
{
    protected const string BASE_URI = '';

    protected HttpClientInterface $http;

    public function __construct(
        protected ProviderConfig $config
    ) {
        $this->http = new RetryableHttpClient(
            client: HttpClient::createForBaseUri(
                baseUri: $this->config->baseUri ?? static::BASE_URI,
                defaultOptions: [
                    'auth_bearer' => $this->config->apiKey,
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'x-api-key' => $this->config->apiKey, // for compatibility with some providers
                    ],
                ]
            ),
            strategy: new GenericRetryStrategy(delayMs: 200),
            maxRetries: $this->config->maxRetries ?? 1
        );
    }
}
