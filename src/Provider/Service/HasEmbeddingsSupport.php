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

namespace Ngandu\Lugha\Provider\Service;

use Ngandu\Lugha\Exception\InvalidArgumentException;
use Ngandu\Lugha\Exception\ServiceIntegrationException;
use Ngandu\Lugha\Model\Embeddings\EmbeddingsConfig;
use Ngandu\Lugha\Provider\Response\EmbeddingsResponse;

/**
 * Interface HasEmbeddingsSupport.
 *
 * @author bernard-ng <bernard@ngandu.dev>
 */
interface HasEmbeddingsSupport
{
    /**
     * @param string $prompt The prompt to use for relevance scoring.
     * @param EmbeddingsConfig $config The configuration to use for embeddings.
     *
     * @throws ServiceIntegrationException when any error occurs during the request.
     * @throws InvalidArgumentException when the prompt is empty.
     */
    public function embeddings(string $prompt, EmbeddingsConfig $config): EmbeddingsResponse;
}
