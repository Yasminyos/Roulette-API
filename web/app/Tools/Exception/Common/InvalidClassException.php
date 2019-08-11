<?php

namespace App\Tools\Exception\Common;

use App\Tools\Exception\AbstractException;

class InvalidClassException extends AbstractException
{
    public function __construct($message = 'Invalid class')
    {
        parent::__construct(400, $message);
    }
}
