<?php

class RegisterUserCest
{
    private $reuestUrl = 'register';
    
    public function _before(ApiTester $I)
    {
    }

    public function registerTest(ApiTester $I)
    {
        $I->assertTrue(true);
    }
}
