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

use Devscast\Lugha\Exception\InvalidArgumentException;
use Devscast\Lugha\Model\Embeddings\Distance;
use Devscast\Lugha\Model\Embeddings\Vector;
use PHPUnit\Framework\TestCase;

/**
 * Class DistanceTest.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class DistanceTest extends TestCase
{
    public function testVectorWithDifferentDimensions(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Vectors must have the same dimension.');

        $distance = Distance::COSINE;
        $a = Vector::from([2.0, 4.0, 1.0, 3.0]);
        $b = Vector::from([3.0, 5.0, 2.0]);

        $distance->compute($a, $b);
    }

    public function testDifferentVectorDistances(): void
    {
        $a = Vector::from([2.0, 4.0, 1.0, 3.0]);
        $b = Vector::from([3.0, 5.0, 2.0, 1.0]);

        $this->assertEquals(0.0937, \round(Distance::COSINE->compute($a, $b), 4));
        $this->assertEquals(2.6458, \round(Distance::L2->compute($a, $b), 4));
        $this->assertEquals(5.0, \round(Distance::L1->compute($a, $b), 4));
        $this->assertEquals(31.0, \round(Distance::INNER_PRODUCT->compute($a, $b), 4));
    }

    public function testSimilarVectorDistances(): void
    {
        $a = Vector::from([2.0, 4.0, 1.0, 3.0]);
        $b = Vector::from([2.0, 4.0, 1.0, 2.0]);

        $this->assertEquals(0.0141, \round(Distance::COSINE->compute($a, $b), 4));
        $this->assertEquals(1.0, \round(Distance::L2->compute($a, $b), 4));
        $this->assertEquals(1.0, \round(Distance::L1->compute($a, $b), 4));
        $this->assertEquals(27.0, \round(Distance::INNER_PRODUCT->compute($a, $b), 4));
    }

    public function testSameVectorDistances(): void
    {
        $a = Vector::from([2.0, 4.0, 1.0, 3.0]);
        $b = Vector::from([2.0, 4.0, 1.0, 3.0]);

        $this->assertEquals(0.0, \round(Distance::COSINE->compute($a, $b), 4));
        $this->assertEquals(0.0, \round(Distance::L2->compute($a, $b), 4));
        $this->assertEquals(0.0, \round(Distance::L1->compute($a, $b), 4));
        $this->assertEquals(30.0, \round(Distance::INNER_PRODUCT->compute($a, $b), 4));
    }
}
