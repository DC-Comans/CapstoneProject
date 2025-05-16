<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Quiz;
use App\Models\Result;
use App\Models\Answer;

class QuizController extends Controller
{
    public function quizPage(){
        
        if(Auth::check()){
            return view("quiz-page");}

            else{return view("sign-up");}
    }



    

    public function quizStart($userId, $step = 0)
{
    if (!Auth::check()) {
        return redirect('/');
    }

    if (Auth::id() != $userId) {
        return redirect()->back();
    }

    $questions = DB::table('quizzes')->get();

    if ($questions->isEmpty()) {
        return redirect("quiz")->with('failure', 'Questions are unavailable.');
    }

    // Reset quiz state at the beginning
    if ($step == 0) {
        session(['score' => 0]);
        session()->forget('quiz_saved');
        session()->forget('answers');
    }

    
   $allQuestions = DB::table('quizzes')->get()->values();
   // Build a unified sequence that includes titles
$displayFlow = collect();
$stepMap = [];  // Map step => index in full list
$titlesByIndex = [];

$stepCounter = 0;
foreach ($allQuestions as $index => $q) {
    if ($q->category === 't') {
        // Assign title to appear before the next question
        $titlesByIndex[$index + 1] = $q->question;
    } else {
        $stepMap[$stepCounter] = $index; // step -> index in allQuestions
        $stepCounter++;
    }
}

$total = count($stepMap);
   
   
   // Build section title mapping to steps
    $sectionTitles = collect();
    $stepIndex = 0;
    foreach ($allQuestions as $q) {
        if ($q->category === 't') {
            $sectionTitles->push((object)[
                'step' => $stepIndex,
                'question' => $q->question
            ]);
        } elseif ($q->category !== 't') {
            $stepIndex++;
        }
    }

    // ✅ QUIZ COMPLETED
    if ($step >= $total) {
        $score = session('score', 0);
        $percentage = round(($score / $total) * 100, 2);

        // Motivational message logic
        $messages = match (true) {
            $percentage >= 80 => [
                "You're a rockstar!",
                "Amazing work — you're on fire!",
                "Top marks!",
                "You crushed it!",
                "Master of knowledge!"
            ],
            $percentage >= 60 => [
                "Nice effort!",
                "You're getting there!",
                "Solid performance!",
                "Just a bit more polish"
            ],
            $percentage >= 35 => [
                "Not bad! Keep studying",
                "Progress is progress",
                "You're learning — don't stop!",
                "Every step counts!"
            ],
            default => [
                "It's okay to start slow!",
                "Everyone starts somewhere!",
                "Don't give up — try again!",
                "Failure is the first step to success!"
            ],
        };
        $finalMessage = $messages[array_rand($messages)];

        // Get stored answers for rendering and possibly saving
        $sessionAnswers = session('answers', []);








        if (!session()->has('quiz_saved')) {
    // Determine next test number
    $latestTest = Result::where('user_id', $userId)
        ->orderByDesc('TestNumber')
        ->first();

    $nextTestNumber = $latestTest ? $latestTest->TestNumber + 1 : 1;

    // Limit to last 2 previous results (keep max 3 including this one)
    Result::where('user_id', $userId)
        ->orderByDesc('created_at')
        ->skip(2)
        ->take(PHP_INT_MAX)
        ->get()
        ->each
        ->delete();

    
        // Delete previous result and answers for this user
        $previousResult = Result::where('user_id', $userId)->latest()->first();
        if ($previousResult) {
            Answer::where('result_id', $previousResult->id)->delete();
            $previousResult->delete();
        }

        // Create fresh result
        $result = Result::create([
            'user_id' => $userId,
            'TestNumber' => $nextTestNumber,
            'result' => $percentage,
        ]);

    // Save each answer
    foreach ($sessionAnswers as $a) {
        Answer::create([
    'result_id' => $result->id,
    'user_id' => $userId,
    'quiz_id' => $a['quiz_id'] ?? null,
    'question' => $a['question'],
    'given_answer' => $a['given_answer'],
]);

    }

    session([
        'quiz_saved' => true
    ]);

    session()->forget('answers'); 
}
        

        // Convert answers to display format
        $questions = collect($sessionAnswers)->map(function ($a, $i) {
            return [
                'number' => $i + 1,
                'question' => $a['question'],
                'userAnswer' => $a['given_answer'],
            ];
        });

        session()->forget('score'); // Optional

        // Group answers by area and calculate average score per area
// Map quiz_id to area from DB
$quizAreas = DB::table('quizzes')->pluck('area', 'id');

// Group answers by area (via quiz_id)
$areaGroups = collect($sessionAnswers)
    ->filter(fn($a) => isset($a['quiz_id']) && isset($quizAreas[$a['quiz_id']]))
    ->groupBy(fn($a) => $quizAreas[$a['quiz_id']])
    ->map(function ($group, $area) {
        $numericScores = collect($group)->pluck('given_answer')
            ->map(fn($val) => is_numeric($val) ? floatval($val) : null)
            ->filter();

        return [
            'area' => $area,
            'average' => $numericScores->avg(),
        ];
    });


//Outputs
$outputDefinitions = [
    'Talking Support' => [
    'low' => 4.24,
        'high' => 6.68,
        'Low' => [
            'range' => 'Lower',
            'how you scored' => 'You scored lower than most people when it comes to talking about death and dying.',
            'meaning' => 'You might not feel very confident talking about death yet—and that’s completely okay.',
            'suggestion' => 'Try joining a group or workshop where people talk openly about end-of-life topics. It’s a great way to build comfort.'
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


        return view('quiz-complete', [
        'total' => $total,
        'score' => $score,
        'percentage' => $percentage,
        'message' => $finalMessage,
        'questions' => $questions,
        'areaSummaries' => $areaSummaries
    ]);
    }

    // Get current question by mapping current step
    $currentIndex = $stepMap[$step] ?? null;
$currentQuestion = $currentIndex !== null ? $allQuestions[$currentIndex] : null;


    // Determine options based on question category
if ($currentQuestion->category === 'dd' || $currentQuestion->category === 's4' || $currentQuestion->category === 's5' || $currentQuestion->category === 's7') {
    $options = collect(json_decode($currentQuestion->options ?? '[]'));
} elseif ($currentQuestion->category === 'yn') {
    $options = collect(['Yes', 'No']);
} else {
    $options = collect([]);
}





    return view('quiz', [
    'question' => $currentQuestion->question,
    'correct' => $currentQuestion->answer,
    'options' => $options,
    'step' => $step,
    'total' => $total,
    'category' => $currentQuestion->category,
    'sectionTitles' => $sectionTitles,
    'titlesByIndex' => $titlesByIndex,
    'currentIndex' => $currentIndex 
]);

}




    public function submitAnswer(Request $request)
{
    $selected = $request->input('selected');
    if ($selected === 'Other (please specify)') {
        $selected = $request->input('otherText');
    }

    $step = (int) $request->input('step');
    $user = Auth::id();

    // Rebuild stepMap to get correct question index
    $allQuestions = DB::table('quizzes')->get()->values();

    $stepMap = [];
    $stepCounter = 0;
    foreach ($allQuestions as $index => $q) {
        if ($q->category !== 't') {
            $stepMap[$stepCounter] = $index;
            $stepCounter++;
        }
    }

    $currentIndex = $stepMap[$step] ?? null;
    $question = $currentIndex !== null ? $allQuestions[$currentIndex] : null;

    if (!$question) {
        return redirect()->route('quiz.take', ['userId' => $user, 'step' => $step + 1]);
    }

    $correct = $question->answer;

    $isCorrect = $selected === $correct;

    // Update session score if applicable
    $score = session('score', 0);
    session(['score' => $isCorrect ? $score + 1 : $score]);

    // Save answer
    $answers = session('answers', []);
    $answers[] = [
        'question' => $question->question ?? 'Unknown',
        'given_answer' => $selected,
        'area' => $question->area ?? null,
        'quiz_id' => $question->id ?? null,
    ];
    session(['answers' => $answers]);

    return redirect()->route('quiz.take', ['userId' => $user, 'step' => $step + 1]);
}


    public function submitQuiz(Request $request)
{

    if (!Auth::check()) {
        return redirect('/');
    }


    if(!Auth::user()->isAdmin){
            return redirect('/');}


    $ids = $request->input('ids');
    $questions = $request->input('questions');
    $category = $request->input('category');
    $area = $request->input('area');
    $options = $request->input('options');
    //$answers = $request->input('answers');

    // Prevent error if user deletes all inputs
if (empty($questions)) {
    return redirect()->back()->with('failure', 'You must have at least one question.');
}

    for ($i = 0; $i < count($questions); $i++) {
    $data = [
        'question' => $questions[$i],
        'category' => $category[$i],
        'area' => $area[$i],
        'options' => $options[$i],
    ];


    if (!empty($ids[$i])) {
        // Update existing question
        Quiz::where('id', $ids[$i])->update($data);
    } else {
        // Insert new question
        Quiz::create($data);
    }
    }
    

    

    return redirect('/quiz')->with('success', 'Quiz updated successfully!');
}




    public function editQuiz($userId){
        
        if(Auth::check()){
            $questions = DB::table('quizzes')
            ->get();
            
            return view("edit-quiz", ['questions' => $questions]);
            
            
            
        }

            else
            {return view("sign-up");}
        
        
    }



    public function showAdminChart($userId)
{


    if (!Auth::check()) {
        return redirect('/');
    }

    if (Auth::id() != $userId && !Auth::user()->isAdmin) {
        return redirect("/");
    }

    if(!Auth::user()->isAdmin){
            return redirect('/');}


    //Get user username

    $user = DB::table('users')
    ->where('id', $userId)
    ->first();
    

    // Fetch recent test results
    $rawTests = DB::table('results')
        ->where('user_id', $userId)
        ->orderByDesc('TestNumber')
        ->take(3)
        ->get()
        ->values(); // reset index to 0,1,2

    $charts = $rawTests->map(function ($result, $index) {
        return [
            'testNumber' => $index + 1,
            'labels' => ['Score', 'Remaining'],
            'values' => [$result->result, 100 - $result->result]
        ];
    });

    
    $latestTest = $rawTests->first();

    
    $questions = [];

    if ($latestTest) {
        $answers = Answer::where('result_id', $latestTest->id)
            ->get();

        $questions = $answers->map(function ($a, $i) {
            return [
                'number' => $i + 1,
                'question' => $a->question,
                'userAnswer' => $a->given_answer,
                
            ];
        });
    }

    return view('admin-chart', [
        'userId' => $userId,
        'charts' => $charts,
        'latestChart' => $charts->first(),
        'questions' => $questions,
        'quizuser' => $user
    ]);
}



//Normal user's own chart
public function showChart($userId)
{
    if (!Auth::check()) {
        return redirect('/');
    }

    if (Auth::id() != $userId && !Auth::user()->isAdmin) {
        return redirect("/");
    }

    // Fetch latest result
    $latestTest = DB::table('results')
        ->where('user_id', $userId)
        ->orderByDesc('TestNumber')
        ->first();

    if (!$latestTest) {
        return view('chart', [
            'userId' => $userId,
            'charts' => [],
            'latestChart' => null,
            'questions' => [],
            'areaSummaries' => [],
        ]);
    }

    $answers = Answer::where('result_id', $latestTest->id)->get();

    // Map quiz_id to area
    $quizAreas = DB::table('quizzes')->pluck('area', 'id');

    // Group answers by area
    $areaGroups = $answers->filter(fn($a) =>
        isset($a->quiz_id) && isset($quizAreas[$a->quiz_id])
    )->groupBy(fn($a) => $quizAreas[$a->quiz_id]);

    $areaGroups = $areaGroups->map(function ($group, $area) {
        $scores = collect($group)->pluck('given_answer')
            ->map(fn($val) => is_numeric($val) ? floatval($val) : null)
            ->filter();

        return [
            'area' => $area,
            'average' => $scores->avg(),
        ];
    });

    // Area definitions reused from quizStart (move to config/deathliteracy.php if preferred)
    $outputDefinitions = [
        'Talking Support' => [
    'low' => 4.24,
        'high' => 6.68,
        'Low' => [
            'range' => 'Lower',
            'how you scored' => 'You scored lower than most people when it comes to talking about death and dying.',
            'meaning' => 'You might not feel very confident talking about death yet—and that’s completely okay.',
            'suggestion' => 'Try joining a group or workshop where people talk openly about end-of-life topics. It’s a great way to build comfort.'
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

    // Map chart data
    $rawTests = DB::table('results')
        ->where('user_id', $userId)
        ->orderByDesc('TestNumber')
        ->take(3)
        ->get()
        ->values();

    $charts = $rawTests->map(function ($result, $index) {
        return [
            'testNumber' => $index + 1,
            'labels' => ['Score', 'Remaining'],
            'values' => [$result->result, 100 - $result->result]
        ];
    });

    $questions = $answers->map(function ($a, $i) {
        return [
            'number' => $i + 1,
            'question' => $a->question,
            'userAnswer' => $a->given_answer,
        ];
    });

    return view('chart', [
        'userId' => $userId,
        'charts' => $charts,
        'latestChart' => $charts->first(),
        'questions' => $questions,
        'areaSummaries' => $areaSummaries,
    ]);
}



public function adminUserList()
{



    if (!Auth::check()) {
        return redirect('/');
    }

    if (!Auth::user()->isAdmin) {
        return redirect("/");
    }
    $users = DB::table('users')
        ->select('id', 'username', 'email') // limit to needed fields
        ->orderBy('username')
        ->paginate(10); // paginate for clean layout

    return view('admin-user-list', ['users' => $users]);
}

     
}

