<?php


namespace App\Tools\Hash;


use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Support\Facades\Hash;

class HashManager implements HashManagerInterface
{
    private $hashHelper;
    
    public function __construct(
        Hasher $hashHelper
    ) {
        $this->hashHelper = $hashHelper;
    }
    
    public function createHash(string $text): string
    {
        
        return $this->hashHelper->make($this->getWithSalt($text));
    }
    
    public function checkEquals(string $text, string $hash): bool
    {
        return $this->hashHelper->check($this->getWithSalt($text), $hash);
    }
    
    private function getWithSalt(string $text): string
    {
        $salt = env('HASH_SALT', '');
        
        return $salt . $text;
    }
}
