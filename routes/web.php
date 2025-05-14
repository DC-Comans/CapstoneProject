<?php

use App\Http\Controllers\QuizController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

//Default
Route::get('/', [UserController::class, "homepage"]);

//About page
Route::get('/about', function () {
    return view('about');});

//Account
Route::get('/sign-up', [UserController::class, "showSignUpForm"]);
Route::post('/sign-up', [UserController::class, "signUp"]);
Route::get('/login', [UserController::class, "login"]);
Route::post('/login', action: [UserController::class, "loginAccount"]);
Route::get('/logout', action: [UserController::class, "logout"]);
Route::get('/account/{user_id}', [UserController::class, "account"]);
Route::get('/account-edit/{user_id}', [UserController::class, "accountEditScreen"]);
Route::post('/account-edit/{user_id}', [UserController::class, "SubmitAccountEdit"]);
Route::get('/change-password/{user_id}', [UserController::class, "changePasswordScreen"]);
Route::post('/change-password/{user_id}', [UserController::class, "changePassword"]);
Route::get('/delete-account/{user_id}', [UserController::class, "deleteAccountScreen"]);
Route::post('/delete-account/{user_id}', [UserController::class, "deleteAccount"]);
Route::get('/quiz-chart/{user_id}', [QuizController::class, "showChart"]);

//Admin
Route::get('/admin', [UserController::class, "adminScreen"]);
Route::get('/admin/users', [QuizController::class, 'adminUserList']);
Route::get('/edit-quiz/{user_id}', [QuizController::class, "editQuiz"]);
Route::post('/submit-quiz', [QuizController::class, 'submitQuiz']);
Route::get('/quiz-chart-admin/{user_id}', [QuizController::class, "showAdminChart"]);
Route::post('/admin/export', [UserController::class, 'export']);

//Quizzes
Route::get('/quiz', [QuizController::class, "quizPage"]);
Route::get('/take-quiz/{userId}/{step?}', [QuizController::class, 'quizStart'])->name('quiz.take');
Route::post('/submit-answer', [QuizController::class, 'submitAnswer']);



//Resources
Route::get('/resources', function () {
    return view('resources');});

//Contact
Route::get('/contact', function () {
    return view('contact');});




