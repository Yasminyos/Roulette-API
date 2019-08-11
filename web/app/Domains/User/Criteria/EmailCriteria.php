<?php

namespace App\Domains\User\Criteria;

use App\Tools\Repositories\Criteria\Interfaces\CriteriaInterface;
use Illuminate\Database\Eloquent\Builder;

class EmailCriteria implements CriteriaInterface
{
    /** @var string */
    private $email;
    
    public function __construct(
        string $email
    ) {
        $this->email = $email;
    }
    
    public function applyTo(Builder $query): void
    {
        $query->where('email', $this->email);
    }
}
