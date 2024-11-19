<?php

declare(strict_types=1);

namespace Devscast\Lugha\Tests\Model\Embedding;

use Devscast\Lugha\Model\Embedding\DimensionReducer;
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
        $vector = [1.0, 2.0, -3.0];
        $result = $this->reducer->reduce($vector, 2);

        $this->assertEqualsWithDelta([0.4472, 0.8944], $result, 0.0001);
    }

    public function testReduceMultiDimensional(): void
    {
        $vector = [
            [1.0, 2.0, 3.0],
            [4.0, -5.0, 6.0],
        ];
        $result = $this->reducer->reduce($vector, 2);

        $expected = [
            [0.4472, 0.8944],
            [0.6246, -0.7808],
        ];
        foreach ($result as $key => $reducedVector) {
            $this->assertEqualsWithDelta($expected[$key], $reducedVector, 0.0001);
        }
    }

    public function testReduceWithZeroVector(): void
    {
        $vector = [0.0, 0.0, 0.0];
        $result = $this->reducer->reduce($vector, 2);

        $this->assertSame([0.0, 0.0], $result);
    }

    public function testReduceMultiDimensionalWithZeroVector(): void
    {
        $vector = [
            [0.0, 0.0, 0.0],
            [4.0, 5.0, 6.0],
        ];
        $result = $this->reducer->reduce($vector, 2);

        $expected = [
            [0.0, 0.0],
            [0.6246, 0.7808],
        ];
        foreach ($result as $key => $reducedVector) {
            $this->assertEqualsWithDelta($expected[$key], $reducedVector, 0.0001);
        }
    }

    public function testReduceToHigherDimensions(): void
    {
        $vector = [1.0, 2.0, 3.0];
        $result = $this->reducer->reduce($vector, 5);

        $expected = [1.0, 2.0, 3.0];
        $this->assertEquals($expected, $result);
    }

    public function testReduceMultiDimensionalToHigherDimensions(): void
    {
        $vector = [
            [1.0, 2.0, 3.0],
            [4.0, 5.0, 6.0],
        ];
        $result = $this->reducer->reduce($vector, 5);

        $expected = [
            [1.0, 2.0, 3.0],
            [4.0, 5.0, 6.0],
        ];
        foreach ($result as $key => $reducedVector) {
            $this->assertEquals($expected[$key], $reducedVector);
        }
    }
}
