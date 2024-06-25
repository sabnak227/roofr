<?php

use App\Http\Controllers\ParkingLotController;
use App\Http\Controllers\ParkingSpotController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/parking-spot/{parkingSpot}/park', [ParkingSpotController::class, 'park']);
Route::post('/parking-spot/{parkingSpot}/unpark', [ParkingSpotController::class, 'unpark']);
Route::get('/parking-lot', [ParkingLotController::class, 'index']);
Route::get('/parking-lot/{parkingSpot}', [ParkingLotController::class, 'get']);
