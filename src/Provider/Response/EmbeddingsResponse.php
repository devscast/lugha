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

namespace Ngandu\Lugha\Provider\Response;

use Ngandu\Lugha\Provider\Provider;

/**
 * Class EmbeddingsResponse.
 *
 * @author bernard-ng <bernard@ngandu.dev>
 */
final readonly class EmbeddingsResponse
{
    public function __construct(
        public Provider $provider,
        public string $model,
        public array $embeddings,
        public array $providerResponse = [],
    ) {
    }
}
