<?php

namespace App\Domains\Auth\DTO;

use App\Tools\DTO\AbstractDTO;

class TokenDTO extends AbstractDTO
{
    /** @var string */
    public $token;
    
    /** @var string */
    public $user_id;
    
    public function rules(): array
    {
        return [
            'token'  => 'string|required',
            'user_id' => 'int|required'
        ];
    }
}
