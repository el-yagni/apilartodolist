<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('success', function () {
    return view('success');
})->name('success');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');



