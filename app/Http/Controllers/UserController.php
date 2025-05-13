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
    if (!Auth::check() || !Auth::user()->isAdmin) {
        return redirect('/');
    }

    $total = DB::table('users')->count();
    $totalEdited = DB::table('users')->where('isAdmin', 0)->count();
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
            'how you scored' => 'Your score is about the same as most people’s.',
            'meaning' => "You're about as comfortable talking about death as most people. That’s a solid place to be.",
            'suggestion' => 'Keep having open conversations when the opportunity comes up—it helps create a more supportive space for everyone.'
        ],
        'High' => [
            'range' => 'Higher',
            'how you scored' => 'You scored higher than most people in talking about death and dying.',
            'meaning' => 'You seem really comfortable talking about end-of-life matters, which is a real strength.',
            'suggestion' => 'Use that comfort to gently support others who may find these conversations difficult. You can help make these talks feel safer.'
        ]
    ],
    'Hands-on Support' => [
        'low' => 3.35,
        'high' => 5.89,
        'Low' => [
            'range' => 'Lower',
            'how you scored' => 'You scored lower than others in hands-on care.',
            'meaning' => 'You may not have had much experience helping someone physically at the end of life. That’s very common.',
            'suggestion' => 'You could look into volunteering, or even just learn basic care skills—it can help build confidence over time.'
        ],
        'Similar' => [
            'range' => 'Similar',
            'how you scored' => 'Your score is in line with the average when it comes to hands-on support.',
            'meaning' => 'You’ve had some hands-on experience, about the same as most people.',
            'suggestion' => 'Share what you’ve learned, and look for chances to build on your skills when you feel ready.'
        ],
        'High' => [
            'range' => 'Higher',
            'how you scored' => 'You scored higher than most people in hands-on support.',
            'meaning' => 'You’ve clearly had experience helping someone directly, and that’s really valuable.',
            'suggestion' => 'Consider mentoring others or getting involved in your community’s end-of-life care efforts. Your experience can make a difference.'
        ]
        ],
    'Community Support 1' => [
        'low' => 2.91,
        'high' => 5.39,
        'Low' => [
            'range' => 'Lower',
            'how you scored' => 'You scored lower than others in feeling supported by your community.',
            'meaning' => 'You might feel like your community doesn’t offer much support around death and dying.',
            'suggestion' => 'Think about ways you can connect with others—there may be more support out there than it seems.'
        ],
        'Similar' => [
            'range' => 'Similar',
            'how you scored' => 'Your score is typical when it comes to perceived community support.',
            'meaning' => 'Your experience with community support is similar to most people’s.',
            'suggestion' => 'Look for ways to strengthen those ties—community can be a big help during difficult times.'
        ],
        'High' => [
            'range' => 'Higher',
            'how you scored' => 'You scored higher than most in community support.',
            'meaning' => 'You feel like your community is pretty supportive, which is wonderful.',
            'suggestion' => 'Help keep that support going by getting involved and welcoming others in.'
        ]
    ],
    'Community Support 2' => [
        'low' => 3.88,
        'high' => 6.24,
        'Low' => [
            'range' => 'Lower',
            'how you scored' => 'You scored lower in how engaged your community feels around end-of-life care.',
            'meaning' => 'It might feel like your community isn’t very involved in end-of-life care.',
            'suggestion' => 'You could explore local initiatives or even start conversations that help get more people engaged.'
        ],
        'Similar' => [
            'range' => 'Similar',
            'how you scored' => 'Your community engagement score is similar to most people’s.',
            'meaning' => 'Your community’s involvement seems about average.',
            'suggestion' => 'Keep encouraging participation—it helps everyone feel less alone.'
        ],
        'High' => [
            'range' => 'Higher',
            'how you scored' => 'You scored higher than others in community engagement.',
            'meaning' => 'You feel your community really steps up when it comes to end-of-life care.',
            'suggestion' => 'You can help others feel confident joining in too—your example can inspire more engagement.'
        ]
    ],
    'Experience' => [
        'low' => 4.75,
        'high' => 7.05,
        'Low' => [
            'range' => 'Lower',
            'how you scored' => 'You scored lower than others in experience with death and dying.',
            'meaning' => 'You may not have had many personal or professional experiences with death or dying.',
            'suggestion' => "Consider listening to others' stories, or gently reflect on your own feelings. That’s a good starting point."
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
            'how you scored' => 'You scored lower than others in death-related knowledge.',
            'meaning' => 'You might not feel very informed about end-of-life care or services right now.',
            'suggestion' => 'Learning even just a bit more—like what options exist—can help you feel more prepared and confident.'
        ],
        'Similar' => [
            'range' => 'Similar',
            'how you scored' => 'Your knowledge score is about the same as others’.',
            'meaning' => 'You’ve got a good, solid base of knowledge—enough to understand what’s going on.',
            'suggestion' => 'Keep asking questions and exploring. It’ll help you and those you care about.'
        ],
        'High' => [
            'range' => 'Higher',
            'how you scored' => 'You scored higher than most people in knowledge about end-of-life matters.',
            'meaning' => 'You know quite a lot about death-related matters. That’s a powerful tool.',
            'suggestion' => 'Consider helping others understand what you’ve learned—many people are looking for someone who can guide them.'
        ]
    ],
    'Community (Overall)' => [
        'low' => 3.5,
        'high' => 5.70,
        'Low' => [
            'range' => 'Lower',
            'how you scored' => 'You scored lower than others overall in community-related support.',
            'meaning' => 'You might not feel very supported by your community around death-related matters.',
            'suggestion' => 'See if there are community groups or events that can help build that support. Small actions make a big difference.'
        ],
        'Similar' => [
            'range' => 'Similar',
            'how you scored' => 'Your overall community score is similar to others’.',
            'meaning' => 'Your sense of community support is about the same as others’.',
            'suggestion' => 'Keep showing up and being part of the conversation—it helps the whole community grow stronger.'
        ],
        'High' => [
            'range' => 'Higher',
            'how you scored' => 'You scored higher than others in overall community support.',
            'meaning' => 'You see your community as supportive and engaged around dying and grieving. That’s a real strength.',
            'suggestion' => 'Share what’s working—others might be looking for ideas to build similar support in their own communities.'
        ]
    ],
    'Practical Knowledge (Overall)' => [
        'low' => 3.94,
        'high' => 6.14,
        'Low' => [
            'range' => 'Lower',
            'how you scored' => 'You scored lower than others in overall practical knowledge.',
            'meaning' => 'You might not feel super confident supporting someone through dying yet, and that’s totally normal.',
            'suggestion' => 'Start small—maybe offer practical help to someone or learn more about what’s involved in end-of-life care.'
        ],
        'Similar' => [
            'range' => 'Similar',
            'how you scored' => 'Your score is similar to others’ in practical knowledge.',
            'meaning' => 'You’ve got a solid base of experience, right in line with others.',
            'suggestion' => 'Keep building on what you know, and don’t hesitate to step in when you see someone needs support.'
        ],
        'High' => [
            'range' => 'Higher',
            'how you scored' => 'You scored higher than most in practical knowledge.',
            'meaning' => 'You’ve got strong practical knowledge. You know what to do and how to be there for someone.',
            'suggestion' => 'Think about sharing what you know—others could really benefit from your experience.'
        ]
    ],
    'Death Literacy Index (Overall)' => [
        'low' => 3.86,
        'high' => 5.8,
        'Low' => [
            'range' => 'Lower',
            'how you scored' => 'You scored lower than others on the overall Death Literacy Index.',
            'meaning' => 'You may not feel very comfortable or knowledgeable about death yet, but that can change with time.',
            'suggestion' => 'Start with one step—talk to someone, read something, or reflect on what death means to you. It all adds up.'
        ],
        'Similar' => [
            'range' => 'Similar',
            'how you scored' => 'Your score is about average across all areas of death literacy.',
            'meaning' => 'You’re in a similar place to most people—there’s a good base, and room to grow.',
            'suggestion' => 'Keep learning, talking, and staying open—it’s a journey that grows with you.'
        ],
        'High' => [
            'range' => 'Higher',
            'how you scored' => 'You scored higher than most people on the Death Literacy Index.',
            'meaning' => 'You seem to have a strong level of comfort, knowledge, and experience. That’s a real gift.',
            'suggestion' => 'Think about how you can support others on their journey—you have a lot to offer.'
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
        'averageScore' => $averageScore,
        'areaSummaries' => $areaSummaries
    ]);
}



}

    
    



