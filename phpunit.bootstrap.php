<?php

declare(strict_types=1);

use Medas\ServiceManager\{ServiceConfig, ServiceManager};
use Medas\TypedDataArguments\TypedDataArgumentsPackage;

chdir(__DIR__);

new ServiceManager(function (): ServiceConfig {
    $config = new ServiceConfig();

    $config->addPackages([
        TypedDataArgumentsPackage::instance(),
    ]);

    return $config;
});
