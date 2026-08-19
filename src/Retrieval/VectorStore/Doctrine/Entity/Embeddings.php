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

namespace Ngandu\Lugha\Retrieval\VectorStore\Doctrine\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Ngandu\Lugha\Model\Embeddings\Vector;
use Ngandu\Lugha\Retrieval\Document;
use Ngandu\Lugha\Retrieval\Metadata;
use Ngandu\Lugha\Retrieval\VectorStore\Doctrine\Types\MetadataType;
use Ngandu\Lugha\Retrieval\VectorStore\Doctrine\Types\VectorType;

/**
 * Class Embeddings.
 *
 * @author bernard-ng <bernard@ngandu.dev>
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
