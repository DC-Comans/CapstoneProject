<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [UserController::class, "homepage"]);

Route::get('/about', function () {
    return view('about');
});