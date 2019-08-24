<?php

namespace App\Tools\Exception;

use App\Tools\DTO\AbstractDTO;
use Illuminate\Validation\ValidationException;

class DtoException extends ValidationException
{
    public function __construct(AbstractDTO $DTO)
    {
        parent::__construct($DTO->getValidator());
    }
}
