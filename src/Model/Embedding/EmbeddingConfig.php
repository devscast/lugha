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

use Webmozart\Assert\Assert;

/**
 * Class EmbeddingConfig.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final readonly class EmbeddingConfig
{
    /**
     * @param int|null $dimensions The dimensionality of the embeddings to generate.
     * @param string $encodingFormat The encoding format to use for the embeddings.
     */
    public function __construct(
        public ?int $dimensions = null,
        public string $encodingFormat = 'float',
    ) {
        Assert::nullOrPositiveInteger($this->dimensions);
        Assert::oneOf($this->encodingFormat, ['float', 'base64']);
    }
}
