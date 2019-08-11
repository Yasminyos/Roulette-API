<?php

use App\Domains\Auth\Repositories\ApiTokenDbRepository;
use App\Domains\Auth\Repositories\ApiTokenRepositoryInterface;
use App\Tools\Hash\HashManager;
use App\Tools\Hash\HashManagerInterface;
use App\Tools\Repositories\BaseDbRepository;
use App\Tools\Repositories\BaseRepositoryInterface;

$definitions = [
    BaseRepositoryInterface::class     => BaseDbRepository::class,
    HashManagerInterface::class        => HashManager::class,
    ApiTokenRepositoryInterface::class => ApiTokenDbRepository::class
];

foreach ($definitions as $abstract => $concrete) {
    $app->bind($abstract, $concrete);
}

unset($definitions);

