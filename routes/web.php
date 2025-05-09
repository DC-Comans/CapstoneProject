<?php

use App\Http\Controllers\QuizController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [UserController::class, "homepage"]);

Route::get('/about', function () {
    return view('about');
});

Route::get('/sign-up', [UserController::class, "showSignUpForm"]);

Route::post('/sign-up', [UserController::class, "signUp"]);

Route::get('/login', [UserController::class, "login"]);

Route::post('/login', action: [UserController::class, "loginAccount"]);

Route::get('/logout', action: [UserController::class, "logout"]);


Route::get('/profile', [UserController::class, "account"]);

Route::get('/quiz', [QuizController::class, "quizPage"]);

Route::get('/take-quiz/{user_id}', [QuizController::class, "quizStart"]);


Route::get('/resources', function () {
    return view('resources');
});

Route::get('/contact', function () {
    return view('contact');
});

Route::get('/quiz-chart/{user_id}', [QuizController::class, "showChart"]);
