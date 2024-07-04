<?php

namespace App\Http\Controllers;

use App\Contracts\Services\UserProfileServiceInterface;
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
            return response()->json($this->userProfileService->getUserProfile($url), 200);
        } catch (\Exception $e){
            return response()->json(['error' => 'Erro ao tentar se comunicar.'], 400);
        }
    }
}
