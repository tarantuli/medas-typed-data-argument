<?php

declare(strict_types=1);

namespace Medas\TypedDataArguments;

use Medas\Core\{AsSingleton, BasePackage, Interfaces\ServiceConfigBuilder};

class TypedDataArgumentsPackage extends BasePackage
{
    use AsSingleton;

    public function dependencies(): array
    {
        return [];
    }

    public function sourceDirectory(): string
    {
        return __DIR__;
    }

    public function initialize(ServiceConfigBuilder $config): void
    {
        $config->addArgumentProcessor(TypedDataArgumentProcessor::class);

        parent::initialize($config);
    }
}
