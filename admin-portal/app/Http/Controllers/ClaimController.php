<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\TokenService;

class ClaimController extends Controller
{
    protected $tokenService;

    public function __construct(TokenService $tokenService)
    {
        $this->tokenService = $tokenService;
    }
    public function fetchClaims(Request $request)
    {
        try {
            $eclaimGateIP = env('GATEWAY_ECLAIM');
            $user = $request->user();
            $accessToken = $this->tokenService->getToken($user->id, $eclaimGateIP, 'ECLAIM');
            if ($accessToken) {
                $role = $request->role;
                $user = $request->user;

                // Prepare the authorization header with the access token
                $headers = [
                    'Authorization' => 'Bearer ' . $accessToken,
                ];

                // Perform the GET request with headers and query parameters
                $response = Http::withHeaders($headers)->post("http://$eclaimGateIP/api/claim-list", [
                    'token_name' => 'admin_portal',
                    'user' => $user,
                    'role' => $role,
                ]);

                return ($response);
            }


        } catch (\Exception $e) {
            return response(['error' => $e->getMessage()]);
        }
    }

    public function claimWork(Request $request)
    {
        try {
            $eclaimGateIP = env('GATEWAY_ECLAIM');
            $user = $request->user();
            $accessToken = $this->tokenService->getToken($user->id, $eclaimGateIP, 'ECLAIM');
            if ($accessToken) {
                $claimId = $request->claimId;
                $items = $request->items;

                // Prepare the authorization header with the access token
                $headers = [
                    'Authorization' => 'Bearer ' . $accessToken,
                ];

                // Perform the GET request with headers and query parameters
                $response = Http::withHeaders($headers)->post("http://$eclaimGateIP/api/claim-review", [
                    'token_name' => 'admin_portal',
                    'claimId' => $claimId,
                    'items' => $items,
                ]);

                return response($response);
            }
        } catch (\Exception $e) {
            return response(['error' => $e->getMessage()], 500);
        }
    }

    public function fetchClaimWork(Request $request)
    {
        try {
            $eclaimGateIP = env('GATEWAY_ECLAIM');
            $user = $request->user();
            $accessToken = $this->tokenService->getToken($user->id, $eclaimGateIP, 'ECLAIM');
            if ($accessToken) {
                $claimId = $request->claimId;

                // Prepare the authorization header with the access token
                $headers = [
                    'Authorization' => 'Bearer ' . $accessToken,
                ];

                // Perform the GET request with headers and query parameters
                $response = Http::withHeaders($headers)->get("http://$eclaimGateIP/api/fetch-claim-work", [
                    'token_name' => 'admin_portal',
                    'claimId' => $claimId
                ]);

                return response($response);
            }
        } catch (\Exception $e) {
            return response(['error' => $e->getMessage()], 500);
        }
    }

    public function approve(Request $request)
    {
        try {
            $eclaimGateIP = env('GATEWAY_ECLAIM');
            $user = $request->user();
            $accessToken = $this->tokenService->getToken($user->id, $eclaimGateIP, 'ECLAIM');
            if ($accessToken) {
                $claimId = $request->claimId;
                $items = $request->items;
                $formUpdated = $request->formUpdated;
                // Prepare the authorization header with the access token
                $headers = [
                    'Authorization' => 'Bearer ' . $accessToken,
                ];

                // Perform the GET request with headers and query parameters
                $response = Http::withHeaders($headers)->post("http://$eclaimGateIP/api/claim-work-approve", [
                    'token_name' => 'admin_portal',
                    'claimId' => $claimId,
                    'formUpdated' => $formUpdated,
                    'items' => $items,
                ]);

                return response($response);
            }
        } catch (\Exception $e) {
            return response(['error' => $e->getMessage()], 500);
        }
    }
}
