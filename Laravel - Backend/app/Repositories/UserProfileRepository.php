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

        if ($response->getStatusCode() == 200) {
            Log::info('Status Code: ' . $response->getStatusCode());
            return $response->getBody();
        }

        return null;
    }
}
