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
        $user = $this->userRepository->find($url);
        $userDecoded = json_decode($user, true);
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

        usort($followingsFiltered, function($a, $b) {
            return strcasecmp($a['github_link'], $b['github_link']);
        });

        return $followingsFiltered;
    }

    /**
     * recupera seguidores de usuario
     *
     * @param string $url URL para os seguidores do perfil do usuario alvo
     */
    public function getUserFollowers($url)
    {
        $followers = $this->userRepository->findFollowers($url);

        $followersDecoded = json_decode($followers, true);

        if (!is_array($followersDecoded) || empty($followersDecoded)) {
            return null;
        }

        $camposParaManter = ["avatar_url", "login", "html_url"];
        $followersFiltered = [];

        foreach ($followersDecoded as $followers) {
            $filtered = array_intersect_key($followers, array_flip($camposParaManter));

            $userFollowModel = new UserFollowModel($filtered);

            $followersFiltered[] = $userFollowModel;
        }

        usort($followersFiltered, function($a, $b) {
            return strcasecmp($a['github_link'], $b['github_link']);
        });

        return $followersFiltered;
    }
}
