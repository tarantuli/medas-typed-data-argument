<?php

declare(strict_types=1);

namespace Medas\TypedDataArgumentsTest\Functional;

use Medas\TypedDataArguments\TypedDataArgumentProcessor;
use Medas\TypedDataArgumentsTest\MockUps\{TypedDataDefinition, TypedDataMethods};
use PHPUnit\Framework\TestCase;

class TypedDataArgumentProcessorTest extends TestCase
{
    public function testBasicUsage(): void
    {
        $result = $this->getTypedData([]);

        self::assertInstanceOf(TypedDataDefinition::class, $result);

        self::expectException(\Error::class);
        echo $result->requiredInteger;
    }

    private function getTypedData(array $arguments): TypedDataDefinition
    {
        $processor = service(TypedDataArgumentProcessor::class);
        $methods = new TypedDataMethods();

        return $processor->process(new \ReflectionParameter($methods->hasTypedData(...), 0), $arguments);
    }

    public function testSetFromData(): void
    {
        $result = $this->getTypedData(['requiredInteger' => 1]);

        self::assertEquals(1, $result->requiredInteger);
        self::assertEquals(10, $result->integerWithDefault);

        $result = $this->getTypedData(['integerWithDefault' => 20]);
        self::assertEquals(20, $result->integerWithDefault);
    }

    public function testSetWrongType(): void
    {
        self::expectException(\TypeError::class);
        $this->getTypedData(['requiredInteger' => '1']);
    }

    public function testSetNull(): void
    {
        $result = $this->getTypedData(['nullableString' => null]);
        self::assertEquals(null, $result->nullableString);
    }
}
