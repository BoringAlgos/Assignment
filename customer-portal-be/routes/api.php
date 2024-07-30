<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PolicyDetailsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\EClaimTokenController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/generate-token-client', function (Request $request) {
        // Check for the specific condition (e.g., presence of a certain string)
        $allowed = ['eclaim-portal' , 'admin_portal'];
        
        if (in_array($request->post('token_name'), $allowed)) {
            $clientKey = 'client-token-' . $request->post('token_name');
        
            // Check if the token already exists in the cache
            $token = cache($clientKey);
        
            if (!$token) {
                // Token does not exist, create a new one
                $token = $request->post('token_name') . Str::random(40);
        
                // Store the new token in the cache
                cache([$clientKey => $token], now()->addHours(1));
            }
        
            return response()->json(['token' => $token]);
        }
        
    
        return response()->json(['error' => 'Invalid client condition'], 200);
    })->middleware('api');

Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('get-details/{email}', [PolicyDetailsController::class, 'getDetails']);
        Route::put('update-details', [PolicyDetailsController::class, 'updateDetails']);
        Route::get('user/policy-details', [UserController::class, 'getUserDetails']);
        Route::get('user/vehicle-details', [VehicleController::class, 'getVehicleDetails']);
        Route::post('/generate-eclaim-token', [EClaimTokenController::class, 'generateToken']);
});

Route::get('/check-policy/{policyNumber}', [PolicyController::class, 'checkPolicy']);
Route::post('/validate-otp', [PolicyController::class, 'validateOtp']);
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);
Route::get('/get-details', [UserController::class, 'getDetails'])->middleware('auth.client');


