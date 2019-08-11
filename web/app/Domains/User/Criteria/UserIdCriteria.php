<?php

namespace App\Domains\User\Criteria;

use App\Tools\Repositories\Criteria\Interfaces\CriteriaInterface;
use Illuminate\Database\Eloquent\Builder;

class UserIdCriteria implements CriteriaInterface
{
    /** @var int */
    private $userId;
    
    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }
    
    public function applyTo(Builder $query): void
    {
        $query->where('user_id', $this->userId);
    }
}
