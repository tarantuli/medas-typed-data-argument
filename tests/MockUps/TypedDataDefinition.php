<?php

declare(strict_types=1);

namespace Medas\TypedDataArgumentsTest\MockUps;

class TypedDataDefinition
{
    public int $requiredInteger;

    public int $integerWithDefault = 10;

    public string $requiredString;

    public string|null $nullableString;
}
