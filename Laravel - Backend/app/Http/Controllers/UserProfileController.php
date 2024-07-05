<?php

namespace App\Http\Controllers;

use App\Contracts\Services\UserProfileServiceInterface;
use App\Models\UserProfileModel;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class UserProfileController extends Controller
{
    protected $userProfileService;

    public function __construct(UserProfileServiceInterface $userProfileService)
    {
        $this->userProfileService = $userProfileService;
    }

    public function get(Request $request)
    {
        $url = $request->query('url');

        if (empty($url)) {
            return response()->json(['error' => 'URL vazia'], 400);
        }

        try{
            $userProfile = $this->userProfileService->getUserProfile($url);

            return response($userProfile, 200);

        } catch (\Exception $e){
            return response()->json(['error' => 'Erro ao tentar se comunicar com servidor.'], 400);
        }
    }

    public function getFollowings(Request $request)
    {
        $url = $request->query('url');

        if (empty($url)) {
            return response()->json(['error' => 'URL vazia'], 400);
        }

        try{
            $userFollowings = $this->userProfileService->getUserFollowings($url);

            return response($userFollowings, 200);

        } catch (\Exception $e){
            return response()->json(['error' => 'Erro ao tentar se comunicar com servidor.'], 400);
        }
    }
}
