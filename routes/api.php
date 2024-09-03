<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\TodolistController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('register', [AuthController::class, 'register']);
Route::delete("delete/{id}", [AuthController::class, 'deleteUser']);
Route::post('login', [AuthController::class, 'login']);
Route::get('user/{id}', [AuthController::class, 'search'])->middleware('auth:sanctum');
Route::post('user/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');


Route::get('/', function() {

    return response()->json([
        'status' => false,
        'message' => "User not Authorized"
    ], 401);

})->name('login');


Route::get('v1/', [TodolistController::class, 'index'])->middleware('auth:sanctum');
Route::get('v1/{id}', [TodolistController::class, 'findList'])->middleware('auth:sanctum');
Route::post('v1/', [TodolistController::class, 'addList'])->middleware('auth:sanctum');
Route::delete('v1/{id}', [TodolistController::class, 'deleteList'])->middleware('auth:sanctum');
Route::put('v1/{id}', [TodolistController::class, 'Update'])->middleware('auth:sanctum');
