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
    
    /**
     * @param  string  $modelClass
     * @return BaseDbRepository
     * @throws Throwable
     */
    public function setModel(string $modelClass): BaseRepositoryInterface
    {
        $model = new $modelClass;
        throw_if(!($model instanceof Model), new InvalidClassException('Class must instance of Model'));
        
        $this->model = $model;
        
        return clone $this;
    }
    
    /**
     * @param  Model  $model
     * @return bool
     */
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
    
    /**
     * @param $primaryKey
     * @return Model|null
     * @throws Throwable
     * @throws NotFoundHttpException
     */
    public function findOneByPK($primaryKey): ?Model
    {
        return $this->findOne(
            $this->getPkCriteria($primaryKey)
        );
    }
    
    /**
     * @param  CriteriaInterface  ...$criteria
     * @return Model|null
     * @throws Throwable
     * @throws ModelNotFoundException
     */
    public function findOne(CriteriaInterface ...$criteria): ?Model
    {
        return $this->getQuery(...$criteria)->first();
    }
    
    /**
     * @param  CriteriaInterface  ...$criteria
     * @return Builder
     * @throws Throwable
     * @throws ModelNotFoundException
     */
    public function getQuery(CriteriaInterface ...$criteria): Builder
    {
        return $this->applyCriteriaSet($this->initQuery(), ...$criteria);
    }
    
    /**
     * @param  Builder  $query
     * @param  CriteriaInterface  ...$criteriaSet
     * @return Builder
     * @throws Throwable
     * @throws InvalidModelForCriteriaException
     */
    private function applyCriteriaSet(Builder $query, CriteriaInterface ...$criteriaSet): Builder
    {
        foreach ($criteriaSet as $criteria) {
            $this->lookupStrictCriteria($criteria);
            $this->applyCriteria($query, $criteria);
        }
        
        return $query;
    }
    
    /**
     * @param  CriteriaInterface  $criteria
     * @throws Throwable
     * @throws InvalidModelForCriteriaException
     */
    private function lookupStrictCriteria(CriteriaInterface $criteria): void
    {
        if ($criteria instanceof StrictCriteriaInterface) {
            throw_if(
                $criteria->getModelClass() !== get_class($this->getModel()),
                new InvalidModelForCriteriaException($criteria)
            );
        }
    }
    
    /**
     * @return Model
     * @throws Throwable
     * @throws ModelNotFoundException
     */
    private function getModel(): Model
    {
        throw_if($this->model === null, new ModelNotFoundException());
        
        return $this->model;
    }
    
    private function applyCriteria(Builder $query, CriteriaInterface $criteria): void
    {
        $criteria->applyTo($query);
    }
    
    /**
     * @return Builder
     * @throws Throwable
     * @throws ModelNotFoundException
     */
    private function initQuery(): Builder
    {
        return $this->getModel()->newQuery();
    }
    
    /**
     * @param $primaryKeyValue
     * @return CriteriaInterface
     * @throws Throwable
     * @throws ModelNotFoundException
     */
    private function getPkCriteria($primaryKeyValue): CriteriaInterface
    {
        return new PrimaryKeyCriteria($primaryKeyValue, $this->getModel());
    }
    
    /**
     * @param $primaryKey
     * @param  CriteriaInterface  ...$criteria
     * @return Model|null
     * @throws Throwable
     * @throws ModelNotFoundException
     */
    public function findOneWithPK($primaryKey, CriteriaInterface ...$criteria): ?Model
    {
        $criteria[] = $this->getPkCriteria($primaryKey);
        
        return $this->findOne(...$criteria);
    }
    
    /**
     * @param $primaryKey
     * @return array
     * @throws Throwable
     * @throws ModelNotFoundException
     */
    public function findAllByPK($primaryKey): array
    {
        return $this->findAll(
            $this->getPkCriteria($primaryKey)
        );
    }
    
    /**
     * @param  CriteriaInterface  ...$criteria
     * @return array
     * @throws Throwable
     * @throws ModelNotFoundException
     */
    public function findAll(CriteriaInterface ...$criteria): array
    {
        return $this->getQuery(...$criteria)->get()->all();
    }
    
    /**
     * @param $primaryKey
     * @param  CriteriaInterface  ...$criteria
     * @return array
     * @throws Throwable
     * @throws ModelNotFoundException
     */
    public function findAllWithPK($primaryKey, CriteriaInterface ...$criteria): array
    {
        $criteria[] = $this->getPkCriteria($primaryKey);
        
        return $this->findAll(...$criteria);
    }
    
    /**
     * @param $primaryKey
     * @param  CriteriaInterface  ...$criteria
     * @return Builder
     * @throws Throwable
     * @throws ModelNotFoundException
     */
    public function getQueryWithPK($primaryKey, CriteriaInterface ...$criteria): Builder
    {
        $criteria[] = $this->getPkCriteria($primaryKey);
        
        return $this->getQuery(...$criteria);
    }
}
