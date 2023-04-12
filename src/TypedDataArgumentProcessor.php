<?php

declare(strict_types=1);

namespace Medas\TypedDataArguments;

use Medas\ServiceManager\ParameterResolving\ArgumentProcessor;
use Medas\ServiceManager\Service;

#[Service]
class TypedDataArgumentProcessor implements ArgumentProcessor
{
    public function process(\ReflectionParameter|\ReflectionProperty $parameter, mixed $argument): mixed
    {
        if (!attribute(TypedData::class, $parameter)) {
            return $argument;
        }

        $type = $parameter->getType();

        if (!$type instanceof \ReflectionNamedType) {
            throw new \Exception('parameter marked as TypedData should have a named type as its parameter type');
        }

        $object = new ($type->getName());

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
