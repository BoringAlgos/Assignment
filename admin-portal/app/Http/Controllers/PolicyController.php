<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\TokenService;

class PolicyController extends Controller
{
    protected $tokenService;

    public function __construct(TokenService $tokenService)
    {
        $this->tokenService = $tokenService;
    }
    public function getPolicyDetails(Request $request)
    {
        try {
            $eclaimGateIP = env('GATEWAY_CUSTOMER');
            $user = $request->user();
            $accessToken = $this->tokenService->getToken($user->id , $eclaimGateIP ,'ADMIN');
            if ($accessToken) {
                $user_id = $request->customer_id;
                $policy_id = $request->policy_id;

                // Prepare the authorization header with the access token
                $headers = [
                    'Authorization' => 'Bearer ' . $accessToken,
                ];

                // Perform the GET request with headers and query parameters
                $response = Http::withHeaders($headers)->get("http://$eclaimGateIP/api/get-details", [
                    'token_name' => 'admin_portal',
                    'policy_id' => $policy_id,
                    'customer_id' => $user_id,
                ]);

                return $response;
            }


        } catch (\Exception $e) {
            return response(['error' => $e->getMessage()]);
        }
    }


    // You can add more methods based on your requirements, e.g., show, store, update, destroy.
}
