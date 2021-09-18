<?php

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


Route::post('/login',[UserController::class,'login']);

Route::group(['prefix' => 'user'],function(){
    Route::post('/create-account',[UserController::class,'createUser']);
});

Route::group(['prefix' => 'auth', 'middleware' => 'checkAuth:user-api'], function(){
// Route::get('/test2',[UserController::class,'test']);

});

// Route::get('/test',[UserController::class,'test']);
