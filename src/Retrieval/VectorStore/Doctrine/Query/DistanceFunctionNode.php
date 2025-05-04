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

namespace Devscast\Lugha\Retrieval\VectorStore\Doctrine\Query;

use Doctrine\ORM\Query\AST\ASTException;
use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\AST\Subselect;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\QueryException;
use Doctrine\ORM\Query\SqlWalker;
use Doctrine\ORM\Query\TokenType;

/**
 * Class DistanceFunctionNode.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
abstract class DistanceFunctionNode extends FunctionNode
{
    protected Node $firstArgument;

    protected Node|Subselect $secondArgument;

    /**
     * @throws ASTException
     */
    #[\Override]
    abstract public function getSql(SqlWalker $sqlWalker): string;

    /**
     * @throws QueryException if the function is not valid
     * @throws \TypeError in rare case if the second argument is a string
     */
    #[\Override]
    public function parse(Parser $parser): void
    {
        $parser->match(TokenType::T_IDENTIFIER);
        $parser->match(TokenType::T_OPEN_PARENTHESIS);
        $this->firstArgument = $parser->StringPrimary();

        $parser->match(TokenType::T_COMMA);

        $secondArgument = $parser->StringExpression();
        if (is_string($secondArgument)) {
            throw new \TypeError(
                sprintf(
                    'Expected second argument to be a Node or Subselect, got %s',
                    get_debug_type($secondArgument)
                )
            );
        }

        $this->secondArgument = $secondArgument;
        $parser->match(TokenType::T_CLOSE_PARENTHESIS);
    }
}
