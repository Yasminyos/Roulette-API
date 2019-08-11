<?php

namespace App\Tools\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

abstract class AbstractException extends HttpException
{
    public function __construct(
        $code = 400,
        $message = 'Error'
    ) {
        parent::__construct($code, $message);
    }
}
