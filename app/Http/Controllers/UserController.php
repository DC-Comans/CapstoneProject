<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Events\ExampleEvent;



class UserController extends Controller
{
    public function homepage(){
        
        
        if(Auth::check()){
            return view("homepage");        }
        
        else{
            return view("sign-up");
        }
        
        
        
    }


    public function showSignUpForm(){


        if(Auth::check()){
            return view("homepage");        }
        
        else{
            return view("sign-up");
        }
    }


    public function signUp(request $request){

        if(Auth::check()){
            return view("homepage");        }
        
        else{
            
        

        $incomingFields = $request->validate([
            'username' => ['required', "min:3", "max:20", Rule::unique('users', 'username')],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'min:8', 'confirmed', 'max:200']

            ]);

            $incomingFields['password'] = bcrypt($incomingFields['password']);

           /* if( !preg_match("#^[0-9a-z -]{6,12}$#", $incomingFields['username']) ){
                return redirect('/')->with('failure', 'Invalid Character/s');

            }
            */


            if( str_contains($incomingFields['username'], "*")){
                return redirect('/')->with('failure', 'Invalid Character/s');
            }
            if( str_contains($incomingFields['username'], "=")){
                return redirect('/')->with('failure', 'Invalid Character/s');
            }
            if( str_contains($incomingFields['username'], "'")){
                return redirect('/')->with('failure', 'Invalid Character/s');
            }
            if( str_contains($incomingFields['username'], '"')){
                return redirect('/')->with('failure', 'Invalid Character/s');
            }



           

            $user = User::create($incomingFields);
            
            
            Auth::login($user);

            $incomingFields['user_id'] = Auth::id();
            $incomingFields['username'] = Auth::user()->username;

            
            //$incomingField['private'] = 0;


          

            //$newProfile = profile::create($incomingField);


            return redirect('/')->with('success', 'You have registered successfully');


        //return view("sign-up");
    }
}


public function login(request $request){

if(Auth::check()){
    return view("homepage");        }

else{
    return view ("login");
}
}

public function loginAccount(request $request){
    $incomingFields = $request->validate([
            'loginusername' => 'required',
            'loginpassword' => 'required'
            ]);




            $pattern = '*';
            $pattern1 = '=';
            $pattern2 = 'DROP';
            $pattern3 = 'SELECT';

            $loginValue = $incomingFields['loginusername'];
            $loginField = filter_var($loginValue, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';


            //$incomingFields['loginusername'] =  str_replace($pattern, '', $incomingFields['loginusername']);
           // $incomingFields['loginusername'] =  str_replace($pattern1, '', $incomingFields['loginusername']);
           if (Auth::attempt([
        $loginField => $loginValue,
        'password' => $incomingFields['loginpassword']
    ])) {
        $request->session()->regenerate();
        event(new ExampleEvent(['username' => Auth::user()->username, 'action' => 'login']));
        return redirect('/')->with('success', 'You have logged in successfully');
    }

    return redirect('/login')->with('failure', 'Invalid login credentials');


}

public function logout(){
    if(!Auth::check()){
        return view("homepage");}
    else {
    
    
        event(new ExampleEvent(['username' => Auth::user()->username, 'action' => 'logout']));

        Auth::logout();
        //event(new ExampleEvent(['username' => auth()->user()->username, 'action' => 'logout']));
        return redirect('/')->with('success', 'You have logged out successfully');

}
}

    public function account() {
        return view("profile");
    }
    
}
