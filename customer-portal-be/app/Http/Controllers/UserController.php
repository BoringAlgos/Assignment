<?php

namespace App\Http\Controllers;

use App\Models\PolicyDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
class UserController extends Controller
{
    public function getUserDetails()
    {

        $user = Auth::user();

        $policyList = PolicyDetails::where('email', $user->email)->get(['id', 'policy_number']);

        return response()->json(['policyList' => $policyList]);
    }

    public function getDetails(Request $request)
    {
        $user_id = $request->customer_id;
        $policy_id = $request->policy_id;
        // Fetch user with policies and vehicles
        $user = User::find($user_id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Extracting relevant information
        $customerInfo = [
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            // Add other user details as needed
        ];


        $policyInfo = PolicyDetails::with('vehicle')->find($policy_id);
        $policyDetails = [
            'policy_number' => $policyInfo->policy_number,
            'policy_id' => $policyInfo->id,
            'expiry' => $policyInfo->end_date
        ];
        $vehicleDetails =  $policyInfo->vehicle ? [
                            'vehicle_id' => $policyInfo->vehicle->id,
                            'registration_id' => $policyInfo->vehicle->registration_number,
                            'make' => $policyInfo->vehicle->make,
                            'model' => $policyInfo->vehicle->model,
                            'color' => $policyInfo->vehicle->color
                            // Add other vehicle details as needed
                        ] : null;
        

        return response()->json(['customerInfo' => $customerInfo, 'policyInfo' => $policyDetails, 'vehicleInfo' => $vehicleDetails]);
    }

}
