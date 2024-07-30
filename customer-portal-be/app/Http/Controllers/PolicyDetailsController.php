<?php

// app/Http/Controllers/PolicyDetailsController.php

namespace App\Http\Controllers;

use App\Models\PolicyDetails;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PolicyDetailsController extends Controller
{
    public function getDetails($email)
    {
        $user = User::where('email', $email)->first();
        if ($user) {
        $policyDetails = PolicyDetails::where('id', $user->policy_id)->first();
        $policyDetails->name = $user->name;

        if ($policyDetails) {
            return response()->json([
                'status' => 'success',
                'data' => $policyDetails,
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Policy details not found.',
            ], 404);
        }
      }else{
        return response()->json([
            'status' => 'error',
            'message' => 'User details not found.',
        ], 404);
      }
    }

    public function updateDetails(Request $request)
    {
        // Retrieve user by email
        $user = User::where('email', $request->input('email'))->first();

        // Check if the user is found
        if ($user) {
            $policyDetails = PolicyDetails::where('id', $user->policy_id)->first();

            if ($policyDetails) {
                $request->validate([
                    'name' => 'nullable|string',
                    'address' => 'nullable|string',
                    'billing_cycle' => 'nullable|in:annual,monthly,semester,quarterly',
                ]);

                // Update user name if provided
                if ($request->has('name')) {
                    $user->name = $request->input('name');
                    $user->save();
                }

                // Update policy details
                $policyDetails->address = $request->input('address', $policyDetails->address);
                $policyDetails->billing_cycle = $request->input('billing_cycle', $policyDetails->billing_cycle);
                $policyDetails->save();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Policy details updated successfully.',
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Policy details not found.',
                ], 404);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found.',
            ], 404);
        }
    }
}

