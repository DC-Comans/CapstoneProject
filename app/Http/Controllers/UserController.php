<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function homepage(){
        return view("homepage");
    }

    public function signUp(){
        return view("sign-up");
    }

    public function account() {
        return view("profile");
    }
}
