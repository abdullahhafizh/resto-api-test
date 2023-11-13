<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{FoodController, IngredientController, HowToController};

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

Route::group(['prefix' => 'v1'], function () {
    Route::group(['prefix' => 'food'], function () {
        Route::post('list', [FoodController::class, 'index']);
        Route::post('view/{id}', [FoodController::class, 'show']);
        Route::post('add', [FoodController::class, 'store']);
        Route::post('update/{id}', [FoodController::class, 'update']);
        Route::post('remove/{id}', [FoodController::class, 'destroy']);
    });

    Route::group(['prefix' => 'ingredient'], function () {
        Route::post('list', [IngredientController::class, 'index']);
        Route::post('view/{id}', [IngredientController::class, 'show']);
        Route::post('add', [IngredientController::class, 'store']);
        Route::post('update/{id}', [IngredientController::class, 'update']);
        Route::post('remove/{id}', [IngredientController::class, 'destroy']);
    });

    Route::group(['prefix' => 'how-to'], function () {
        Route::post('list', [HowToController::class, 'index']);
        Route::post('view/{id}', [HowToController::class, 'show']);
        Route::post('add', [HowToController::class, 'store']);
        Route::post('update/{id}', [HowToController::class, 'update']);
        Route::post('remove/{id}', [HowToController::class, 'destroy']);
    });
});

