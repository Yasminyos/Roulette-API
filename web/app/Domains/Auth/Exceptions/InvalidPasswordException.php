<?php

namespace App\Domains\Auth\Exceptions;

use App\Tools\Exception\AbstractException;

class InvalidPasswordException extends AbstractException
{
    public function __construct(
        $message = 'Invalid password'
    ) {
        parent::__construct(422, $message);
    }
}
