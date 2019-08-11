<?php

namespace App\Tools\Hash;

/**
 * Interface HashManagerInterface
 *
 * @package App\Tools\Hash
 */
interface HashManagerInterface
{
    public function createHash(string $text): string;
    
    public function checkEquals(string $text, string $hash): bool;
}
