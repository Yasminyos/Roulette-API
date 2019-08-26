<?php

namespace App\Tools\Repositories;

use App\Tools\Exception\Common\InvalidClassException;
use App\Tools\Exception\Common\InvalidModelForCriteriaException;
use App\Tools\Repositories\Criteria\Interfaces\CriteriaInterface;
use App\Tools\Repositories\Criteria\Interfaces\StrictCriteriaInterface;
use App\Tools\Repositories\Criteria\PrimaryKeyCriteria;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class BaseDbRepository implements BaseRepositoryInterface
{
    /** @var Model */
    private $model;
    
    public function setModel(string $modelClass): BaseRepositoryInterface
    {
        $model = new $modelClass;
        
        if (!($model instanceof Model)) {
            throw new InvalidClassException('Class must instance of Model');
        }
        
        $this->model = $model;
        
        return clone $this;
    }
    
    public function save(Model $model): bool
    {
        return $model->save();
    }
    
    /**
     * @param  Model  $model
     * @return bool
     * @throws Exception
     */
    public function delete(Model $model): bool
    {
        return $model->delete();
    }
    
    public function findOneByPK($primaryKey): ?Model
    {
        return $this->findOne(
            $this->getPkCriteria($primaryKey)
        );
    }
    
    public function findOne(CriteriaInterface ...$criteria): ?Model
    {
        return $this->getQuery(...$criteria)->first();
    }
    
    public function getQuery(CriteriaInterface ...$criteria): Builder
    {
        return $this->applyCriteriaSet($this->initQuery(), ...$criteria);
    }
    
    private function applyCriteriaSet(Builder $query, CriteriaInterface ...$criteriaSet): Builder
    {
        foreach ($criteriaSet as $criteria) {
            $this->lookupStrictCriteria($criteria);
            $this->applyCriteria($query, $criteria);
        }
        
        return $query;
    }
    
    
    private function lookupStrictCriteria(CriteriaInterface $criteria): void
    {
        if (
            $criteria instanceof StrictCriteriaInterface
            && $criteria->getModelClass() !== get_class($this->getModel())
        ) {
            throw new InvalidModelForCriteriaException($criteria);
        }
    }
    
    
    private function getModel(): Model
    {
        if ($this->model === null) {
            throw new ModelNotFoundException('Model not found');
        }
        
        return $this->model;
    }
    
    private function applyCriteria(Builder $query, CriteriaInterface $criteria): void
    {
        $criteria->applyTo($query);
    }
    
    private function initQuery(): Builder
    {
        return $this->getModel()->newQuery();
    }
    
    private function getPkCriteria($primaryKeyValue): CriteriaInterface
    {
        return new PrimaryKeyCriteria($primaryKeyValue, $this->getModel());
    }
    
    
    public function findOneWithPK($primaryKey, CriteriaInterface ...$criteria): ?Model
    {
        $criteria[] = $this->getPkCriteria($primaryKey);
        
        return $this->findOne(...$criteria);
    }
    
    public function findAllByPK($primaryKey): array
    {
        return $this->findAll(
            $this->getPkCriteria($primaryKey)
        );
    }
    
    public function findAll(CriteriaInterface ...$criteria): array
    {
        return $this->getQuery(...$criteria)->get()->all();
    }
    
    public function findAllWithPK($primaryKey, CriteriaInterface ...$criteria): array
    {
        $criteria[] = $this->getPkCriteria($primaryKey);
        
        return $this->findAll(...$criteria);
    }
    
    public function getQueryWithPK($primaryKey, CriteriaInterface ...$criteria): Builder
    {
        $criteria[] = $this->getPkCriteria($primaryKey);
        
        return $this->getQuery(...$criteria);
    }
}
