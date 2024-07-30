<?php

// Custom middleware for client authentication
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthenticateClient
{
    public function handle($request, Closure $next)
    {
        // Check if the Authorization header is present
        if ($request->hasHeader('Authorization')) {
            $token = str_replace('Bearer ', '', $request->header('Authorization'));
            // Extract the client name from the token
            // Extract the client name from the token
            \Log::info('Token Name:', ['token-name' => $token]);
            preg_match('/^.*?(\w+)_portal/', $token, $matches);
            $clientName = $matches[1] ?? null;
            \Log::info('Client Name:', ['client-name' => $clientName]);
            if ($clientName) {
                // Generate the client key based on the extracted client name
                $clientKey = 'client-token-' . $clientName.'_portal';

                // Validate the token against the client key
                if (Cache::has($clientKey) && $token === Cache::get($clientKey)) {
                    return $next($request);
                }
            }
        }

        return response()->json(['error' => 'Unauthenticated'], 401);
    }
}
