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

Route::get('/take-quiz/{userId}/{step?}', [QuizController::class, 'quizStart'])->name('quiz.take');


Route::post('/submit-answer', [QuizController::class, 'submitAnswer']);

Route::get('/edit-quiz/{user_id}', [QuizController::class, "editQuiz"]);

Route::post('/submit-quiz', [QuizController::class, 'submitQuiz']);


Route::get('/resources', function () {
    return view('resources');
});

Route::get('/contact', function () {
    return view('contact');
});

Route::get('/quiz-chart/{user_id}', [QuizController::class, "showChart"]);


