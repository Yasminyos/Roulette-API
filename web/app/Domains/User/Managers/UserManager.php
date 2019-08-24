<?php

namespace App\Domains\User\Managers;

use App\Domains\User\Criteria\EmailCriteria;
use App\Domains\User\Exceptions\UserNotFoundException;
use App\Domains\User\Models\User;
use App\Tools\Repositories\BaseRepositoryInterface;
use Throwable;

class UserManager
{
    /** @var BaseRepositoryInterface */
    private $userRepository;
    
    public function __construct(
        BaseRepositoryInterface $repository
    ) {
        $this->userRepository = $repository->setModel(User::class);
    }
    
    /**
     * @param  string  $userId
     * @return User
     */
    public function get(string $userId): User
    {
        /** @var User $user */
        $user = $this->userRepository->findOneByPK($userId);
    
        if ($user === null) {
            throw new UserNotFoundException();
        }
        
        return $user;
    }
    
    /**
     * @param  string  $email
     * @return User
     */
    public function getWhereEmail(string $email): User
    {
        /** @var User $user */
        $user = $this->userRepository->findOne(new EmailCriteria($email));
        
        if ($user === null) {
            throw new UserNotFoundException();
        }
        
        return $user;
    }
}
