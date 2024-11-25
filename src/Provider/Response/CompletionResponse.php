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

namespace Devscast\Lugha\Provider\Response;

use Devscast\Lugha\Provider\Provider;

/**
 * Class CompletionResponse.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final readonly class CompletionResponse
{
    public function __construct(
        public Provider $provider,
        public string $model,
        public string $completion,
        public array $providerResponse = []
    ) {
    }
}
