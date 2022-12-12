<?php

declare(strict_types=1);

namespace Medas\TypedDataArgumentsTest\MockUps;

use Medas\TypedDataArguments\TypedData;

class TypedDataMethods
{
    public function hasTypedData(#[TypedData] TypedDataDefinition $dataDefinition): TypedDataDefinition
    {
        return $dataDefinition;
    }
}
