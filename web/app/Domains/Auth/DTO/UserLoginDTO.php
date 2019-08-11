<?php

namespace App\Domains\Auth\DTO;

use App\Tools\DTO\AbstractDTO;

class UserLoginDTO extends AbstractDTO
{
    /** @var string */
    public $email;
    
    /** @var string */
    public $password;
    
    public function rules(): array
    {
        return [
            'email' => 'string|required|max:255|regex:/^.+@.+$/i',
            'password' => 'string|required|min:8'
        ];
    }
}
