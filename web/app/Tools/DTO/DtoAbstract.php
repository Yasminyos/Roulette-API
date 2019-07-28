<?php


namespace App\Tools\DTO;

use Illuminate\Contracts\Validation\Validator as ValidatorResponse;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

abstract class DtoAbstract
{
    /**
     * DtoAbstract constructor.
     *
     * @param null $data
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
    
    
    public function validateThrowException(): void
    {
        $validator = $this->validate();
        
        if ($validator->fails()) {
            throw new UnprocessableEntityHttpException(implode(' ', $validator->getMessageBag()->all()));
        }
    }
    
    public function validate(): ValidatorResponse
    {
        $data = get_object_vars($this);
        $rules = $this->rules();
        
        return Validator::make($data, $rules);
    }
    
    abstract public function rules(): array;
    
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
