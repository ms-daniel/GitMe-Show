<?php

namespace App\Repositories;

use GuzzleHttp\Client;
use App\Contracts\Repositories\UserProfileRepositoryInterface;
use Illuminate\Support\Facades\Log;

class UserProfileRepository implements UserProfileRepositoryInterface
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function find($url)
    {
        $response = $this->client->request('GET',$url);

        $data = $response->getBody();

        if ($response->getStatusCode() == 200 && $data != null) {
            return $data;
        }

        return null;
    }

    public function findFollowings($url)
    {
        $response = $this->client->request('GET',$url);

        $data = $response->getBody();

        if ($response->getStatusCode() == 200 && $data != null) {
            return $data;
        }

        return null;
    }

    public function findFollowers($url)
    {
        $response = $this->client->request('GET',$url);

        $data = $response->getBody();

        if ($response->getStatusCode() == 200 && $data != null) {
            return $data;
        }

        return null;
    }
}
