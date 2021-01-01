<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\OrderItemController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\CustomerController;



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

Route::post('login',            [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);

// Protect a route so it's only accessible to authenticated users
//Route::middleware('auth:sanctum')->get('orders', [OrderController::class, 'index']);

Route::get('orders',                       [OrderController::class, 'index']);
Route::get('orders/status/{status}',       [OrderController::class, 'getStatus']);
Route::get('orders/{id}/{status}',         [OrderController::class, 'getOrdersByCook']);
Route::post('orders/{id}/status/{status}', [OrderController::class, 'setOrderStatus']); // Change to patch
Route::get('orders/{id}',                  [OrderController::class, 'getOrderById']);

Route::post('orders',                      [OrderController::class, 'create']); // create order

Route::post('order/item',                   [OrderItemController::class, 'create']); // create order

Route::get('products',                       [ProductController::class, 'index']);
Route::post('products',                      [ProductController::class, 'create']);
Route::post('products/photos',               [ProductController::class, 'uploadPhoto']);
Route::post('products/photos/{id}',          [ProductController::class, 'updatePhoto']);
Route::put('products/{id}',                  [ProductController::class, 'update']);
Route::delete('products/{id}',               [ProductController::class, 'delete']);


Route::get('users',             [UserController::class, 'index']);
Route::get('user/{id}',         [UserController::class, 'info']);
Route::post('users',            [UserController::class, 'create']); // create user
Route::put('users/{id}',        [UserController::class, 'update']); //edit user
Route::delete('users/{user}',   [UserController::class, 'destroy']);//delete user

Route::middleware('auth:sanctum')->get('users/me', [UserController::class, 'me']);

Route::get('customers',              [CustomerController::class, 'index']);
// Route::get('customers/{id}',         [CustomerController::class, 'me']);
Route::get('customers/{id}/orders',         [CustomerController::class, 'getCustomerOrders']);
Route::post('customers',             [CustomerController::class, 'create']); // create customer
Route::put('customers/{id}',         [CustomerController::class, 'update']);    //edit customer
Route::post('customer/photos',       [CustomerController::class, 'uploadPhoto']);


