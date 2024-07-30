<?php

namespace App\Http\Controllers;

use App\Models\PolicyDetails;
use App\Models\VehicleDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VehicleController extends Controller
{
    public function getVehicleDetails(Request $request)
    {
        $policyId = $request->input('policyId');
        $user = Auth::user();

        // Ensure the requested policy belongs to the authenticated user
        $policy = PolicyDetails::where('id', $policyId)->where('email', $user->email)->first();

        if (!$policy) {
            return response()->json(['error' => 'Invalid policy ID'], 404);
        }

        $vehicleDetailsList = VehicleDetails::where('policy_id', $policyId)->get(['id', 'make', 'model', 'year']);

        return response()->json(['vehicleDetailsList' => $vehicleDetailsList]);
    }
}
