<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClaimsController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/storage/{filename}', function ($filename) {
    $path = storage_path("app/public/claim_documents/{$filename}");

    if (file_exists($path)) {
        return response()->file($path);
    } else {
        return response()->json(['error' => 'File not found'], 404);
    }
})->where('filename', '.*');
