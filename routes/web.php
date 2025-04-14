<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [UserController::class, "homepage"]);

Route::get('/about', function () {
    return view('about');
});

Route::get('/sign-up', [UserController::class, "signUp"]);

Route::get('/profile', [UserController::class, "account"]);

Route::get('/quiz', function () {
    return view('quiz-page');
});

Route::get('/resources', function () {
    return view('resources');
});

Route::get('/contact', function () {
    return view('contact');
});