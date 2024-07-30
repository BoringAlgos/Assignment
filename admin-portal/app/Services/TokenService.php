<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class TokenService
{
    public function getToken($userId , $gatewayIp ,$gateway)
    {
        
        $tokenCacheKey = 'access_token_' .$gateway.'_'. $userId;

        // Check if a valid token is already in the cache
        $accessToken = Cache::get($tokenCacheKey);

        if (!$accessToken) {
            // No valid token in cache, request a new one
            $response = Http::post("http://$gatewayIp/api/generate-token-client", [
                'token_name' => 'admin_portal',
            ]);

            $tokenResponse = $response->json();

            $accessToken = $tokenResponse['token'] ?? null;

            if ($accessToken) {
                // Cache the new token with an expiration time (1 hour)
                Cache::put($tokenCacheKey, $accessToken, 3600); // 3600 seconds = 1 hour
            }
        }

        return $accessToken;
    }
}
