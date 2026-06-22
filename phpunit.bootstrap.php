<?php

declare(strict_types=1);

use Medas\ObjectInstantiator\{ObjectInstantiator, ObjectInstantiatorPackage};
use Medas\ServiceManager\{ServiceConfigBuilder, ServiceManager};
use Medas\TypedDataArguments\TypedDataArgumentsPackage;

chdir(__DIR__);

new ServiceManager(function (): ServiceConfigBuilder {
    $config = new ServiceConfigBuilder(ObjectInstantiator::class);

    $config->addPackages([
        ObjectInstantiatorPackage::instance(),
        TypedDataArgumentsPackage::instance(),
    ]);

    return $config;
});
