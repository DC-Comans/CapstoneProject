<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Events\ExampleEvent;
use Illuminate\Support\Facades\Hash;


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


          

            // Create a default profile
            Profile::create([
                'user_id' => $user->id,
                'username' => $user->username,
                'DOB' => '2000-01-01', // optional, or pass from form
                'avatar' => null,
                'private' => false
            ]);


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

    public function account($user_id) {
        if(!Auth::check()){
            return redirect('/');}


            if(Auth::id() != $user_id){
                return redirect('/');}
            

    else {
        $profile = DB::table('profiles')
        ->join('users', 'profiles.user_id', '=', 'users.id')
        ->where('profiles.user_id', $user_id)
        ->select('profiles.username', 'profiles.DOB', 'users.email')
        ->first(); // because you're expecting one profile per user


        
        return view("profile", ['profile' => $profile]);
    }
    }


    public function accountEditScreen($user_id) {
        if(!Auth::check()){
            return redirect('/');}


            if(Auth::id() != $user_id){
                return redirect('/');
            }

    else {
        $profile = DB::table('profiles')
        ->join('users', 'profiles.user_id', '=', 'users.id')
        ->where('profiles.user_id', $user_id)
        ->select('profiles.username', 'profiles.DOB', 'users.email')
        ->first(); // because you're expecting one profile per user


        
        return view("profile-edit", ['profile' => $profile]);
    }
    }



    

    public function SubmitAccountEdit($user_id,request $request) {
        if(!Auth::check()){
            return redirect('/');}


            if(Auth::id() != $user_id){
                return redirect('/');
            }

    else {



            $incomingFields = $request->validate([
            'DOB' => ['required', 'date'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user_id)],
            'password' => ['required']

            ]);

            $user = DB::table('users')->where('id', $user_id)->first();

            if (!Hash::check($incomingFields['password'], $user->password)) {
            return redirect()->back()->withErrors(['password' => 'Incorrect password. Changes not saved.']);}

        // Update `users` table (email only)
        DB::table('users')
        ->where('id', $user_id)
        ->update([
            'email' => $incomingFields['email'],
            'updated_at' => now()
        ]);

    // Update `profiles` table (DOB only)
    DB::table('profiles')
        ->where('user_id', $user_id)
        ->update([
            'DOB' => $incomingFields['DOB'],
            'updated_at' => now()
        ]);


        
        return redirect("/account/$user_id")->with('success', 'Profile updated successfully!');
    }
    }


    public function changePasswordScreen($user_id) {
        if(!Auth::check()){
            return redirect('/');}


            if(Auth::id() != $user_id){
                return redirect('/');
            }

    else {
        $profile = DB::table('profiles')
        ->join('users', 'profiles.user_id', '=', 'users.id')
        ->where('profiles.user_id', $user_id)
        ->select('profiles.username', 'profiles.DOB', 'users.email')
        ->first(); // because you're expecting one profile per user


        
        return view("password-change", ['profile' => $profile]); 
    }}



    public function changePassword($user_id,request $request) {
        if(!Auth::check()){
            return redirect('/');}


            if(Auth::id() != $user_id){
                return redirect('/');
            }

    else {

            $incomingFields = $request->validate([
            'password' => ['required'],
            'newpassword' => ['required', 'min:8', 'max:200'],
            'newpasswordcheck' => ['required', 'same:newpassword']

            ]);

            $user = DB::table('users')->where('id', $user_id)->first();

            if (!Hash::check($incomingFields['password'], $user->password)) {
            return redirect()->back()->withErrors(['password' => 'Incorrect password. Changes not saved.']);}

            if( $incomingFields['newpassword']!= $incomingFields['newpasswordcheck']){
                return redirect()->back()->withErrors(['password' => 'Passwords must be the same. Changes not saved.']);
            }


            $incomingFields['newpassword'] = bcrypt($incomingFields['newpassword']);

        // Update `users` table (email only)
        DB::table('users')
        ->where('id', $user_id)
        ->update([
            'password' => $incomingFields['newpassword'],
            'updated_at' => now()
        ]);

    
        
        return redirect("/account/$user_id")->with('success', 'Profile updated successfully!');
    }
    }


    public function deleteAccountScreen($user_id) {
        if(!Auth::check()){
            return redirect('/');}


            if(Auth::id() != $user_id){
                return redirect('/');
            }

    else {
        $profile = DB::table('profiles')
        ->join('users', 'profiles.user_id', '=', 'users.id')
        ->where('profiles.user_id', $user_id)
        ->select('profiles.username', 'profiles.DOB', 'users.email')
        ->first(); // because you're expecting one profile per user


        
        return view("delete-account", ['profile' => $profile]); 
    }}


    public function deleteAccount($user_id,request $request) {
        if(!Auth::check()){
            return redirect('/');}


            if(Auth::id() != $user_id){
                return redirect('/');
            }

    else {

        $request->validate([
        'password' => ['required']
         ]);

         $user = \App\Models\User::find($user_id);

            if (!$user || !Hash::check($request->password, $user->password)) {
        return redirect()->back()->withErrors(['password' => 'Incorrect password. Account was not deleted.']);}

            Auth::logout(); // log out first
            $user->delete(); // remove from DB
    
        
            return redirect('/')->with('success', 'Your account has been deleted.');
    }
    }



//ADMIN
public function adminScreen() {
        if(!Auth::check()){
            return redirect('/');}

        if(!Auth::user()->isAdmin){
            return redirect('/');}

        if(Auth::user()->isAdmin == 1){
            $users = DB::table('users')
            //->where('user_id', $userId)
            ->get();
            $total = $users->count();

            $totalEdited = DB::table('users')
            ->where('isAdmin', 0)
            ->get()->count();

            $averageScore = DB::table('results')->avg('result');

            $averageScore = round(DB::table('results')->avg('result'), 2);
            
        
        
        
            return view('admin', ['users' => $total, "total" => $total, "totalEdited" => $totalEdited, "averageScore" => $averageScore]);}

        else{
             return redirect('/');
        }
    
    }


}
