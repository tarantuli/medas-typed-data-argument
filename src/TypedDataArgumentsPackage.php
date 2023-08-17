<?php

declare(strict_types=1);

namespace Medas\TypedDataArguments;

use Medas\Core\AsSingleton;
use Medas\ServiceManager\{BasePackage, ServiceConfig};

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

    public function initialize(ServiceConfig $config): void
    {
        $config->addArgumentProcessor(service(TypedDataArgumentProcessor::class));
        parent::initialize($config);
    }
}
