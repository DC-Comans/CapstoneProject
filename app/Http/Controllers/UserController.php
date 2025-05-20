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
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Carbon;

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
        ->select('profiles.username', 'profiles.DOB', 'profiles.avatar', 'users.email')
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
            'password' => ['required'],
            'profile_picture' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif', 'max:2048']

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


     // Handle profile picture upload
    if ($request->hasFile('profile_picture')) {
        $path = $request->file('profile_picture')->store('profile_pictures', 'public');

        DB::table('profiles')->where('user_id', $user_id)->update([
            'avatar' => $path,
            'DOB' => $incomingFields['DOB'],
            'updated_at' => now()
        ]);
    } else {
        DB::table('profiles')->where('user_id', $user_id)->update([
            'DOB' => $incomingFields['DOB'],
            'updated_at' => now()
        ]);
    }


        
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
    if (!Auth::check() || !Auth::user()->isAdmin) {
        return redirect('/');
    }

    $total = DB::table('users')->count();
    $totalEdited = DB::table('users')->where('isAdmin', 0)->count();
    
    
    $activeThreshold = Carbon::now()->subMinutes(15)->timestamp;

    $usersLoggedIn = DB::table('sessions')
        ->where('last_activity', '>=', $activeThreshold)
        ->distinct('user_id')
        ->count('user_id');
    
   // $usersLoggedIn = DB::table('sessions')->count();
    $averageScore = round(DB::table('results')->avg('result'), 2);

    // Get all answers with areas
    $allAnswers = DB::table('answers')
        ->join('quizzes', 'answers.quiz_id', '=', 'quizzes.id')
        ->select('answers.given_answer', 'quizzes.area')
        ->get();

    // Group answers by area and calculate average
    $areaGroups = $allAnswers
        ->filter(fn($a) => is_numeric($a->given_answer))
        ->groupBy('area')
        ->map(function ($group, $area) {
            $scores = collect($group)->pluck('given_answer')->map(fn($val) => floatval($val));
            return [
                'area' => $area,
                'average' => $scores->avg(),
            ];
        });


    //$averageUserScore = $scores->avg();
    // Area definitions reused from quizStart (move to config/deathliteracy.php if preferred)
    $outputDefinitions = [
        'Talking Support' => [
    'low' => 4.24,
        'high' => 6.68,
        'Low' => [
            'range' => 'Lower',
            'how you scored' => 'Most users scored lower than most people when it comes to talking about death and dying.',
            'meaning' => 'Users might not feel very confident talking about death',
            'suggestion' => 'Users should try joining a group or workshop where people talk openly about end-of-life topics.'
        ],
        'Similar' => [
            'range' => 'Similar',
            'how you scored' => 'User score is about the same as most people’s.',
            'meaning' => "Users are about as comfortable talking about death as most people.",
            'suggestion' => 'Users should keep having open conversations when the opportunity comes up—it helps create a more supportive space for everyone.'
        ],
        'High' => [
            'range' => 'Higher',
            'how you scored' => 'Users scored higher than most people in talking about death and dying.',
            'meaning' => 'Users seem really comfortable talking about end-of-life matters.',
            'suggestion' => 'Users should gently support others who may find these conversations difficult.'
        ]
    ],
    'Hands-on Support' => [
        'low' => 3.35,
        'high' => 5.89,
        'Low' => [
            'range' => 'Lower',
            'how you scored' => 'Users scored lower than others in hands-on care.',
            'meaning' => 'Users may not have had much experience helping someone physically at the end of life.',
            'suggestion' => 'Users could look into volunteering, or even just learn basic care skills.'
        ],
        'Similar' => [
            'range' => 'Similar',
            'how you scored' => 'User score is in line with the average when it comes to hands-on support.',
            'meaning' => 'Users have had some hands-on experience, about the same as most people.',
            'suggestion' => 'Users should share what they have learned.'
        ],
        'High' => [
            'range' => 'Higher',
            'how you scored' => 'Users scored higher than most people in hands-on support.',
            'meaning' => 'Users clearly have had experience helping someone directly.',
            'suggestion' => 'Users should consider mentoring others or getting involved in the community’s end-of-life care efforts.'
        ]
        ],
    'Community Support 1' => [
        'low' => 2.91,
        'high' => 5.39,
        'Low' => [
            'range' => 'Lower',
            'how you scored' => 'Users scored lower than others in feeling supported by their community.',
            'meaning' => 'Users might feel like their community doesn’t offer much support around death and dying.',
            'suggestion' => 'Users should think about ways they could connect with others.'
        ],
        'Similar' => [
            'range' => 'Similar',
            'how you scored' => 'User score is typical when it comes to perceived community support.',
            'meaning' => "User's experience with community support is similar to most people’s.",
            'suggestion' => 'Users should look for ways to strengthen those ties.'
        ],
        'High' => [
            'range' => 'Higher',
            'how you scored' => 'Users scored higher than most in community support.',
            'meaning' => 'Users feel like their community is pretty supportive.',
            'suggestion' => 'Users should keep that support going by getting involved and welcoming others in.'
        ]
    ],
    'Community Support 2' => [
        'low' => 3.88,
        'high' => 6.24,
        'Low' => [
            'range' => 'Lower',
            'how you scored' => 'Users scored lower in how engaged their community feels around end-of-life care.',
            'meaning' => 'It might feel like their community isn’t very involved in end-of-life care.',
            'suggestion' => 'Users could explore local initiatives or even start conversations that help get more people engaged.'
        ],
        'Similar' => [
            'range' => 'Similar',
            'how you scored' => 'Users community engagement score is similar to most people’s.',
            'meaning' => 'Users community’s involvement seems about average.',
            'suggestion' => 'Users should keep encouraging participation—it helps everyone feel less alone.'
        ],
        'High' => [
            'range' => 'Higher',
            'how you scored' => 'Users scored higher than others in community engagement.',
            'meaning' => 'Users feel their community really steps up when it comes to end-of-life care.',
            'suggestion' => 'Users can help others feel confident joining in too—your example can inspire more engagement.'
        ]
    ],
    'Experience' => [
        'low' => 4.75,
        'high' => 7.05,
        'Low' => [
            'range' => 'Lower',
            'how you scored' => 'Users scored lower than others in experience with death and dying.',
            'meaning' => 'Users may not have had many personal or professional experiences with death or dying.',
            'suggestion' => "Users should consider listening to others' stories, or gently reflect on your their feelings."
        ],
        'Similar' => [
            'range' => 'Similar',
            'how you scored' => 'Your experience score is about average.',
            'meaning' => 'Your experiences are about average—enough to give you a sense of what death and dying can be like.',
            'suggestion' => 'Stay open to learning from your own experiences and those around you. It builds wisdom.'
        ],
        'High' => [
            'range' => 'Higher',
            'how you scored' => 'You scored higher than others in experience.',
            'meaning' => 'You’ve had more exposure to death and dying than most. That gives you valuable perspective.',
            'suggestion' => 'You might find yourself naturally supporting others—your lived experience is a real asset.'
        ]
    ],
    'Knowledge' => [
        'low' => 2.5,
        'high' => 5.08,
        'Low' => [
            'range' => 'Lower',
            'how you scored' => 'Users scored lower than others in death-related knowledge.',
            'meaning' => 'Users might not feel very informed about end-of-life care or services right now.',
            'suggestion' => 'Users should try learning even just a bit more—like what options exist.'
        ],
        'Similar' => [
            'range' => 'Similar',
            'how you scored' => 'Users knowledge score is about the same as others’.',
            'meaning' => 'Users have a good, solid base of knowledge, enough to understand what’s going on.',
            'suggestion' => 'Users should asking questions and exploring.'
        ],
        'High' => [
            'range' => 'Higher',
            'how you scored' => 'You scored higher than most people in knowledge about end-of-life matters.',
            'meaning' => 'Users know quite a lot about death-related matters.',
            'suggestion' => "Users should consider helping others understand what they've learned."
        ]
    ],
    'Community (Overall)' => [
        'low' => 3.5,
        'high' => 5.70,
        'Low' => [
            'range' => 'Lower',
            'how you scored' => 'Users scored lower than others overall in community-related support.',
            'meaning' => 'Users might not feel very supported by their community around death-related matters.',
            'suggestion' => 'Users should see if there are community groups or events that can help build that support. Small actions make a big difference.'
        ],
        'Similar' => [
            'range' => 'Similar',
            'how you scored' => 'User overall community score is similar to others’.',
            'meaning' => "User's sense of community support is about the same as others’.",
            'suggestion' => 'Users should keep showing up and being part of the conversation.'
        ],
        'High' => [
            'range' => 'Higher',
            'how you scored' => 'Users scored higher than others in overall community support.',
            'meaning' => 'Users see their community as supportive and engaged around dying and grieving.',
            'suggestion' => 'Users should share what’s working with others.'
        ]
    ],
    'Practical Knowledge (Overall)' => [
        'low' => 3.94,
        'high' => 6.14,
        'Low' => [
            'range' => 'Lower',
            'how you scored' => 'Users scored lower than others in overall practical knowledge.',
            'meaning' => 'Users might not feel super confident supporting someone through dying yet.',
            'suggestion' => 'Users should start small, maybe offer practical help to someone or learn more about what’s involved in end-of-life care.'
        ],
        'Similar' => [
            'range' => 'Similar',
            'how you scored' => 'User score is similar to others’ in practical knowledge.',
            'meaning' => 'Users have got a solid base of experience, right in line with others.',
            'suggestion' => 'Users should keep building on what they know and offer support when needed.'
        ],
        'High' => [
            'range' => 'Higher',
            'how you scored' => 'Users scored higher than most in practical knowledge.',
            'meaning' => 'Users have strong practical knowledge. They know what to do and how to be there for someone.',
            'suggestion' => 'Users should think about sharing what they know.'
        ]
    ],
    'Death Literacy Index (Overall)' => [
        'low' => 3.86,
        'high' => 5.8,
        'Low' => [
            'range' => 'Lower',
            'how you scored' => 'Users scored lower than others on the overall Death Literacy Index.',
            'meaning' => 'Users may not feel very comfortable or knowledgeable about death yet.',
            'suggestion' => 'Users should start with one step—talk to someone, read something, or reflect on what death means to them.'
        ],
        'Similar' => [
            'range' => 'Similar',
            'how you scored' => 'User score is about average across all areas of death literacy.',
            'meaning' => 'Users are in a similar place to most people.',
            'suggestion' => 'Users should keep learning, talking, and staying open.'
        ],
        'High' => [
            'range' => 'Higher',
            'how you scored' => 'Users scored higher than most people on the Death Literacy Index.',
            'meaning' => 'Users seem to have a strong level of comfort, knowledge, and experience.',
            'suggestion' => 'Users should think about how they can support others on their journey.'
        ]
    ]
        ];

    $areaSummaries = [];
    foreach ($areaGroups as $area => $data) {
        if (!isset($outputDefinitions[$area])) continue;

        $def = $outputDefinitions[$area];
        $score = $data['average'] ?? 0;
        $bucket = 'Low';
        if ($score >= $def['high']) $bucket = 'High';
        elseif ($score >= $def['low']) $bucket = 'Similar';

        $areaSummaries[] = [
            'area' => $area,
            'average' => number_format($score, 2),
            'range' => $def[$bucket]['range'],
            'howYouScored' => $def[$bucket]['how you scored'],
            'meaning' => $def[$bucket]['meaning'],
            'suggestion' => $def[$bucket]['suggestion']
        ];
    }

    return view('admin', [
        'users' => $total, 
        'total' => $total, 
        'totalEdited' => $totalEdited,
        'usersLoggedIn' => $usersLoggedIn, 
        'averageScore' => $averageScore,
        'areaSummaries' => $areaSummaries
    ]);
}




