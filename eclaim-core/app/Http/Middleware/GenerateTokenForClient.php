<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\CustomPersonalAccessToken;

class GenerateTokenForClient
{
    public function handle(Request $request, Closure $next)
    {
        // Check if the client ID is allowed and get the API key
        $clientId = $request->input('client_id');
        $allowedClientIds = ['react-customer-portal', 'react-admin-portal'];

        if (!in_array($clientId, $allowedClientIds)) {
            return response(['message' => 'Unauthorized client'], 401);
        }

        // Generate a token for the allowed client
        $token = CustomPersonalAccessToken::create([
            'name' => 'client-token',
            'abilities' => ['*'], // Adjust the abilities as needed
            'tokenable_type' => null,
            'tokenable_id' => null,
        ]);

        // Add the generated token to the request for further use if needed
        $request->merge(['client_token' => $token]);

        return $next($request);
    }
}

