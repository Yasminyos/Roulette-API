<?php

namespace App\Tools\Repositories\Criteria\Interfaces;

interface StrictCriteriaInterface extends CriteriaInterface
{
    public function getModelClass(): string;
}
