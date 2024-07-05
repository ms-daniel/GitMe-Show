<?php

namespace App\Services;

use App\Contracts\Repositories\UserProfileRepositoryInterface;
use App\Contracts\Services\UserProfileServiceInterface;
use App\Models\UserProfileModel;
use App\Models\UserFollowModel;
use Exception;
use GuzzleHttp\Exception\RequestException;

class UserProfileService implements UserProfileServiceInterface
{
    protected $userRepository;

    //apagar, mock de data para evitar requisitar api
    private $user = '{
        "login": "ms-daniel",
        "id": 62726040,
        "node_id": "MDQ6VXNlcjYyNzI2MDQw",
        "avatar_url": "https://avatars.githubusercontent.com/u/62726040?v=4",
        "gravatar_id": "",
        "url": "https://api.github.com/users/ms-daniel",
        "html_url": "https://github.com/ms-daniel",
        "followers_url": "https://api.github.com/users/ms-daniel/followers",
        "following_url": "https://api.github.com/users/ms-daniel/following{/other_user}",
        "gists_url": "https://api.github.com/users/ms-daniel/gists{/gist_id}",
        "starred_url": "https://api.github.com/users/ms-daniel/starred{/owner}{/repo}",
        "subscriptions_url": "https://api.github.com/users/ms-daniel/subscriptions",
        "organizations_url": "https://api.github.com/users/ms-daniel/orgs",
        "repos_url": "https://api.github.com/users/ms-daniel/repos",
        "events_url": "https://api.github.com/users/ms-daniel/events{/privacy}",
        "received_events_url": "https://api.github.com/users/ms-daniel/received_events",
        "type": "User",
        "site_admin": false,
        "name": "Carlos Daniel",
        "company": "Universidade Federal de Sergipe",
        "blog": "https://www.instagram.com/_ms_daniel/",
        "location": "Sergipe, Brasil",
        "email": null,
        "hireable": null,
        "bio": null,
        "twitter_username": null,
        "public_repos": 34,
        "public_gists": 0,
        "followers": 17,
        "following": 17,
        "created_at": "2020-03-26T22:18:29Z",
        "updated_at": "2024-06-20T22:49:43Z"
    }';

    public function __construct(UserProfileRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * recupera dados de usuario
     *
     * @param string $url URL para perfil do usuario alvo
     */
    public function getUserProfile($url)
    {
        //$user = $this->userRepository->find($url);
        $userDecoded = json_decode($this->user, true);
        $userData = new UserProfileModel($userDecoded);

        return $userData;
    }

    /**
     * recupera seguidos de usuario
     *
     * @param string $url URL para os seguidos do perfil do usuario alvo
     */
    public function getUserFollowings($url)
    {
        $followings = $this->userRepository->findFollowings($url);

        $followingsDecoded = json_decode($followings, true);

        if (!is_array($followingsDecoded) || empty($followingsDecoded)) {
            return null;
        }

        $camposParaManter = ["avatar_url", "login", "html_url"];
        $followingsFiltered = [];

        foreach ($followingsDecoded as $following) {
            $filtered = array_intersect_key($following, array_flip($camposParaManter));

            $userFollowModel = new UserFollowModel($filtered);

            $followingsFiltered[] = $userFollowModel;
        }

        return $followingsFiltered;
    }
}
