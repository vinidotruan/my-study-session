<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\TwitchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwitchController extends Controller
{
    public function handleTwitchCallback(Request $request, TwitchService $twitchService)
    {
        $userData = $twitchService->handleCallback($request->query('code'));
        $user = User::where(['twitch_id' => $userData['twitch_id']])->first();
        if(Auth::loginUsingId($user->id)) {
            $token = $user->createToken('login')->plainTextToken;
        }
        return response()->json([...$userData, 'token' => $token]);
    }

    public function getAuthUrl(Request $request) {
        return response()->json("https://id.twitch.tv/oauth2/authorize?force_verify=true&client_id=od9y5mt2nzyh03zwdfsl86g8nwb2x3&response_type=code&redirect_uri=http://localhost:8000/api/auth/callback&scope=user:read:email&claims=email");
    }

    /**
     * @throws \Exception
     */
    public function getFollowedChannels(Request $request, TwitchService $twitchService): JsonResponse
    {
        $data = $twitchService->handleFollowedChannels();
        return response()->json($data);
    }
}
