<?php

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
Route::post('/register', [App\Http\Controllers\API\AuthController::class, 'register']);
Route::post('/login', [App\Http\Controllers\API\AuthController::class, 'login']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
  

// Route::group(['middleware' => ['auth:sanctum']], function () {
Route::group(['middleware' => ['api']], function () {
    Route::get('products/', 'App\Http\Controllers\API\ProductController@index');
    Route::get('products/{id}', 'App\Http\Controllers\API\ProductController@show');

});

Route::group(['middleware' => ['api', 'auth:sanctum']], function() {
    Route::post('cart/add', 'App\Http\Controllers\API\OrderController@addToCart');
    Route::post('checkout', 'App\Http\Controllers\API\OrderController@checkout');
});