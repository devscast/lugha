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

namespace Devscast\Lugha\Provider;

use Devscast\Lugha\Assert;

/**
 * Class ProviderConfig.
 * Configuration for a provider, including the API key, base URI, and other options.
 * A provider is a service that can be used for model inference via an API.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class ProviderConfig
{
    /**
     * @param string|null $apiKey The API key to use for the provider.
     * @param string|null $baseUri The base URI for the provider.
     * @param int|null $maxRetries The maximum number of retries to attempt in case of failure.
     * @param bool $providerResponse Whether to return the full provider's response along with the result.
     */
    public function __construct(
        #[\SensitiveParameter]
        public ?string $apiKey = null,
        public readonly ?string $baseUri = null,
        public readonly ?int $maxRetries = 2,
        public readonly bool $providerResponse = false,
    ) {
        Assert::nullOrNotEmpty($this->apiKey);
        Assert::nullOrNotEmpty($this->baseUri);
        Assert::positiveInteger($this->maxRetries);
    }
}
