<?php

declare(strict_types=1);

namespace Medas\TypedDataArguments\Exceptions;

use Medas\Core\Exceptions\BaseException;

class ParameterMustHaveNamedType extends BaseException
{
    public function __construct(\ReflectionParameter|\ReflectionProperty $parameter)
    {
        parent::__construct($parameter->getName());
    }

    public function pattern(): string
    {
        return 'parameter %s marked with TypedData must have a named type';
    }
}
