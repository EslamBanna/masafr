<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\Masafr\MasafrController;
use App\Http\Controllers\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});




Route::group(['prefix' => 'common'], function () {
    Route::post('/customer-services', [Controller::class, 'CustomerService']);
});



Route::group(['prefix' => 'user'], function () {
    Route::post('/create-account', [UserController::class, 'createUser']);
    Route::post('/login', [UserController::class, 'login']);
});

Route::group(['prefix' => 'masafr'], function () {
    Route::post('/create-account', [MasafrController::class, 'createMasafr']);
    Route::post('/login', [MasafrController::class, 'login']);
});

Route::group(['prefix' => 'auth/user', 'middleware' => 'checkAuth:user-api'], function () {
    Route::post('/store-transaction', [UserController::class, 'storeTransaction']);
    Route::post('/logout', [UserController::class, 'logout']);
});


Route::group(['prefix' => 'auth/masafr', 'middleware' => 'checkAuth:masafr-api'], function () {
    Route::post('/logout', [MasafrController::class, 'logout']);
});
