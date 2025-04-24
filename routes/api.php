<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\TypeServiceController;
use App\Http\Controllers\ImageController; // Import ImageController yang benar

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

Route::get('/services', [ServiceController::class, 'index']);
Route::post('/services', [ServiceController::class, 'store']);
Route::get('/services/{id}', [ServiceController::class, 'show']);
Route::put('/services/{id}', [ServiceController::class, 'update']);
Route::delete('/services/{id}', [ServiceController::class, 'destroy']);
Route::get('/services/search/{query}', [ServiceController::class, 'search']);

Route::post('/user/register', [UserAuthController::class, 'register']);
Route::post('/user/login', [UserAuthController::class, 'login']);
Route::get('/users', [UserAuthController::class, 'index']);
Route::delete('/users/{id}', [UserAuthController::class, 'destroy']);
Route::put('/users/{id}', [UserAuthController::class, 'editProfile']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/user/logout', [UserAuthController::class, 'logout']);
    Route::get('/user/profile', function (Request $request) {
        return response()->json($request->user());
    });
});

Route::resource('type-services', TypeServiceController::class);

// Route untuk ImageController
Route::post('/carousel/upload', [ImageController::class, 'uploadCarousel']);
Route::get('/carousel', [ImageController::class, 'getCarouselImages']);
Route::delete('/carousel/{id}', [ImageController::class, 'deleteCarouselImage']);
