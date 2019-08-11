<?php

namespace App\Domains\Auth\Criteria;

use App\Tools\Repositories\Criteria\Interfaces\CriteriaInterface;
use Illuminate\Database\Eloquent\Builder;

class TokenCriteria implements CriteriaInterface
{
    /** @var string */
    private $token;
    
    public function __construct(string $token)
    {
        $this->token = $token;
    }
    
    public function applyTo(Builder $query): void
    {
        $query->where('token', $this->token);
    }
}
