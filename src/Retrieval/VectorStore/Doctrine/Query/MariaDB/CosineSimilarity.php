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

namespace Devscast\Lugha\Retrieval\VectorStore\Doctrine\Query\MariaDB;

use Devscast\Lugha\Retrieval\VectorStore\Doctrine\Query\DistanceFunctionNode;
use Doctrine\ORM\Query\SqlWalker;

/**
 * Class CosineSimilarity.
 *
 * @see https://mariadb.com/kb/en/vec_distance_cosine/
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class CosineSimilarity extends DistanceFunctionNode
{
    #[\Override]
    public function getSql(SqlWalker $sqlWalker): string
    {
        return sprintf(
            'VEC_DISTANCE_COSINE(%s, %s)',
            $this->firstArgument->dispatch($sqlWalker),
            $this->secondArgument->dispatch($sqlWalker)
        );
    }
}
