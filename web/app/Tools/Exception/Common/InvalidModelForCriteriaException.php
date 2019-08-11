<?php

namespace App\Tools\Exception\Common;

use App\Tools\Exception\AbstractException;
use App\Tools\Repositories\Criteria\Interfaces\StrictCriteriaInterface;

class InvalidModelForCriteriaException extends AbstractException
{
    public function __construct(StrictCriteriaInterface $criteria)
    {
        $message = 'Strict criteria must be applied to defined model class';
        
        parent::__construct(400, $message);
    }
}
