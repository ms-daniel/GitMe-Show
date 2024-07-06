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

    private $followsMock = '[
                            {
                                "login": "Luiz-Fel",
                                "id": 36901019,
                                "node_id": "MDQ6VXNlcjM2OTAxMDE5",
                                "avatar_url": "https://avatars.githubusercontent.com/u/36901019?v=4",
                                "gravatar_id": "",
                                "url": "https://api.github.com/users/Luiz-Fel",
                                "html_url": "https://github.com/Luiz-Fel",
                                "followers_url": "https://api.github.com/users/Luiz-Fel/followers",
                                "following_url": "https://api.github.com/users/Luiz-Fel/following{/other_user}",
                                "gists_url": "https://api.github.com/users/Luiz-Fel/gists{/gist_id}",
                                "starred_url": "https://api.github.com/users/Luiz-Fel/starred{/owner}{/repo}",
                                "subscriptions_url": "https://api.github.com/users/Luiz-Fel/subscriptions",
                                "organizations_url": "https://api.github.com/users/Luiz-Fel/orgs",
                                "repos_url": "https://api.github.com/users/Luiz-Fel/repos",
                                "events_url": "https://api.github.com/users/Luiz-Fel/events{/privacy}",
                                "received_events_url": "https://api.github.com/users/Luiz-Fel/received_events",
                                "type": "User",
                                "site_admin": false
                            },
                            {
                                "login": "junio10",
                                "id": 50469065,
                                "node_id": "MDQ6VXNlcjUwNDY5MDY1",
                                "avatar_url": "https://avatars.githubusercontent.com/u/50469065?v=4",
                                "gravatar_id": "",
                                "url": "https://api.github.com/users/junio10",
                                "html_url": "https://github.com/junio10",
                                "followers_url": "https://api.github.com/users/junio10/followers",
                                "following_url": "https://api.github.com/users/junio10/following{/other_user}",
                                "gists_url": "https://api.github.com/users/junio10/gists{/gist_id}",
                                "starred_url": "https://api.github.com/users/junio10/starred{/owner}{/repo}",
                                "subscriptions_url": "https://api.github.com/users/junio10/subscriptions",
                                "organizations_url": "https://api.github.com/users/junio10/orgs",
                                "repos_url": "https://api.github.com/users/junio10/repos",
                                "events_url": "https://api.github.com/users/junio10/events{/privacy}",
                                "received_events_url": "https://api.github.com/users/junio10/received_events",
                                "type": "User",
                                "site_admin": false
                            },
                            {
                                "login": "joannestephany",
                                "id": 58868281,
                                "node_id": "MDQ6VXNlcjU4ODY4Mjgx",
                                "avatar_url": "https://avatars.githubusercontent.com/u/58868281?v=4",
                                "gravatar_id": "",
                                "url": "https://api.github.com/users/joannestephany",
                                "html_url": "https://github.com/joannestephany",
                                "followers_url": "https://api.github.com/users/joannestephany/followers",
                                "following_url": "https://api.github.com/users/joannestephany/following{/other_user}",
                                "gists_url": "https://api.github.com/users/joannestephany/gists{/gist_id}",
                                "starred_url": "https://api.github.com/users/joannestephany/starred{/owner}{/repo}",
                                "subscriptions_url": "https://api.github.com/users/joannestephany/subscriptions",
                                "organizations_url": "https://api.github.com/users/joannestephany/orgs",
                                "repos_url": "https://api.github.com/users/joannestephany/repos",
                                "events_url": "https://api.github.com/users/joannestephany/events{/privacy}",
                                "received_events_url": "https://api.github.com/users/joannestephany/received_events",
                                "type": "User",
                                "site_admin": false
                            },
                            {
                                "login": "samuelAnjos",
                                "id": 61122752,
                                "node_id": "MDQ6VXNlcjYxMTIyNzUy",
                                "avatar_url": "https://avatars.githubusercontent.com/u/61122752?v=4",
                                "gravatar_id": "",
                                "url": "https://api.github.com/users/samuelAnjos",
                                "html_url": "https://github.com/samuelAnjos",
                                "followers_url": "https://api.github.com/users/samuelAnjos/followers",
                                "following_url": "https://api.github.com/users/samuelAnjos/following{/other_user}",
                                "gists_url": "https://api.github.com/users/samuelAnjos/gists{/gist_id}",
                                "starred_url": "https://api.github.com/users/samuelAnjos/starred{/owner}{/repo}",
                                "subscriptions_url": "https://api.github.com/users/samuelAnjos/subscriptions",
                                "organizations_url": "https://api.github.com/users/samuelAnjos/orgs",
                                "repos_url": "https://api.github.com/users/samuelAnjos/repos",
                                "events_url": "https://api.github.com/users/samuelAnjos/events{/privacy}",
                                "received_events_url": "https://api.github.com/users/samuelAnjos/received_events",
                                "type": "User",
                                "site_admin": false
                            },
                            {
                                "login": "DSC09GH",
                                "id": 62481569,
                                "node_id": "MDQ6VXNlcjYyNDgxNTY5",
                                "avatar_url": "https://avatars.githubusercontent.com/u/62481569?v=4",
                                "gravatar_id": "",
                                "url": "https://api.github.com/users/DSC09GH",
                                "html_url": "https://github.com/DSC09GH",
                                "followers_url": "https://api.github.com/users/DSC09GH/followers",
                                "following_url": "https://api.github.com/users/DSC09GH/following{/other_user}",
                                "gists_url": "https://api.github.com/users/DSC09GH/gists{/gist_id}",
                                "starred_url": "https://api.github.com/users/DSC09GH/starred{/owner}{/repo}",
                                "subscriptions_url": "https://api.github.com/users/DSC09GH/subscriptions",
                                "organizations_url": "https://api.github.com/users/DSC09GH/orgs",
                                "repos_url": "https://api.github.com/users/DSC09GH/repos",
                                "events_url": "https://api.github.com/users/DSC09GH/events{/privacy}",
                                "received_events_url": "https://api.github.com/users/DSC09GH/received_events",
                                "type": "User",
                                "site_admin": false
                            },
                            {
                                "login": "Gabrielx47",
                                "id": 62863349,
                                "node_id": "MDQ6VXNlcjYyODYzMzQ5",
                                "avatar_url": "https://avatars.githubusercontent.com/u/62863349?v=4",
                                "gravatar_id": "",
                                "url": "https://api.github.com/users/Gabrielx47",
                                "html_url": "https://github.com/Gabrielx47",
                                "followers_url": "https://api.github.com/users/Gabrielx47/followers",
                                "following_url": "https://api.github.com/users/Gabrielx47/following{/other_user}",
                                "gists_url": "https://api.github.com/users/Gabrielx47/gists{/gist_id}",
                                "starred_url": "https://api.github.com/users/Gabrielx47/starred{/owner}{/repo}",
                                "subscriptions_url": "https://api.github.com/users/Gabrielx47/subscriptions",
                                "organizations_url": "https://api.github.com/users/Gabrielx47/orgs",
                                "repos_url": "https://api.github.com/users/Gabrielx47/repos",
                                "events_url": "https://api.github.com/users/Gabrielx47/events{/privacy}",
                                "received_events_url": "https://api.github.com/users/Gabrielx47/received_events",
                                "type": "User",
                                "site_admin": false
                            },
                            {
                                "login": "marcosmbm",
                                "id": 63175026,
                                "node_id": "MDQ6VXNlcjYzMTc1MDI2",
                                "avatar_url": "https://avatars.githubusercontent.com/u/63175026?v=4",
                                "gravatar_id": "",
                                "url": "https://api.github.com/users/marcosmbm",
                                "html_url": "https://github.com/marcosmbm",
                                "followers_url": "https://api.github.com/users/marcosmbm/followers",
                                "following_url": "https://api.github.com/users/marcosmbm/following{/other_user}",
                                "gists_url": "https://api.github.com/users/marcosmbm/gists{/gist_id}",
                                "starred_url": "https://api.github.com/users/marcosmbm/starred{/owner}{/repo}",
                                "subscriptions_url": "https://api.github.com/users/marcosmbm/subscriptions",
                                "organizations_url": "https://api.github.com/users/marcosmbm/orgs",
                                "repos_url": "https://api.github.com/users/marcosmbm/repos",
                                "events_url": "https://api.github.com/users/marcosmbm/events{/privacy}",
                                "received_events_url": "https://api.github.com/users/marcosmbm/received_events",
                                "type": "User",
                                "site_admin": false
                            },
                            {
                                "login": "MariaMilena",
                                "id": 65188572,
                                "node_id": "MDQ6VXNlcjY1MTg4NTcy",
                                "avatar_url": "https://avatars.githubusercontent.com/u/65188572?v=4",
                                "gravatar_id": "",
                                "url": "https://api.github.com/users/MariaMilena",
                                "html_url": "https://github.com/MariaMilena",
                                "followers_url": "https://api.github.com/users/MariaMilena/followers",
                                "following_url": "https://api.github.com/users/MariaMilena/following{/other_user}",
                                "gists_url": "https://api.github.com/users/MariaMilena/gists{/gist_id}",
                                "starred_url": "https://api.github.com/users/MariaMilena/starred{/owner}{/repo}",
                                "subscriptions_url": "https://api.github.com/users/MariaMilena/subscriptions",
                                "organizations_url": "https://api.github.com/users/MariaMilena/orgs",
                                "repos_url": "https://api.github.com/users/MariaMilena/repos",
                                "events_url": "https://api.github.com/users/MariaMilena/events{/privacy}",
                                "received_events_url": "https://api.github.com/users/MariaMilena/received_events",
                                "type": "User",
                                "site_admin": false
                            },
                            {
                                "login": "Ericles-Porty",
                                "id": 67772327,
                                "node_id": "MDQ6VXNlcjY3NzcyMzI3",
                                "avatar_url": "https://avatars.githubusercontent.com/u/67772327?v=4",
                                "gravatar_id": "",
                                "url": "https://api.github.com/users/Ericles-Porty",
                                "html_url": "https://github.com/Ericles-Porty",
                                "followers_url": "https://api.github.com/users/Ericles-Porty/followers",
                                "following_url": "https://api.github.com/users/Ericles-Porty/following{/other_user}",
                                "gists_url": "https://api.github.com/users/Ericles-Porty/gists{/gist_id}",
                                "starred_url": "https://api.github.com/users/Ericles-Porty/starred{/owner}{/repo}",
                                "subscriptions_url": "https://api.github.com/users/Ericles-Porty/subscriptions",
                                "organizations_url": "https://api.github.com/users/Ericles-Porty/orgs",
                                "repos_url": "https://api.github.com/users/Ericles-Porty/repos",
                                "events_url": "https://api.github.com/users/Ericles-Porty/events{/privacy}",
                                "received_events_url": "https://api.github.com/users/Ericles-Porty/received_events",
                                "type": "User",
                                "site_admin": false
                            },
                            {
                                "login": "charlescosta1",
                                "id": 68356054,
                                "node_id": "MDQ6VXNlcjY4MzU2MDU0",
                                "avatar_url": "https://avatars.githubusercontent.com/u/68356054?v=4",
                                "gravatar_id": "",
                                "url": "https://api.github.com/users/charlescosta1",
                                "html_url": "https://github.com/charlescosta1",
                                "followers_url": "https://api.github.com/users/charlescosta1/followers",
                                "following_url": "https://api.github.com/users/charlescosta1/following{/other_user}",
                                "gists_url": "https://api.github.com/users/charlescosta1/gists{/gist_id}",
                                "starred_url": "https://api.github.com/users/charlescosta1/starred{/owner}{/repo}",
                                "subscriptions_url": "https://api.github.com/users/charlescosta1/subscriptions",
                                "organizations_url": "https://api.github.com/users/charlescosta1/orgs",
                                "repos_url": "https://api.github.com/users/charlescosta1/repos",
                                "events_url": "https://api.github.com/users/charlescosta1/events{/privacy}",
                                "received_events_url": "https://api.github.com/users/charlescosta1/received_events",
                                "type": "User",
                                "site_admin": false
                            },
                            {
                                "login": "lauro-ss",
                                "id": 69280619,
                                "node_id": "MDQ6VXNlcjY5MjgwNjE5",
                                "avatar_url": "https://avatars.githubusercontent.com/u/69280619?v=4",
                                "gravatar_id": "",
                                "url": "https://api.github.com/users/lauro-ss",
                                "html_url": "https://github.com/lauro-ss",
                                "followers_url": "https://api.github.com/users/lauro-ss/followers",
                                "following_url": "https://api.github.com/users/lauro-ss/following{/other_user}",
                                "gists_url": "https://api.github.com/users/lauro-ss/gists{/gist_id}",
                                "starred_url": "https://api.github.com/users/lauro-ss/starred{/owner}{/repo}",
                                "subscriptions_url": "https://api.github.com/users/lauro-ss/subscriptions",
                                "organizations_url": "https://api.github.com/users/lauro-ss/orgs",
                                "repos_url": "https://api.github.com/users/lauro-ss/repos",
                                "events_url": "https://api.github.com/users/lauro-ss/events{/privacy}",
                                "received_events_url": "https://api.github.com/users/lauro-ss/received_events",
                                "type": "User",
                                "site_admin": false
                            },
                            {
                                "login": "XxthiagoboyXx",
                                "id": 72053163,
                                "node_id": "MDQ6VXNlcjcyMDUzMTYz",
                                "avatar_url": "https://avatars.githubusercontent.com/u/72053163?v=4",
                                "gravatar_id": "",
                                "url": "https://api.github.com/users/XxthiagoboyXx",
                                "html_url": "https://github.com/XxthiagoboyXx",
                                "followers_url": "https://api.github.com/users/XxthiagoboyXx/followers",
                                "following_url": "https://api.github.com/users/XxthiagoboyXx/following{/other_user}",
                                "gists_url": "https://api.github.com/users/XxthiagoboyXx/gists{/gist_id}",
                                "starred_url": "https://api.github.com/users/XxthiagoboyXx/starred{/owner}{/repo}",
                                "subscriptions_url": "https://api.github.com/users/XxthiagoboyXx/subscriptions",
                                "organizations_url": "https://api.github.com/users/XxthiagoboyXx/orgs",
                                "repos_url": "https://api.github.com/users/XxthiagoboyXx/repos",
                                "events_url": "https://api.github.com/users/XxthiagoboyXx/events{/privacy}",
                                "received_events_url": "https://api.github.com/users/XxthiagoboyXx/received_events",
                                "type": "User",
                                "site_admin": false
                            },
                            {
                                "login": "brunaa-keila",
                                "id": 72557993,
                                "node_id": "MDQ6VXNlcjcyNTU3OTkz",
                                "avatar_url": "https://avatars.githubusercontent.com/u/72557993?v=4",
                                "gravatar_id": "",
                                "url": "https://api.github.com/users/brunaa-keila",
                                "html_url": "https://github.com/brunaa-keila",
                                "followers_url": "https://api.github.com/users/brunaa-keila/followers",
                                "following_url": "https://api.github.com/users/brunaa-keila/following{/other_user}",
                                "gists_url": "https://api.github.com/users/brunaa-keila/gists{/gist_id}",
                                "starred_url": "https://api.github.com/users/brunaa-keila/starred{/owner}{/repo}",
                                "subscriptions_url": "https://api.github.com/users/brunaa-keila/subscriptions",
                                "organizations_url": "https://api.github.com/users/brunaa-keila/orgs",
                                "repos_url": "https://api.github.com/users/brunaa-keila/repos",
                                "events_url": "https://api.github.com/users/brunaa-keila/events{/privacy}",
                                "received_events_url": "https://api.github.com/users/brunaa-keila/received_events",
                                "type": "User",
                                "site_admin": false
                            },
                            {
                                "login": "Ti4goS",
                                "id": 87858900,
                                "node_id": "MDQ6VXNlcjg3ODU4OTAw",
                                "avatar_url": "https://avatars.githubusercontent.com/u/87858900?v=4",
                                "gravatar_id": "",
                                "url": "https://api.github.com/users/Ti4goS",
                                "html_url": "https://github.com/Ti4goS",
                                "followers_url": "https://api.github.com/users/Ti4goS/followers",
                                "following_url": "https://api.github.com/users/Ti4goS/following{/other_user}",
                                "gists_url": "https://api.github.com/users/Ti4goS/gists{/gist_id}",
                                "starred_url": "https://api.github.com/users/Ti4goS/starred{/owner}{/repo}",
                                "subscriptions_url": "https://api.github.com/users/Ti4goS/subscriptions",
                                "organizations_url": "https://api.github.com/users/Ti4goS/orgs",
                                "repos_url": "https://api.github.com/users/Ti4goS/repos",
                                "events_url": "https://api.github.com/users/Ti4goS/events{/privacy}",
                                "received_events_url": "https://api.github.com/users/Ti4goS/received_events",
                                "type": "User",
                                "site_admin": false
                            },
                            {
                                "login": "reinan47",
                                "id": 91684220,
                                "node_id": "U_kgDOBXb9fA",
                                "avatar_url": "https://avatars.githubusercontent.com/u/91684220?v=4",
                                "gravatar_id": "",
                                "url": "https://api.github.com/users/reinan47",
                                "html_url": "https://github.com/reinan47",
                                "followers_url": "https://api.github.com/users/reinan47/followers",
                                "following_url": "https://api.github.com/users/reinan47/following{/other_user}",
                                "gists_url": "https://api.github.com/users/reinan47/gists{/gist_id}",
                                "starred_url": "https://api.github.com/users/reinan47/starred{/owner}{/repo}",
                                "subscriptions_url": "https://api.github.com/users/reinan47/subscriptions",
                                "organizations_url": "https://api.github.com/users/reinan47/orgs",
                                "repos_url": "https://api.github.com/users/reinan47/repos",
                                "events_url": "https://api.github.com/users/reinan47/events{/privacy}",
                                "received_events_url": "https://api.github.com/users/reinan47/received_events",
                                "type": "User",
                                "site_admin": false
                            },
                            {
                                "login": "TSjadness",
                                "id": 95153192,
                                "node_id": "U_kgDOBavsKA",
                                "avatar_url": "https://avatars.githubusercontent.com/u/95153192?v=4",
                                "gravatar_id": "",
                                "url": "https://api.github.com/users/TSjadness",
                                "html_url": "https://github.com/TSjadness",
                                "followers_url": "https://api.github.com/users/TSjadness/followers",
                                "following_url": "https://api.github.com/users/TSjadness/following{/other_user}",
                                "gists_url": "https://api.github.com/users/TSjadness/gists{/gist_id}",
                                "starred_url": "https://api.github.com/users/TSjadness/starred{/owner}{/repo}",
                                "subscriptions_url": "https://api.github.com/users/TSjadness/subscriptions",
                                "organizations_url": "https://api.github.com/users/TSjadness/orgs",
                                "repos_url": "https://api.github.com/users/TSjadness/repos",
                                "events_url": "https://api.github.com/users/TSjadness/events{/privacy}",
                                "received_events_url": "https://api.github.com/users/TSjadness/received_events",
                                "type": "User",
                                "site_admin": false
                            },
                            {
                                "login": "viniVN7",
                                "id": 95314199,
                                "node_id": "U_kgDOBa5hFw",
                                "avatar_url": "https://avatars.githubusercontent.com/u/95314199?v=4",
                                "gravatar_id": "",
                                "url": "https://api.github.com/users/viniVN7",
                                "html_url": "https://github.com/viniVN7",
                                "followers_url": "https://api.github.com/users/viniVN7/followers",
                                "following_url": "https://api.github.com/users/viniVN7/following{/other_user}",
                                "gists_url": "https://api.github.com/users/viniVN7/gists{/gist_id}",
                                "starred_url": "https://api.github.com/users/viniVN7/starred{/owner}{/repo}",
                                "subscriptions_url": "https://api.github.com/users/viniVN7/subscriptions",
                                "organizations_url": "https://api.github.com/users/viniVN7/orgs",
                                "repos_url": "https://api.github.com/users/viniVN7/repos",
                                "events_url": "https://api.github.com/users/viniVN7/events{/privacy}",
                                "received_events_url": "https://api.github.com/users/viniVN7/received_events",
                                "type": "User",
                                "site_admin": false
                            }
                            ]';

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
        //$followings = $this->userRepository->findFollowings($url);

        $followingsDecoded = json_decode($this->followsMock, true);

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
        //$followers = $this->userRepository->findFollowers($url);

        $followersDecoded = json_decode($this->followsMock, true);

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
