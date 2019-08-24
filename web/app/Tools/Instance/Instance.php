<?php

namespace App\Tools\Instance;

use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;

class Instance
{
    /**
     * @param  string  $abstract
     * @return object
     * @throws BindingResolutionException
     */
    public static function of(string $abstract): object
    {
        $instance = Container::getInstance();
        
        return $instance->make($abstract);
    }
}
