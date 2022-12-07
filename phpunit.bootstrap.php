<?php

declare(strict_types=1);

use Medas\TypedDataArguments\TypedDataArgumentsPackage;
use Medas\ServiceManager\ServiceManager;

chdir(__DIR__);

ServiceManager::get()
    ->addPackage(TypedDataArgumentsPackage::instance());
