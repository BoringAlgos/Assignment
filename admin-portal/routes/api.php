<?php

use App\Http\Controllers\ClaimController;
use App\Http\Controllers\PolicyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
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
    $allowed = ['customer_portal' , 'eclaim-portal'];
    
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

Route::post('/getUser', [UserController::class, 'getAvailableUser'])->middleware('auth.client');

Route::post('/login', [LoginController::class, 'login']);
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout']);
    Route::get('/users', [UserController::class, 'index']);
    Route::put('/update-availability/{id}', [UserController::class, 'updateAvailability']);
    Route::get('/get-details' , [PolicyController::class , 'getPolicyDetails']);
    Route::post('/claim-list' , [ClaimController::class , 'fetchClaims']);
    Route::post('/claim-work' , [ClaimController::class , 'claimWork']);
    Route::get('/fetch-claim-work', [ClaimController::class , 'fetchClaimWork']);
    Route::post('/claim-work-approve', [ClaimController::class , 'approve']);
    // Add other authenticated routes as needed
});