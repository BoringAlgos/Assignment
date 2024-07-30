<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClaimsController;
use Illuminate\Support\Str;
// Route to handle client ID and generate Sanctum token

Route::post('/generate-token-client', function (Request $request) {
    // Check for the specific condition (e.g., presence of a certain string)
    $allowed = ['customer_portal' , 'admin_portal'];
    
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


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// Route::middleware('auth:client')->group(function () {
//     Route::post('/claims', [ClaimsController::class, 'store']);
// });

Route::post('/claims', [ClaimsController::class, 'store'])->middleware('auth.client');
Route::post('/claim-list', [ClaimsController::class, 'list'])->middleware('auth.client');
Route::post('/claim-review', [ClaimsController::class, 'review'])->middleware('auth.client');
Route::get('/fetch-claim-work', [ClaimsController::class, 'fetchClaimWork'])->middleware('auth.client');
Route::post('/claim-work-approve',[ClaimsController::class,'approve'])->middleware('auth.client');
