<?php

declare(strict_types=1);

namespace Devscast\Lugha\Tests\Model\Completion\Parser;

use Devscast\Lugha\Model\Completion\Parser\JsonOutputParser;
use PHPUnit\Framework\TestCase;

/**
 * Class JsonOutputParserTest.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class JsonOutputParserTest extends TestCase
{
    private JsonOutputParser $jsonOutputParser;

    #[\Override]
    protected function setUp(): void
    {
        // Instantiate the JsonOutputParser
        $this->jsonOutputParser = new JsonOutputParser();
    }

    public function testValidJson(): void
    {
        $json = '{"name": "John", "age": 30, "city": "New York"}';
        $result = $this->jsonOutputParser->__invoke($json);

        $this->assertIsArray($result);
        $this->assertEquals('John', $result['name']);
        $this->assertEquals(30, $result['age']);
        $this->assertEquals('New York', $result['city']);
    }

    public function testInvalidJson(): void
    {
        $json = '{"name": "John", "age": 30, "city": "New York"';
        $result = $this->jsonOutputParser->__invoke($json);

        $this->assertNull($result);
    }

    public function testEmptyJson(): void
    {
        $json = '';
        $result = $this->jsonOutputParser->__invoke($json);

        $this->assertNull($result);
    }

    public function testEmptyJsonObject(): void
    {
        $json = '{}';
        $result = $this->jsonOutputParser->__invoke($json);

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testArrayJson(): void
    {
        $json = '["apple", "banana", "cherry"]';
        $result = $this->jsonOutputParser->__invoke($json);

        $this->assertIsArray($result);
        $this->assertCount(3, $result);
        $this->assertEquals('apple', $result[0]);
        $this->assertEquals('banana', $result[1]);
        $this->assertEquals('cherry', $result[2]);
    }
}
