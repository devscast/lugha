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

namespace Devscast\Lugha\Retrieval\VectorStore\Doctrine;

use Devscast\Lugha\Model\Embeddings\Vector;
use Devscast\Lugha\Retrieval\Document;
use Devscast\Lugha\Retrieval\Metadata;

/**
 * Class DocumentEntity.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
class DocumentEntity extends Document
{
    public function __construct(
        public string $content,
        public ?Vector $embeddings = null,
        public Metadata $metadata = new Metadata(),
    ) {
        parent::__construct(
            $this->content,
            $this->embeddings,
            $this->metadata
        );
    }
}
