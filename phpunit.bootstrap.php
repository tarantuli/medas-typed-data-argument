<?php

declare(strict_types=1);

use Medas\ServiceManager\ServiceManager;
use Medas\TypedDataArguments\TypedDataArgumentsPackage;

chdir(__DIR__);

ServiceManager::get()
    ->addPackage(TypedDataArgumentsPackage::instance());