public function export()
{
    if (!Auth::check() || !Auth::user()->isAdmin) {
        return redirect('/');
    }

    // Get the same area summaries used in the view
    // Get all answers with areas
    $allAnswers = DB::table('answers')
        ->join('quizzes', 'answers.quiz_id', '=', 'quizzes.id')
        ->select('answers.given_answer', 'quizzes.area')
        ->get();

    // Group answers by area and calculate average
    $areaGroups = $allAnswers
        ->filter(fn($a) => is_numeric($a->given_answer))
        ->groupBy('area')
        ->map(function ($group, $area) {
            $scores = collect($group)->pluck('given_answer')->map(fn($val) => floatval($val));
            return [
                'area' => $area,
                'average' => $scores->avg(),
            ];
        });

    $outputDefinitions = [
        'Talking Support' => [
    'low' => 4.24,
        'high' => 6.68,
        'Low' => [
            'range' => 'Lower',
            'how you scored' => 'Most users scored lower than most people when it comes to talking about death and dying.',
            'meaning' => 'Users might not feel very confident talking about death',
            'suggestion' => 'Users should try joining a group or workshop where people talk openly about end-of-life topics.'
        ],
        'Similar' => [
            'range' => 'Similar',
            'how you scored' => 'User score is about the same as most people’s.',
            'meaning' => "Users are about as comfortable talking about death as most people.",
            'suggestion' => 'Users should keep having open conversations when the opportunity comes up—it helps create a more supportive space for everyone.'
        ],
        'High' => [
            'range' => 'Higher',
            'how you scored' => 'Users scored higher than most people in talking about death and dying.',
            'meaning' => 'Users seem really comfortable talking about end-of-life matters.',
            'suggestion' => 'Users should gently support others who may find these conversations difficult.'
        ]
    ],
    'Hands-on Support' => [
        'low' => 3.35,
        'high' => 5.89,
        'Low' => [
            'range' => 'Lower',
            'how you scored' => 'Users scored lower than others in hands-on care.',
            'meaning' => 'Users may not have had much experience helping someone physically at the end of life.',
            'suggestion' => 'Users could look into volunteering, or even just learn basic care skills.'
        ],
        'Similar' => [
            'range' => 'Similar',
            'how you scored' => 'User score is in line with the average when it comes to hands-on support.',
            'meaning' => 'Users have had some hands-on experience, about the same as most people.',
            'suggestion' => 'Users should share what they have learned.'
        ],
        'High' => [
            'range' => 'Higher',
            'how you scored' => 'Users scored higher than most people in hands-on support.',
            'meaning' => 'Users clearly have had experience helping someone directly.',
            'suggestion' => 'Users should consider mentoring others or getting involved in the community’s end-of-life care efforts.'
        ]
        ],
    'Community Support 1' => [
        'low' => 2.91,
        'high' => 5.39,
        'Low' => [
            'range' => 'Lower',
            'how you scored' => 'Users scored lower than others in feeling supported by their community.',
            'meaning' => 'Users might feel like their community doesn’t offer much support around death and dying.',
            'suggestion' => 'Users should think about ways they could connect with others.'
        ],
        'Similar' => [
            'range' => 'Similar',
            'how you scored' => 'User score is typical when it comes to perceived community support.',
            'meaning' => "User's experience with community support is similar to most people’s.",
            'suggestion' => 'Users should look for ways to strengthen those ties.'
        ],
        'High' => [
            'range' => 'Higher',
            'how you scored' => 'Users scored higher than most in community support.',
            'meaning' => 'Users feel like their community is pretty supportive.',
            'suggestion' => 'Users should keep that support going by getting involved and welcoming others in.'
        ]
    ],
    'Community Support 2' => [
        'low' => 3.88,
        'high' => 6.24,
        'Low' => [
            'range' => 'Lower',
            'how you scored' => 'Users scored lower in how engaged their community feels around end-of-life care.',
            'meaning' => 'It might feel like their community isn’t very involved in end-of-life care.',
            'suggestion' => 'Users could explore local initiatives or even start conversations that help get more people engaged.'
        ],
        'Similar' => [
            'range' => 'Similar',
            'how you scored' => 'Users community engagement score is similar to most people’s.',
            'meaning' => 'Users community’s involvement seems about average.',
            'suggestion' => 'Users should keep encouraging participation—it helps everyone feel less alone.'
        ],
        'High' => [
            'range' => 'Higher',
            'how you scored' => 'Users scored higher than others in community engagement.',
            'meaning' => 'Users feel their community really steps up when it comes to end-of-life care.',
            'suggestion' => 'Users can help others feel confident joining in too—your example can inspire more engagement.'
        ]
    ],
    'Experience' => [
        'low' => 4.75,
        'high' => 7.05,
        'Low' => [
            'range' => 'Lower',
            'how you scored' => 'Users scored lower than others in experience with death and dying.',
            'meaning' => 'Users may not have had many personal or professional experiences with death or dying.',
            'suggestion' => "Users should consider listening to others' stories, or gently reflect on your their feelings."
        ],
        'Similar' => [
            'range' => 'Similar',
            'how you scored' => 'Your experience score is about average.',
            'meaning' => 'Your experiences are about average—enough to give you a sense of what death and dying can be like.',
            'suggestion' => 'Stay open to learning from your own experiences and those around you. It builds wisdom.'
        ],
        'High' => [
            'range' => 'Higher',
            'how you scored' => 'You scored higher than others in experience.',
            'meaning' => 'You’ve had more exposure to death and dying than most. That gives you valuable perspective.',
            'suggestion' => 'You might find yourself naturally supporting others—your lived experience is a real asset.'
        ]
    ],
    'Knowledge' => [
        'low' => 2.5,
        'high' => 5.08,
        'Low' => [
            'range' => 'Lower',
            'how you scored' => 'Users scored lower than others in death-related knowledge.',
            'meaning' => 'Users might not feel very informed about end-of-life care or services right now.',
            'suggestion' => 'Users should try learning even just a bit more—like what options exist.'
        ],
        'Similar' => [
            'range' => 'Similar',
            'how you scored' => 'Users knowledge score is about the same as others’.',
            'meaning' => 'Users have a good, solid base of knowledge, enough to understand what’s going on.',
            'suggestion' => 'Users should asking questions and exploring.'
        ],
        'High' => [
            'range' => 'Higher',
            'how you scored' => 'You scored higher than most people in knowledge about end-of-life matters.',
            'meaning' => 'Users know quite a lot about death-related matters.',
            'suggestion' => "Users should consider helping others understand what they've learned."
        ]
    ],
    'Community (Overall)' => [
        'low' => 3.5,
        'high' => 5.70,
        'Low' => [
            'range' => 'Lower',
            'how you scored' => 'Users scored lower than others overall in community-related support.',
            'meaning' => 'Users might not feel very supported by their community around death-related matters.',
            'suggestion' => 'Users should see if there are community groups or events that can help build that support. Small actions make a big difference.'
        ],
        'Similar' => [
            'range' => 'Similar',
            'how you scored' => 'User overall community score is similar to others’.',
            'meaning' => "User's sense of community support is about the same as others’.",
            'suggestion' => 'Users should keep showing up and being part of the conversation.'
        ],
        'High' => [
            'range' => 'Higher',
            'how you scored' => 'Users scored higher than others in overall community support.',
            'meaning' => 'Users see their community as supportive and engaged around dying and grieving.',
            'suggestion' => 'Users should share what’s working with others.'
        ]
    ],
    'Practical Knowledge (Overall)' => [
        'low' => 3.94,
        'high' => 6.14,
        'Low' => [
            'range' => 'Lower',
            'how you scored' => 'Users scored lower than others in overall practical knowledge.',
            'meaning' => 'Users might not feel super confident supporting someone through dying yet.',
            'suggestion' => 'Users should start small, maybe offer practical help to someone or learn more about what’s involved in end-of-life care.'
        ],
        'Similar' => [
            'range' => 'Similar',
            'how you scored' => 'User score is similar to others’ in practical knowledge.',
            'meaning' => 'Users have got a solid base of experience, right in line with others.',
            'suggestion' => 'Users should keep building on what they know and offer support when needed.'
        ],
        'High' => [
            'range' => 'Higher',
            'how you scored' => 'Users scored higher than most in practical knowledge.',
            'meaning' => 'Users have strong practical knowledge. They know what to do and how to be there for someone.',
            'suggestion' => 'Users should think about sharing what they know.'
        ]
    ],
    'Death Literacy Index (Overall)' => [
        'low' => 3.86,
        'high' => 5.8,
        'Low' => [
            'range' => 'Lower',
            'how you scored' => 'Users scored lower than others on the overall Death Literacy Index.',
            'meaning' => 'Users may not feel very comfortable or knowledgeable about death yet.',
            'suggestion' => 'Users should start with one step—talk to someone, read something, or reflect on what death means to them.'
        ],
        'Similar' => [
            'range' => 'Similar',
            'how you scored' => 'User score is about average across all areas of death literacy.',
            'meaning' => 'Users are in a similar place to most people.',
            'suggestion' => 'Users should keep learning, talking, and staying open.'
        ],
        'High' => [
            'range' => 'Higher',
            'how you scored' => 'Users scored higher than most people on the Death Literacy Index.',
            'meaning' => 'Users seem to have a strong level of comfort, knowledge, and experience.',
            'suggestion' => 'Users should think about how they can support others on their journey.'
        ]
    ]
        ];

    $areaSummaries = [];

    foreach ($areaGroups as $area => $data) {
        if (!isset($outputDefinitions[$area])) continue;

        $def = $outputDefinitions[$area];
        $score = $data['average'] ?? 0;
        $bucket = 'Low';
        if ($score >= $def['high']) $bucket = 'High';
        elseif ($score >= $def['low']) $bucket = 'Similar';

        $areaSummaries[] = [
            'Area' => $area,
            'Average Score' => number_format($score, 2),
            'Range' => $def[$bucket]['range'],
            'How You Scored' => $def[$bucket]['how you scored'],
            'Meaning' => $def[$bucket]['meaning'],
            'Suggestion' => $def[$bucket]['suggestion']
        ];
    }

    // Convert to CSV format
    $headers = [
        "Content-type" => "text/csv",
        "Content-Disposition" => "attachment; filename=death_literacy_summary.csv",
        "Pragma" => "no-cache",
        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
        "Expires" => "0"
    ];

    $callback = function () use ($areaSummaries) {
        $handle = fopen('php://output', 'w');
        fputcsv($handle, array_keys($areaSummaries[0]));
        foreach ($areaSummaries as $row) {
            fputcsv($handle, $row);
        }
        fclose($handle);
    };

    return Response::stream($callback, 200, $headers);
}


}

    
    



