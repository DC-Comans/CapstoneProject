<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Quiz;
use App\Models\Result;

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
            Result::create([
                'user_id' => $userId,
                'TestNumber' => $nextTestNumber,
                'result' => $percentage
            ]);

            session(['quiz_saved' => true]); // prevent re-saving on refresh
        }

        session()->forget('score'); // optional: clear score after use

        return view('quiz-complete', [
            'total' => $total,
            'score' => $score,
            'percentage' => $percentage,
        ]);
    }

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


    if(Auth::id() != $userId){
        return redirect("/");
    }


    $rawTests = DB::table('results')
    ->where('user_id', $userId)
    ->orderByDesc('TestNumber')
    ->take(3)
    ->get()
    ->values(); // resets index to 0,1,2

    $charts = $rawTests->map(function ($result, $index) {
    return [
        'testNumber' => $index + 1, //
        'labels' => ['Score', 'Remaining'],
        'values' => [$result->result, 100 - $result->result]
    ];
        });

    return view('chart', [
        'userId' => $userId,
        'charts' => $charts,
        'latestChart' => $charts->first(),
        'questions' => [] // send an empty array for now
    ]);
}







     
}

