<?php

namespace App\Tools\DTO;

use App;
use App\Tools\Instance\Instance;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Validation\Factory as ValidatorFactory;
use Illuminate\Contracts\Validation\Validator;
use Validator as ValidatorFacade;

abstract class AbstractDTO
{
    private $validatorFactory;
    
    private $validator;
    
    /**
     * AbstractDTO constructor.
     *
     * @param  null  $data
     */
    public function __construct($data = null)
    {
        if ($data !== null) {
            $this->load($data);
        }
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
        
        $this->validator = ValidatorFacade::make($data, $rules);
        
        return !$this->validator->fails();
    }

    public function getValidator(): ?Validator
    {
        return $this->validator;
    }

    abstract public function rules(): array;
    
    public function __toString(): string
    {
        return (string) json_encode($this->toArray());
    }
    
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
