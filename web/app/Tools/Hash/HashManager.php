<?php


namespace App\Tools\Hash;


use Illuminate\Support\Facades\Hash;

class HashManager implements HashManagerInterface
{
    public function createHash(string $text): string
    {
        
        return Hash::make($this->getWithSalt($text));
    }
    
    public function checkEquals(string $text, string $hash): bool
    {
        return Hash::check($this->getWithSalt($text), $hash);
    }
    
    private function getWithSalt(string $text): string
    {
        $salt = getenv('HASH_SALT') ?: '';
        
        return $salt . $text;
    }
}
