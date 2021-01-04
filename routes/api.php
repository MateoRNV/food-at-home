<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\OrderController;
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

Route::get('products',                                  [ProductController::class, 'index']);

Route::post('users/photos',                             [UserController::class, 'uploadPhoto']);

Route::get('customers',                                 [CustomerController::class, 'index']);
Route::post('customers',                                [CustomerController::class, 'create']); // create customer
Route::post('customer/photos',                          [CustomerController::class, 'uploadPhoto']);

Route::get('users/employees',                           [UserController::class, 'getEmployees']);
Route::get('orders/active',                             [OrderController::class, 'getActiveOrders']);


Route::middleware('auth:sanctum')->get('user/me',       [UserController::class, 'me']);


Route::middleware(['auth:sanctum'])->group(function (){
    
    Route::get('customers/{id}',                  [CustomerController::class, 'me']);
    Route::put('customers/{id}',                  [CustomerController::class, 'update']);    //edit customer


    Route::get('users/{id}',                      [UserController::class, 'info']);
    Route::post('users/{id}/photos',              [UserController::class, 'updatePhoto']);


    Route::get('orders',                          [OrderController::class, 'index']);

});

Route::middleware(['customer'])->group(function (){

    Route::get('customers/{id}/orders',           [CustomerController::class, 'getCustomerOrders']); //solo customer
   
    Route::post('orders',                         [OrderController::class, 'create']); // create order //solo customer

});

Route::middleware(['employee'])->group(function (){

    Route::get('orders/{id}',                               [OrderController::class, 'getOrderById']);
    Route::get('orders/status/{status}',                    [OrderController::class, 'getStatus']);
    Route::post('orders/{id}/status/{status}',              [OrderController::class, 'setOrderStatus']); 
   
    Route::put('users/{id}',                                [UserController::class, 'update']); //edit user

});

Route::middleware(['admin'])->group(function (){

    Route::post('products',                                 [ProductController::class, 'create']);
    Route::post('products/photos',                          [ProductController::class, 'uploadPhoto']);
    Route::post('products/{id}/photos/',                    [ProductController::class, 'updatePhoto']);
    Route::put('products/{id}',                             [ProductController::class, 'update']);
    Route::delete('products/{id}',                          [ProductController::class, 'delete']);
    
    Route::get('users',                                     [UserController::class, 'index']);
    Route::post('users',                                    [UserController::class, 'create']); // create user
    Route::delete('users/{user}',                           [UserController::class, 'destroy']);//delete user
    Route::post('users/{id}/block',                         [UserController::class, 'block']);
    Route::post('users/{id}/unblock',                       [UserController::class, 'unblock']);
    Route::get('users/employees/{id}/current',              [UserController::class, 'getCurrentOrder']);


});