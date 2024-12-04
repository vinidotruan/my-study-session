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
        return response()->json([...$userData, 'token' => $token, 'user' => $user]);
    }

    public function getAuthUrl(Request $request) {
        return response()->json("teste");
        $client_id = config('services.twitch.client_id');
        $redirect_uri = config('services.twitch.redirect_uri');
        return response()->json("https://id.twitch.tv/oauth2/authorize?force_verify=true&client_id={$client_id}&response_type=code&redirect_uri={$redirect_uri}&scope=user:read:email&claims=email");
    }

    public function logout(Request $request, TwitchService $twitchService)
    {
        auth()->user()->currentAccessToken()->delete();
        $twitchService->logout();

        return response()->json(['data' => 'success']);
    }
}
