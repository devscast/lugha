<?php

declare(strict_types=1);

namespace Devscast\Lugha\Provider\Response;

/**
 * Class CompletionResponse.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final readonly class CompletionResponse
{
    public function __construct(
        public string $model,
        public string $completion,
        public array $providerResponse = []
    ) {
    }
}
