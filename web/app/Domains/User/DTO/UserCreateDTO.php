<?php

namespace App\Domains\User\DTO;

use App\Tools\DTO\DtoAbstract;

class UserCreateDTO extends DtoAbstract
{
    public $email;
    
    public $password;
    
    public function rules(): array
    {
        return [
            'email'    => 'string|required|regex:/^.+@.+$/i|unique:users,email_address',
            'password' => 'string|required',
        ];
    }
}
