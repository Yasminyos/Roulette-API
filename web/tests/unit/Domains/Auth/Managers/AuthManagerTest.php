<?php

namespace Tests\Unit\Domains\Auth\Managers;

use App\Domains\Auth\DTO\UserLoginDTO;
use App\Domains\Auth\DTO\UserRegisterDTO;
use App\Domains\Auth\Exceptions\InvalidPasswordException;
use App\Domains\Auth\Managers\AuthManager;
use App\Domains\Auth\Repositories\ApiTokenRepositoryInterface;
use App\Domains\User\Managers\UserManager;
use App\Domains\User\Models\User;
use App\Tools\Hash\HashManagerInterface;
use App\Tools\Repositories\BaseRepositoryInterface;
use Codeception\Test\Unit;
use Faker\Factory;
use Faker\Generator;

class AuthManagerTest extends Unit
{
    /** @var Generator */
    private $faker;
    
    /** @var User */
    private $user;
    
    /** @var string */
    private $token;
    
    /** @var UserLoginDTO */
    private $loginDTO;
    
    /** @var UserRegisterDTO */
    private $registerDTO;
    
    /** @var array */
    private $dependencies;
    
    public function _before()
    {
        if ($this->faker === null) {
            $this->faker = Factory::create();
            
            $this->user = new User([
                'id'       => $this->faker->randomNumber(),
                'email'    => $this->faker->email,
                'password' => $this->faker->password(8, 255)
            ]);
            $this->token = $this->faker->password(60, 60);
            
            $this->loginDTO = new UserLoginDTO($this->user->toArray());
            $this->registerDTO = new UserRegisterDTO($this->user->toArray());
            
            $this->dependencies = [
                'userManager'        => $this->make(UserManager::class, ['getWhereEmail' => $this->user]),
                'hashManager'        => $this->makeEmpty(HashManagerInterface::class, ['checkEquals' => true]),
                'apiTokenRepository' => $this->makeEmpty(ApiTokenRepositoryInterface::class, [
                    'getToken'    => $this->token,
                    'createToken' => $this->token
                ]),
                'userRepository'     => $this->makeEmpty(BaseRepositoryInterface::class, ['save' => true])
            ];
        }
        
    }
    
//    public function testRegister(): void
//    {
//
//        $result = $this->getObject($this->dependencies)->register($this->registerDTO);
//        $this->assertEquals($result->toArray(), [
//
//        ]);
//    }
    
    /**
     * @throws Throwable
     */
    public function testLogin(): void
    {
        $result = $this->getObject($this->dependencies)->login($this->loginDTO);
        
        $this->assertEquals($result->toArray(), [
            'user_id' => $this->user->id,
            'token'   => $this->token
        ]);
    }
    
    private function getObject(array $params): AuthManager
    {
        return $this->make(AuthManager::class, $params);
    }
    
    public function testLoginUserEmpty(): void
    {
        $dependencies = $this->dependencies;
        $dependencies['hashManager'] = $this->makeEmpty(HashManagerInterface::class, ['checkEquals' => false]);
        
        $this->expectException(InvalidPasswordException::class);
        $this->getObject($dependencies)->login($this->loginDTO);
    }
}
