<?php

namespace App\Tools\Repositories\Criteria;

use App\Tools\Repositories\Criteria\Interfaces\CriteriaInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PrimaryKeyCriteria implements CriteriaInterface
{
    private $primaryKeyValue;
    
    /** @var Model */
    private $model;
    
    public function __construct(
        $primaryKeyValue,
        Model $model
    ) {
        $this->primaryKeyValue = $primaryKeyValue;
        $this->model = $model;
    }
    
    public function applyTo(Builder $query): void
    {
       $query->where(
           $this->model->getKeyName(),
           $this->primaryKeyValue
       );
    }
}
