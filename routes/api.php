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
    Route::post('/all-trips', [Controller::class, 'getAllTrips']);
    Route::post('/create-account', [UserController::class, 'createUser']);
    Route::post('/login', [UserController::class, 'login']);
});

Route::group(['prefix' => 'masafr'], function () {
    Route::post('/all-request-services', [Controller::class, 'getAllRequestServices']);
    Route::post('/create-account', [MasafrController::class, 'createMasafr']);
    Route::post('/login', [MasafrController::class, 'login']);
    Route::post('/add-masafr-info', [MasafrController::class, 'addMasafrInfo']);
});

Route::group(['prefix' => 'auth/user'], function () {

// Route::group(['prefix' => 'auth/user', 'middleware' => 'checkAuth:user-api'], function () {
    Route::post('/get-user-info', [UserController::class, 'me']);
    Route::post('/store-transaction', [UserController::class, 'storeTransaction']);
    Route::post('/get-transactions', [UserController::class, 'getTransactions']);
    Route::post('/make-comment', [UserController::class, 'makeComment']);
    Route::post('/get-comments', [UserController::class, 'getComments']);
    Route::post('/update-comment', [UserController::class, 'updateComment']);
    Route::post('/update-user-info', [UserController::class, 'updateUserInfo']);
    Route::post('/make-complain', [UserController::class, 'makeComplain']);
    Route::post('/get-complains', [UserController::class, 'getComplains']);
    Route::post('/get-notifications', [UserController::class, 'getNotifications']);
    Route::post('/store-notifications', [UserController::class, 'storeNotifications']);
    Route::post('/create-request-service', [UserController::class, 'createRequestService']);
    Route::post('/get-trip', [UserController::class, 'getTrip']);
    Route::post('/send-message', [UserController::class, 'sendMessage']);
    Route::post('/get-messages', [UserController::class, 'getMessages']);
    Route::post('/search-trips', [UserController::class, 'searchTrips']);
    Route::post('/all-free-services', [UserController::class, 'getAllFreeServices']);
    Route::post('/search-free-service', [UserController::class, 'searchFreeService']);

    Route::post('/logout', [UserController::class, 'logout']);
});

Route::group(['prefix' => 'auth/masafr'], function () {

// Route::group(['prefix' => 'auth/masafr', 'middleware' => 'checkAuth:masafr-api'], function () {
    Route::post('/get-user-info', [MasafrController::class, 'me']);
    Route::post('/update-masafr-info', [MasafrController::class, 'updateMasafrInfo']);
    Route::post('/store-transaction', [MasafrController::class, 'storeTransaction']);
    Route::post('/get-transactions', [MasafrController::class, 'getTransactions']);
    Route::post('/make-comment', [MasafrController::class, 'makeComment']);
    Route::post('/get-comments', [MasafrController::class, 'getComments']);
    Route::post('/update-comment', [MasafrController::class, 'updateComment']);
    Route::post('/make-complain', [MasafrController::class, 'makeComplain']);
    Route::post('/get-complains', [MasafrController::class, 'getComplains']);
    Route::post('/create-trip', [MasafrController::class, 'createTrip']);
    Route::post('/update-trip', [MasafrController::class, 'updateTrip']);
    Route::post('/delete-trip', [MasafrController::class, 'deleteTrip']);
    Route::post('/get-notifications', [MasafrController::class, 'getNotifications']);
    Route::post('/store-notifications', [MasafrController::class, 'storeNotifications']);
    Route::post('/get-request-service', [MasafrController::class, 'getRequestService']);
    Route::post('/send-message', [MasafrController::class, 'sendMessage']);
    Route::post('/get-messages', [MasafrController::class, 'getMessages']);
    Route::post('/create-free-service', [MasafrController::class, 'createFreeService']);


    ################ not working ############################
    Route::post('/search-request-service', [MasafrController::class, 'searchRequestService']);
    #############################################
    Route::post('/logout', [MasafrController::class, 'logout']);
});
