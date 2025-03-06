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

namespace Devscast\Lugha\Tests\Model\Embedding;

use Devscast\Lugha\Model\Embedding\Vector;
use PHPUnit\Framework\TestCase;

/**
 * Class VectorTest.
 *
 * @author bernard
 */
final class VectorTest extends TestCase
{
    public function testConstructorAndGetValues(): void
    {
        $values = [1.0, 2.0, 3.0];
        $vector = new Vector($values);

        $this->assertSame($values, $vector->values);
    }

    public function testToString(): void
    {
        $values = [1.01, 2.4, 3.01];
        $vector = new Vector($values);

        $this->assertSame('1.01,2.4,3.01', (string) $vector);
    }

    public function testFrom(): void
    {
        $values = [1.0, 2.0, 3.0];
        $vector = Vector::from($values);

        $this->assertInstanceOf(Vector::class, $vector);
        $this->assertSame($values, $vector->values);
    }

    public function testGetDimension(): void
    {
        $values = [1.0, 2.0, 3.0];
        $vector = new Vector($values);

        $this->assertSame(3, $vector->getDimension());
    }

    public function testGetMagnitude(): void
    {
        $values = [3.0, 4.0];
        $vector = new Vector($values);

        $this->assertSame(5.0, $vector->getMagnitude());
    }

    public function testJsonSerialize(): void
    {
        $values = [1.0, 2.0, 3.0];
        $vector = new Vector($values);

        $this->assertSame([
            'values' => $values,
        ], $vector->jsonSerialize());
    }
}
