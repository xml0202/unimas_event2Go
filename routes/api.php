<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Event;
use App\Http\Resources\EventResource;
use App\Http\Controllers\API\EventController;
use App\Http\Controllers\API\AuthController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('verify-otp', [AuthController::class, 'verifyOtp']);

Route::group(["middleware" => ['auth:sanctum', EnsureEmailVerifiedForAPI::class]], function (){
    Route::post('logout', [AuthController::class, 'logout']);
    
    Route::get('events', [EventController::class, 'index']);
});

// Route::get('events', [EventController::class, 'index']);
Route::post('events', [EventController::class, 'store']);
Route::put('events/{id}', [EventController::class, 'update']);
Route::get('events/{id}', [EventController::class, 'show']);
Route::delete('events/{id}', [EventController::class, 'destroy']);
Route::get('events/{event}/comments', [EventController::class, 'get_current_event_comments']);



