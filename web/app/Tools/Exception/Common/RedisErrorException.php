<?php

namespace App\Tools\Exception\Common;

use App\Tools\Exception\AbstractException;

class RedisErrorException extends AbstractException
{
    public function __construct($message = 'Redis error exception')
    {
        parent::__construct(400, $message);
    }
}
