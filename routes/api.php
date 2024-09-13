<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\TodolistController;
use App\Http\Controllers\VideoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('email/verify/{id}/{hash}', [AuthController::class, 'verify'])->name('verification.verify');
Route::get('email/verify', [AuthController::class, 'notice'])->name('verification.notice');


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('register', [AuthController::class, 'register']);
Route::delete("delete/{id}", [AuthController::class, 'deleteUser'])->middleware(['auth:sanctum','verified']);
Route::post('login', [AuthController::class, 'login']);
Route::get('user/{id}', [AuthController::class, 'search'])->middleware(['auth:sanctum', 'verified']);
Route::post('user/logout', [AuthController::class, 'logout'])->middleware(['auth:sanctum', 'verified']);
Route::post('upload/{id}', [AuthController::class, 'uploadImage'])->middleware(['auth:sanctum', 'verified']);
Route::get('user/image/{id}', [AuthController::class, 'showUserImage'])->middleware(['auth:sanctum', 'verified']);

Route::get('/', function() {

    return response()->json([
        'status' => false,
        'message' => "User not Authorized"
    ], 401);

})->name('login');


Route::get('v1/', [TodolistController::class, 'index'])->middleware(['auth:sanctum', 'verified']);
Route::get('v1/{id}', [TodolistController::class, 'findList'])->middleware(['auth:sanctum', 'verified']);
Route::post('v1/', [TodolistController::class, 'addList'])->middleware(['auth:sanctum', 'verified']);
Route::delete('v1/{id}', [TodolistController::class, 'deleteList'])->middleware(['auth:sanctum', 'verified']);
Route::put('v1/{id}', [TodolistController::class, 'Update'])->middleware(['auth:sanctum', 'verified']);




Route::get('video', [VideoController::class, 'index'])->middleware(['auth:sanctum', 'verified']);
Route::post('video', [VideoController::class, 'store'])->middleware(['auth:sanctum', 'verified']);
