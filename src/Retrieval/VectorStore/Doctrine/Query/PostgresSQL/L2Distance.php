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

namespace Devscast\Lugha\Retrieval\VectorStore\Doctrine\Query\PostgresSQL;

use Devscast\Lugha\Retrieval\VectorStore\Doctrine\Query\DistanceFunctionNode;
use Doctrine\ORM\Query\SqlWalker;

/**
 * Class L2Distance.
 *
 * @see https://github.com/pgvector/pgvector?tab=readme-ov-file#querying
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class L2Distance extends DistanceFunctionNode
{
    #[\Override]
    public function getSql(SqlWalker $sqlWalker): string
    {
        return sprintf(
            '%s <-> %s',
            $this->firstArgument->dispatch($sqlWalker),
            $this->secondArgument->dispatch($sqlWalker)
        );
    }
}

\class_alias(
    L2Distance::class,
    'Devscast\Lugha\Retrieval\VectorStore\Doctrine\Operations\PostgresSQL\EuclideanDistance',
);
