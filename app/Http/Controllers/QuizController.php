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
        return view('sign-up');
    }

    if ($step == 0) {
        session(['score' => 0]);
        session()->forget('quiz_saved'); // Reset the save-flag at the start
    }

    $questions = DB::table('quizzes')
        ->where('user_id', $userId)
        ->get();

    if ($questions->isEmpty()) {
        return view("edit-quiz")->with('failure', 'Please add questions first.');
    }

    $total = $questions->count();

    // ✅ QUIZ FINISHED — save result here once
    if ($step >= $total) {
        $userId = Auth::id();
        $score = session('score', 0);
        $percentage = round(($score / $total) * 100, 2);

        

        $messages = [];

        if ($percentage >= 80) {
            $messages = [
                "You're a rockstar!",
                "Amazing work — you're on fire!",
                "Top marks!",
                "You crushed it!",
                "Master of knowledge!"
            ];
        } elseif ($percentage >= 60) {
            $messages = [
                "Nice effort!",
                "You're getting there!",
                "Solid performance!",
                "Just a bit more polish"
            ];
        } elseif ($percentage >= 35) {
            $messages = [
                "Not bad! Keep studying",
                "Progress is progress ",
                "You're learning — don't stop!",
                "Every step counts!"
            ];
        } else {
            $messages = [
                "It's okay to start slow!",
                "Everyone starts somewhere!",
                "Don't give up — try again!",
                "Failure is the first step to success!"
            ];
        }

        // Pick one random message
        $finalMessage = $messages[array_rand($messages)];

        if (!session()->has('quiz_saved')) {
            // Determine next test number
            $latestTest = DB::table('results')
                ->where('user_id', $userId)
                ->orderByDesc('TestNumber')
                ->first();

            $nextTestNumber = $latestTest ? $latestTest->TestNumber + 1 : 1;

            // Keep only latest 2 so we can add a 3rd
            $existingResults = Result::where('user_id', $userId)
                ->orderByDesc('created_at')
                ->get();

            if ($existingResults->count() >= 3) {
                $existingResults->last()->delete(); // delete oldest
            }

            // Save the result once
            $result = Result::create([
                'user_id' => $userId,
                'TestNumber' => $nextTestNumber,
                'result' => $percentage
            ]);

            // Save all answers from session
            foreach (session('answers', []) as $a) {
                Answer::create([
                    'user_id' => $userId,
                    'result_id' => $result->id,
                    'question' => $a['question'],
                    'correct_answer' => $a['correct_answer'],
                    'given_answer' => $a['given_answer'],
                    'is_correct' => $a['is_correct']
                ]);
            }

            session(['quiz_saved' => true]);
            session()->forget('answers'); // clear session answers
        }

        session()->forget('score'); // optional: clear score after use

        return view('quiz-complete', [
            'total' => $total,
            'score' => $score,
            'percentage' => $percentage,
            'message' => $finalMessage
        ]);
    } // 

    // Ongoing question logic
    $currentQuestion = $questions[$step];

    $otherAnswers = $questions
        ->where('id', '!=', $currentQuestion->id)
        ->pluck('answer')
        ->shuffle()
        ->take(3)
        ->toArray();

    $defaultDistractors = ['Grieving', 'Being polite', 'Assurance', 'Cremation'];

    while (count($otherAnswers) < 3) {
        $filler = collect($defaultDistractors)
            ->diff($otherAnswers)
            ->diff([$currentQuestion->answer])
            ->random();

        $otherAnswers[] = $filler;
    }

    $options = collect($otherAnswers)
        ->push($currentQuestion->answer)
        ->shuffle();

    return view('quiz', [
        'question' => $currentQuestion->question,
        'correct' => $currentQuestion->answer,
        'options' => $options,
        'step' => $step,
        'total' => $total
    ]);
}




    public function submitAnswer(Request $request)
{
    $selected = $request->input('selected');
    $correct = $request->input('correct');
    $step = (int) $request->input('step');

    $isCorrect = $selected === $correct;

    $user = Auth::user()->id;

    // Store score in session or DB (this example uses session)
    $score = session('score', 0);
    session(['score' => $isCorrect ? $score + 1 : $score]);


    // Get current question
    $userId = Auth::id();
    $question = DB::table('quizzes')
        ->where('user_id', $userId)
        ->skip($step)
        ->take(1)
        ->first();

    // Store answer in session
    $answers = session('answers', []);
    $answers[] = [
        'question' => $question->question ?? 'Unknown',
        'correct_answer' => $correct,
        'given_answer' => $selected,
        'is_correct' => $isCorrect,
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

    // 🟢 Get the latest test
    $latestTest = $rawTests->first();

    // 🟢 Fetch question details from the `answers` table
    $questions = [];

    if ($latestTest) {
        $answers = Answer::where('result_id', $latestTest->id)
            ->get();

        $questions = $answers->map(function ($a, $i) {
            return [
                'number' => $i + 1,
                'question' => $a->question,
                'userAnswer' => $a->given_answer,
                'correct' => $a->correct_answer,
                'isCorrect' => $a->is_correct,
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

