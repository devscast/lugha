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

namespace Ngandu\Lugha\Model\Reranking;

use Ngandu\Lugha\Assert;
use Ngandu\Lugha\Exception\InvalidArgumentException;

/**
 * Class RerankingConfig.
 * Configuration class for reranking results.
 *
 * This class defines the settings required for reranking search results or embeddings,
 * specifying the model, the number of top results to return, and any additional parameters.
 *
 * @author bernard-ng <bernard@ngandu.dev>
 */
final readonly class RerankingConfig
{
    /**
     * @param string $model The model to use for generating the embeddings.
     * @param int $topK The number of top results to return.
     * @param array $additionalParameters Additional parameters to pass to the model.
     *
     * @throws InvalidArgumentException If the model name is empty.
     * @throws InvalidArgumentException If topK is not a positive integer.
     */
    public function __construct(
        public string $model,
        public int $topK,
        public array $additionalParameters = []
    ) {
        Assert::notEmpty($this->model);
        Assert::positiveInteger($this->topK);
    }
}
