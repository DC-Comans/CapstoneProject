<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function quizPage(){
        
        if(Auth::check()){
            return view("quiz-page");}

            else{return view("sign-up");}
        
        
    }



    public function quizStart($userId){
        
        if(Auth::check()){
            $questions = DB::table('quizzes')
            ->where('user_id', $userId)
            ->latest()
            ->first();

            if(!$questions){
                return view("edit-quiz");
            }
            
            
            return view("quiz");}

            else{return view("sign-up");}
        
        
    }



public function showChart($userId)
{


    $questions = DB::table('quizzes')
        ->where('user_id', $userId)
        ->latest()
        ->first();


        



 // Get the latest test
 $latestTest = DB::table('results')
        ->where('user_id', $userId)
        ->orderByDesc('TestNumber')
        ->first();

    $latestChart = null;


    $questionSet = collect(range(1, 20))->map(function ($i) use ($questions, $latestTest) {

        $question = $questions->{'question' . $i};
    $userAnswer = $latestTest->{'givenanswer' . $i} ?? null;
    $wasCorrect = $latestTest->{'answer' . $i} ?? null; // Should be "Yes" or "No"

    return [
        'number' => $i,
        'question' => $questions->{'question' . $i},
        'correct' => $questions->{'answer' . $i},
        'userAnswer' => $userAnswer,
        'isCorrect' => strtolower($wasCorrect) === 'yes',
    ];
})->filter(fn($q) => $q['question'] !== null);;



    if ($latestTest) {
        $answers = collect([
            $latestTest->answer1, $latestTest->answer2, $latestTest->answer3, $latestTest->answer4, $latestTest->answer5,
            $latestTest->answer6, $latestTest->answer7, $latestTest->answer8, $latestTest->answer9, $latestTest->answer10,
            $latestTest->answer11, $latestTest->answer12, $latestTest->answer13, $latestTest->answer14, $latestTest->answer15,
            $latestTest->answer16, $latestTest->answer17, $latestTest->answer18, $latestTest->answer19, $latestTest->answer20
        ])->filter();

        $latestChart = [
            'testNumber' => $latestTest->TestNumber,
            'labels' => $answers->countBy()->keys(),
            'values' => $answers->countBy()->values(),
        ];
    }


    $tests = DB::table('results')
        ->where('user_id', $userId)
        ->orderBy('TestNumber')
        ->limit(3)
        ->get();

        $charts = $tests->map(function ($result) {
        $answers = collect([
            $result->answer1, $result->answer2, $result->answer3, $result->answer4, $result->answer5,
            $result->answer6, $result->answer7, $result->answer8, $result->answer9, $result->answer10,
            $result->answer11, $result->answer12, $result->answer13, $result->answer14, $result->answer15,
            $result->answer16, $result->answer17, $result->answer18, $result->answer19, $result->answer20
        ])->filter(); // Remove nulls

        


        
        return [
            'testNumber' => $result->TestNumber,
            'labels' => $answers->countBy()->keys(),
            'values' => $answers->countBy()->values(),
        ];
    });

    return view('chart', [
        'userId' => $userId,
        'charts' => $charts,
        'latestChart' => $latestChart,
        'questions' => $questionSet
    ]);



    
}







     
}

