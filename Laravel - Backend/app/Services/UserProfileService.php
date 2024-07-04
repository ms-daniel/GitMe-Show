<?php

namespace App\Services;

use App\Contracts\Repositories\UserProfileRepositoryInterface;
use App\Contracts\Services\UserProfileServiceInterface;

class UserProfileService implements UserProfileServiceInterface
{
    protected $userRepository;

    public function __construct(UserProfileRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getUserProfile($url)
    {
        $user = $this->userRepository->find($url);

        return $user;
    }
}
