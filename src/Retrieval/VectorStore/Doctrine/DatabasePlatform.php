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

use Devscast\Lugha\Model\Embeddings\Distance;
use Devscast\Lugha\Retrieval\VectorStore\Doctrine\Query\MariaDB;
use Devscast\Lugha\Retrieval\VectorStore\Doctrine\Query\PostgresSQL;
use Doctrine\DBAL\Platforms\MariaDb1010Platform;
use Doctrine\DBAL\Platforms\PostgreSQL120Platform;

/**
 * Class DatabasePlatform.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
enum DatabasePlatform: string
{
    case MariaDB = MariaDb1010Platform::class;
    case PostgresSQL = PostgreSQL120Platform::class;

    public function distanceFunctions(): array
    {
        return match ($this) {
            self::MariaDB => [
                Distance::L2->value => MariaDB\L2Distance::class,
                Distance::COSINE->value => MariaDB\CosineSimilarity::class,
            ],
            self::PostgresSQL => [
                Distance::L1->value => PostgresSQL\L1Distance::class,
                Distance::L2->value => PostgresSQL\L2Distance::class,
                Distance::COSINE->value => PostgresSQL\CosineSimilarity::class,
                Distance::INNER_PRODUCT->value => PostgresSQL\InnerProduct::class,
            ]
        };
    }
}
