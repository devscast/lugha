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

namespace Devscast\Lugha\Retrieval\VectorStore\Doctrine\Entity;

use Devscast\Lugha\Model\Embeddings\Vector;
use Devscast\Lugha\Retrieval\Document;
use Devscast\Lugha\Retrieval\Metadata;
use Devscast\Lugha\Retrieval\VectorStore\Doctrine\Types\MetadataType;
use Devscast\Lugha\Retrieval\VectorStore\Doctrine\Types\VectorType;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Embeddings.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
class Embeddings extends Document
{
    #[ORM\Column(type: Types::TEXT)]
    public string $content;

    #[ORM\Column(type: VectorType::NAME, length: 1536)]
    public ?Vector $embeddings = null;

    #[ORM\Column(type: MetadataType::NAME)]
    public Metadata $metadata;
}
