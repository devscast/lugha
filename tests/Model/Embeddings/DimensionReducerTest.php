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

namespace Devscast\Lugha\Tests\Model\Embeddings;

use Devscast\Lugha\Model\Embeddings\DimensionReducer;
use Devscast\Lugha\Model\Embeddings\Vector;
use PHPUnit\Framework\TestCase;

/**
 * Class DimensionReducerTest.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class DimensionReducerTest extends TestCase
{
    private DimensionReducer $reducer;

    #[\Override]
    protected function setUp(): void
    {
        $this->reducer = new DimensionReducer();
    }

    public function testReduceSingleDimension(): void
    {
        $vector = Vector::from([1.0, 2.0, -3.0]);
        $result = $this->reducer->reduce($vector, 2);

        $this->assertEqualsWithDelta([0.4472, 0.8944], $result->values, 0.0001);
    }

    public function testReduceWithZeroVector(): void
    {
        $vector = Vector::from([0.0, 0.0, 0.0]);
        $result = $this->reducer->reduce($vector, 2);

        $this->assertSame([0.0, 0.0], $result->values);
    }

    public function testReduceToHigherDimensions(): void
    {
        $vector = Vector::from([1.0, 2.0, 3.0]);
        $result = $this->reducer->reduce($vector, 5);

        $expected = [1.0, 2.0, 3.0];
        $this->assertEquals($expected, $result->values);
    }
}
