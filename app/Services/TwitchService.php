<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class TwitchService
{
    private string $clientId;
    private string $clientSecret;
    private string $redirectUri;

    public function __construct()
    {
        $this->clientId = config('services.twitch.client_id');
        $this->clientSecret = config('services.twitch.client_secret');
        $this->redirectUri = config('services.twitch.redirect_uri');
    }

    /**
     * @throws Exception
     */
    public function handleCallback(string $code): array
    {
        $tokenResponse = Http::asForm()->post('https://id.twitch.tv/oauth2/token', [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'code' => $code,
            'grant_type' => 'authorization_code',
            'redirect_uri' => $this->redirectUri,
        ]);

        if (!$tokenResponse->successful()) {
            throw new Exception('Falha ao obter token da Twitch: ' . $tokenResponse->body());
        }

        $tokenData = $tokenResponse->json();

        $userResponse = Http::withHeaders([
            'Client-ID' => $this->clientId,
            'Authorization' => 'Bearer ' . $tokenData['access_token'],
        ])->get('https://api.twitch.tv/helix/users');


        if (!$userResponse->successful()) {
            throw new Exception('Falha ao obter dados do usuário da Twitch: ' . $userResponse->body());
        }

        $userData = $userResponse->json();
        DB::table('users')->updateOrInsert(
            ['twitch_id' => $userData['data'][0]['id']],
            [
                'twitch_id' => $userData['data'][0]['id'],
                'login' => $userData['data'][0]['login'],
                'email' => $userData['data'][0]['email'],
                'display_name' => $userData['data'][0]['display_name'],
                'profile_image_url' => $userData['data'][0]['profile_image_url'],
                'access_token' => $tokenData['access_token'],
                'refresh_token' => $tokenData['refresh_token'],
                'expires_in' => now()->addSeconds($tokenData['expires_in']),
            ]
        );

        return [
            'twitch_id' => $userData['data'][0]['id'],
            'access_token' => $tokenData['access_token'],
        ];
    }

    public function handleFollowedChannels(): \Illuminate\Http\JsonResponse
    {
        $userId = session('twitch_id');
        $response = Http::get(`https://api.twitch.tv/helix/channels/followed?user_id=$userId`);

        if(!$response->successful()) {
            throw new Exception('Falha ao obter canais seguidos: ' . $response->body());
        }

        return response()->json();
    }
}
