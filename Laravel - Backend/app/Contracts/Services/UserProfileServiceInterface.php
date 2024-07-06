<?php

namespace App\Contracts\Services;

interface UserProfileServiceInterface
{
    public function getUserProfile($url);
    public function getUserFollowings($url);
    public function getUserFollowers($url);
}
