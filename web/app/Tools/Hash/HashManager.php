<?php


namespace App\Tools\Hash;


use Illuminate\Support\Facades\Hash;

class HashManager implements HashManagerInterface
{
    public function createHash(string $text): string
    {
        return Hash::make($this->getSalt() . $text);
    }
    
    private function getSalt(): string
    {
        return getenv('HASH_SALT') ?: '';
    }
}
