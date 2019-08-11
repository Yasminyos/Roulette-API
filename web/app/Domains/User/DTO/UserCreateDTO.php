<?php

namespace App\Domains\User\DTO;

use App\Tools\DTO\AbstractDTO;

class UserCreateDTO extends AbstractDTO
{
    /** @var string */
    public $email;
    
    /** @var string */
    public $password;
    
    /** @var string */
    public $api_token;
    
    public function rules(): array
    {
        return [
            'email' => 'string|required|unique:users|max:255|regex:/^.+@.+$/i',
            'password' => 'string|required|min:8',
            'api_token' => 'string|required|max:80'
        ];
    }
}
