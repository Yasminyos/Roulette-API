<?php

namespace App\Domains\User\Exceptions;

use App\Tools\Exception\AbstractException;

class UserNotFoundException extends AbstractException
{
    public function __construct(
        $message = 'User model not found'
    ) {
        parent::__construct(404, $message);
    }
}
