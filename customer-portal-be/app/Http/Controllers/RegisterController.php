<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PolicyDetails;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        // Validate the request
        $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        // Fetch policy details using the provided email
        $policyDetails = PolicyDetails::where('email', $request->email)->first();

        if (!$policyDetails) {
            return response()->json(['status' => 'failed' ,'error' => 'Policy details not found for the given email.'], 404);
        }

        // Create a new user
        $user = new User([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'policy_id' => $policyDetails->id,
        ]);

        // Save the user
        $user->save();

        return response()->json(['status' => 'success' , 'message' => 'Registration is successfull. You would be redirected to Login screen soon']);
    }
}

