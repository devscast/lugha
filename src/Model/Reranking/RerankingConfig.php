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

namespace Devscast\Lugha\Model\Reranking;

use Webmozart\Assert\Assert;

/**
 * Class RerankingConfig.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final readonly class RerankingConfig
{
    /**
     * @param string $model The model to use for generating the embeddings.
     * @param int $topK The number of top results to return.
     * @param array $additionalParameters Additional parameters to pass to the model.
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
