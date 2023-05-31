<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\CustomerController;
use App\Http\Controllers\API\AuthController;

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


Route::get('/',function(){
    return 'api';
});
// Route::middleware('api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
});


    Route::middleware('auth:api')->get('/users',[UserController::class ,'index']);

    Route::middleware('auth:api')->get('/customers',[CustomerController::class ,'index']);
    Route::middleware('auth:api')->get('/customer/{id}',[CustomerController::class ,'show']);

