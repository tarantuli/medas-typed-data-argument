<?php

declare(strict_types=1);

namespace Medas\TypedDataArguments;

use Medas\Core\{
    Attributes\Service,
    CompiledValue,
    Interfaces\CompilableArgumentProcessor,
    ProcessesByCompiling
};

#[Service]
class TypedDataArgumentProcessor implements CompilableArgumentProcessor
{
    use ProcessesByCompiling;

    public function compile(\ReflectionParameter|\ReflectionProperty $parameter): CompiledValue|null
    {
        if (!attribute(TypedData::class, $parameter)) {
            return null;
        }

        $type = $parameter->getType();

        if (!$type instanceof \ReflectionNamedType) {
            throw new Exceptions\ParameterMustHaveNamedType($parameter);
        }

        return new CompiledValue($type->getName());
    }

    /**
     * @param CompiledValue $compiled
     */
    public function processCompiled(object $compiled, mixed $argument): mixed
    {
        $object = new ($compiled->value);

        foreach ($argument as $property => $value) {
            // This does not throw type errors, like direct assignment does:
            //(new \ReflectionProperty($object, $property))->setValue($object, $value);
            $object->$property = $value;
        }

        return $object;
    }

    public function priority(): int
    {
        return -50;
    }
}
