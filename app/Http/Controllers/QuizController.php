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

    
   $total = $questions->where('category', '!=', 't')->count();

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








        // Save results only once
        if (!session()->has('quiz_saved')) {
            // Determine next test number
            $latestTest = Result::where('user_id', $userId)
                ->orderByDesc('TestNumber')
                ->first();

            $nextTestNumber = $latestTest ? $latestTest->TestNumber + 1 : 1;

            // Delete oldest if 3 already exist
            $existingResults = Result::where('user_id', $userId)
                ->orderByDesc('created_at')
                ->get();

            if ($existingResults->count() >= 3) {
                $existingResults->last()->delete();
            }

            // Save the result
            $result = Result::create([
                'user_id' => $userId,
                'TestNumber' => $nextTestNumber,
                'result' => $percentage
            ]);

            // Save each answer
            foreach ($sessionAnswers as $a) {
                Answer::create([
            'user_id' => $userId,
            'result_id' => $result->id,
            'question' => $a['question'],
            'given_answer' => $a['given_answer'],
            'quiz_id' => $a['quiz_id'] ?? null
            ]);

            }

            session(['quiz_saved' => true]);
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


// Output mapping from hardcoded PHP array (originally from CSV)
$outputDefinitions = [
    'Talking Support' => [
    'low' => 4.24,
        'high' => 6.68,
        'Low' => [
            'range' => 'Lower',
            'meaning' => 'Your community connection may need strengthening.',
            'suggestion' => 'Consider joining local support or interest groups.'
        ],
        'Similar' => [
            'range' => 'Similar',
            'meaning' => 'Your experience is similar to others in your community.',
            'suggestion' => 'Stay engaged and continue contributing.'
        ],
        'High' => [
            'range' => 'Higher',
            'meaning' => "You're strongly connected with your community.",
            'suggestion' => 'Consider leading or mentoring in your area.'
        ]
    ],
    //'Hands-on Support' => [...],
    'Community Support 1' => [
        'low' => 2.91,
        'high' => 5.39,
        'Low' => [
            'range' => 'Lower',
            'meaning' => 'Your community connection may need strengthening.',
            'suggestion' => 'Consider joining local support or interest groups.'
        ],
        'Similar' => [
            'range' => 'Similar',
            'meaning' => 'Your experience is similar to others in your community.',
            'suggestion' => 'Stay engaged and continue contributing.'
        ],
        'High' => [
            'range' => 'Higher',
            'meaning' => 'You’re strongly connected with your community.',
            'suggestion' => 'Consider leading or mentoring in your area.'
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

    // ✅ ONGOING QUESTION
    $currentQuestion = $questions[$step];


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
    'category' => $currentQuestion->category 
]);

}




    public function submitAnswer(Request $request)
{
    $selected = $request->input('selected');
    if ($selected === 'Other (please specify)') {
    $selected = $request->input('otherText');
}
    $correct = $request->input('correct');
    $step = (int) $request->input('step');

    $isCorrect = $selected === $correct;

    $user = Auth::user()->id;

    // Store score in session or DB (this example uses session)
    $score = session('score', 0);
    session(['score' => $isCorrect ? $score + 1 : $score]);


    // Get current question
    //$userId = Auth::id();
    $question = DB::table('quizzes')
    ->skip($step)
    ->take(1)
    ->first();

    // Skip title-only questions
if ($question && $question->category === 't') {
    return redirect()->route('quiz.take', [
        'userId' => Auth::id(),
        'step' => $step + 1
    ]);
}

    // Store answer in session
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
    $questions = $request->input('questions');
    $answers = $request->input('answers');

    // Prevent error if user deletes all inputs
if (empty($questions) || empty($answers)) {
    return redirect()->back()->with('failure', 'You must have at least one question and answer.');
}

    // Remove old quiz entries for this user
    Quiz::where('user_id', Auth::id())->delete();

    // Insert new ones
    foreach ($questions as $index => $question) {
        $answer = $answers[$index];
        Quiz::create([
            'user_id' => Auth::id(),
            'question' => $question,
            'answer' => $answer,
        ]);
    }

    

    return redirect('/quiz')->with('success', 'Quiz updated successfully!');
}




    public function editQuiz($userId){
        
        if(Auth::check()){
            $questions = DB::table('quizzes')
            ->where('user_id', $userId)
            ->get();
            
            return view("edit-quiz", ['questions' => $questions]);
            
            
            
        }

            else
            {return view("sign-up");}
        
        
    }



    public function showChart($userId)
{
    if (Auth::id() != $userId) {
        return redirect("/");
    }

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

    return view('chart', [
        'userId' => $userId,
        'charts' => $charts,
        'latestChart' => $charts->first(),
        'questions' => $questions
    ]);
}







     
}

