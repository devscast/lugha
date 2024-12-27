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

namespace Devscast\Lugha\Model\Embedding;

use Devscast\Lugha\Assert;

/**
 * Class EmbeddingConfig.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final readonly class EmbeddingConfig
{
    /**
     * @param string $model The model to use for generating the embeddings.
     * @param int|null $dimensions The dimensionality of the embeddings to generate.
     * @param string $encodingFormat The encoding format to use for the embeddings.
     * @param array $additionalParameters Additional parameters to pass to the model.
     */
    public function __construct(
        public string $model,
        public ?int $dimensions = null,
        public string $encodingFormat = 'float',
        public array $additionalParameters = []
    ) {
        Assert::notEmpty($this->model);
        Assert::nullOrPositiveInteger($this->dimensions);
        Assert::oneOf($this->encodingFormat, ['float', 'base64']);
    }
}
