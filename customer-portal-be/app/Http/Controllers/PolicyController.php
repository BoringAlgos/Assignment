<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PolicyDetails;
use App\Models\Otp;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;

class PolicyController extends Controller
{
    public function checkPolicy($policyDetails)
    {
        //Validate the Policy Number
        $toEmail = PolicyDetails::where('policy_number', $policyDetails)->value('email');
        if($toEmail == '')
        {
            return response()->json([
                'status' => 'failed',
                'message' => 'Policy Details not found',
            ]);
        }
        //$otp = rand(100000, 999999);
        $otp = 12345;
        // Save OTP in the database
        Otp::create([
            'policy_number' => $policyDetails,
            'otp' => $otp,
        ]);

        try{
            Mail::to($toEmail)->send(new OtpMail($otp));
        }catch(\Exception $e){
            //Handle Error
        }
        

        return response()->json([
            'status' => 'success',
            'message' => 'Kindly enter the OTP received in your registered email address for authentication.',
        ]);
    }

    public function validateOtp(Request $request)
    {
        $otp = $request->post('otp');
        $policy = $request->post('policy');
        $otpExpiration =  date('Y-m-d H:i:s', strtotime(' +5 minutes ')); 
        $otpDetails = Otp::where('otp', $otp)
                     ->where('policy_number', $policy)
                     ->where('created_at', '<=', $otpExpiration)
                     ->first();
        // return response()->json([
        //     'status' => 'success',
        //     'otp' => $otp,
        //     'policy' => $policy,
        //     'otpExpiration' => $otpExpiration
        // ]);
        if ($otpDetails) {
            $toEmail = PolicyDetails::where('policy_number', $policy)->value('email');
            $otpDetails->delete();
            if($toEmail != '')
            {
                return response()->json([
                    'status' => 'success',
                    'message' => 'OTP successfully Authenticated',
                    'email' => $toEmail,
                ]);
            }else{
                return response()->json([
                    'status' => 'failure',
                    'message' => 'Email Does not exists',
                ]);
            }
             
        } else {
            return response()->json([
                'status' => 'failure',
                'message' => 'OTP has expired or is invalid.',
            ], 422);
        }
    }
}

