<?php

namespace App\Contracts\Repositories;
use App\Models\UserProfileModel;

interface UserProfileRepositoryInterface
{
    public function find($url);
}
