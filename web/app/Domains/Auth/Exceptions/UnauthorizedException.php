<?php

namespace App\Domains\Auth\Exceptions;

use App\Tools\Exception\AbstractException;

class UnauthorizedException extends AbstractException
{
    public function __construct(
        $message = 'Invalid token'
    ) {
        parent::__construct(401, $message);
    }
}
