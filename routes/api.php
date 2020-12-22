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

Route::get('orders',            [OrderController::class, 'index']);

Route::get('products',          [ProductController::class, 'index']);

Route::get('users',             [UserController::class, 'index']);
Route::get('user/{id}',         [UserController::class, 'info']);
Route::post('users',            [UserController::class, 'create']); // create user
Route::put('users/{user}',      [UserController::class, 'update']); //edit user
Route::delete('users/{user}',   [UserController::class, 'destroy']);//delete user

Route::middleware('auth:sanctum')->get('users/me', [UserController::class, 'me']);

Route::get('customers',         [CustomerController::class, 'index']);
Route::post('customers',         [CustomerController::class, 'create']); // create customer


