<?php

namespace App\Http\Controllers;

use App\Models\ApiKeys;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class EClaimTokenController extends Controller
{
    public function generateToken(Request $request)
    {
        // Validate the request and get the client ID
        // $request->validate([
        //     'client_id' => 'required|in:eclaim-system',
        // ]);
        $clientId = $request->input('client_id');

        // Check if the client ID is allowed and get the API key
        $apiKey = ApiKeys::where('client_id', $clientId)->first();

        if (!$apiKey) {
            return response(['message' => 'Unauthorized client'], 401);
        }

        try {
            // $response = Http::post('http://172.30.0.1:8002/api/client-token', [
            //     'client_id' => $apiKey->api_key,
            // ]);
            // $data = $response->json();
            // \Log::info('Request made to e-claim system', ['response' => $response->body()]);

            // return ['token' => $data];
            $eclaimGateIP = env('GATEWAY_ECLAIM');
            $response = Http::post("http://$eclaimGateIP/api/generate-token-client", [
                'token_name' => 'customer_portal',
            ]);
            
            $tokenResponse = $response->json();
            \Log::info('Token Response:', $tokenResponse);
            
            $accessToken = $tokenResponse['token'];
            
            return['token' => $accessToken];
            
        } catch (\Exception $e) {
            return response(['error' => $e->getMessage()]);
        }
        
    }
}
