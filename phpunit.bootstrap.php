<?php

declare(strict_types=1);

use Medas\ObjectInstantiator\{ObjectInstantiator, ObjectInstantiatorPackage};
use Medas\ServiceManager\{ServiceConfig, ServiceManager};
use Medas\TypedDataArguments\TypedDataArgumentsPackage;

chdir(__DIR__);

new ServiceManager(function (): ServiceConfig {
    $config = new ServiceConfig(ObjectInstantiator::class);

    $config->addPackages([
        ObjectInstantiatorPackage::instance(),
        TypedDataArgumentsPackage::instance(),
    ]);

    return $config;
});
