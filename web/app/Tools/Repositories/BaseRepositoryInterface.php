<?php

namespace App\Tools\Repositories;

use App\Tools\Repositories\Criteria\Interfaces\CriteriaInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

interface BaseRepositoryInterface
{
    public function setModel(string $modelClass): self;
    
    public function save(Model $model): bool;
    
    public function delete(Model $model): bool;
    
    public function findOneByPK($primaryKey): ?Model;
    
    public function findOneWithPK($primaryKey, CriteriaInterface ...$criteria): ?Model;
    
    public function findOne(CriteriaInterface ...$criteria): ?Model;
    
    public function findAllByPK($primaryKey): array;
    
    public function findAllWithPK($primaryKey, CriteriaInterface ...$criteria): array;
    
    public function findAll(CriteriaInterface ...$criteria): array;
    
    public function getQueryWithPK($primaryKey, CriteriaInterface ...$criteria): Builder;
    
    public function getQuery(CriteriaInterface ...$criteria): Builder;
}
