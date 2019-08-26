<?php

namespace App\Tools\DTO;

use App;
use App\Tools\Instance\Instance;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Validation\Factory as ValidatorFactory;
use Illuminate\Contracts\Validation\Validator;
use ReflectionObject;
use ReflectionProperty;

abstract class AbstractDTO
{
    /** @var ValidatorFactory */
    protected $validatorFactory;
    /** @var Validator */
    protected $validator;
    
    /**
     * AbstractDTO constructor.
     *
     * @param  null  $data
     * @throws BindingResolutionException
     */
    public function __construct($data = null)
    {
        if ($data !== null) {
            $this->load($data);
        }
        
        $this->validatorFactory = Instance::of(ValidatorFactory::class);
    }
    
    /**
     * @param $data
     */
    public function load($data): void
    {
        $properties = get_object_vars($this);
        $propertiesName = array_keys($properties);
        
        foreach ($data as $key => $value) {
            if (in_array($key, $propertiesName, true)) {
                $this->$key = $value;
            }
        }
    }
    
    public function validate(): bool
    {
        $data = get_object_vars($this);
        $rules = $this->rules();
        
        $this->validator = $this->validatorFactory->make($data, $rules);
        
        return !$this->validator->fails();
    }
    
    abstract public function rules(): array;
    
    public function getValidator(): ?Validator
    {
        return $this->validator;
    }
    
    public function __toString(): string
    {
        return (string) json_encode($this->toArray());
    }
    
    public function toArray(): array
    {
        $propertyThisObj = get_object_vars($this);
        $reflection = new ReflectionObject($this);
        
        $selfReflection = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);
        $selfPublicProperty = array_map(static function (ReflectionProperty $property) {
            return $property->name;
        }, $selfReflection);
        
        return array_filter(
            $propertyThisObj,
            static function ($propertyName) use ($selfPublicProperty) {
                return in_array($propertyName, $selfPublicProperty, true);
            },
            ARRAY_FILTER_USE_KEY
        );
    }
}
