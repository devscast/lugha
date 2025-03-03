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

use Devscast\Lugha\Exception\InvalidArgumentException;
use Devscast\Lugha\Exception\ServiceIntegrationException;
use Devscast\Lugha\Model\Embedding\EmbeddingConfig;
use Devscast\Lugha\Provider\Response\EmbeddingResponse;

/**
 * Interface HasEmbeddingSupport.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
interface HasEmbeddingSupport
{
    /**
     * @param string $prompt The prompt to use for relevance scoring.
     * @param EmbeddingConfig $config The configuration to use for embeddings.
     *
     * @throws ServiceIntegrationException when any error occurs during the request.
     * @throws InvalidArgumentException when the prompt is empty.
     */
    public function embeddings(string $prompt, EmbeddingConfig $config): EmbeddingResponse;
}
