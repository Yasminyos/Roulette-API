<?php

namespace App\Tools\Repositories\Criteria\Interfaces;

use Illuminate\Database\Eloquent\Builder;

interface CriteriaInterface
{
    public function applyTo(Builder $query): void;
}
