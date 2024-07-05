<?php

namespace App\Contracts\Repositories;

interface UserProfileRepositoryInterface
{
    public function find($url);
    public function findFollowings($url);
}
