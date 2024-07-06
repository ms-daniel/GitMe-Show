<?php

namespace App\Http\Controllers;

use App\Contracts\Services\UserProfileServiceInterface;
use App\Models\UserProfileModel;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;

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

        } catch (ClientException $e) {
            return response()->json([$e->getMessage()], $e->getCode());
        } catch (ServerException $e) {
            return response()->json([$e->getMessage()], $e->getCode());
        } catch (\Exception $e){
            return response()->json([$e->getMessage()], 400);
        }
    }

    public function getFollowings(Request $request)
    {
        $url = $request->query('url');

        if (empty($url)) {
            return response()->json(['error' => 'URL vazia'], 400);
        }

        $url .= '/following';

        try{
            $userFollowings = $this->userProfileService->getUserFollowings($url);
        } catch (ClientException $e) {
            return response()->json([$e->getMessage()], $e->getCode());
        } catch (ServerException $e) {
            return response()->json([$e->getMessage()], $e->getCode());
        } catch (\Exception $e){
            return response()->json([$e->getMessage()], 400);
        }
        return response($userFollowings, 200);
    }

    public function getFollowers(Request $request)
    {
        $url = $request->query('url');

        if (empty($url)) {
            return response()->json(['error' => 'URL vazia'], 400);
        }

        $url .= '/followers';

        try{
            $userFollowers = $this->userProfileService->getUserFollowers($url);
        } catch (ClientException $e) {
            return response()->json([$e->getMessage()], $e->getCode());
        } catch (ServerException $e) {
            return response()->json([$e->getMessage()], $e->getCode());
        } catch (\Exception $e){
            return response()->json([$e->getMessage()], 400);
        }
        return response($userFollowers, 200);
    }
}
