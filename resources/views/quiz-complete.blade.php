<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Quiz</title>
        <link rel="stylesheet" href="/css/main.css">
    </head>
    <body>

        <x-layout>

           
            
           
            <h2 style="color: black">Quiz Complete!</h2>
            <p style="color: green">Your score: {{ $score }} / {{ $total }} ({{ $percentage }}%)</p>

            <h2 class="text-2xl font-bold text-center mt-4 italic animate-pulse" style="color: #3ae637;">{{ $message }}</h2>
            <a href="/quiz-chart/{{auth()->user()->id}}"><p>To compare previous results</p></a>
            
            <a href="/">Return Home</a>

            
              
        
        </body>
                </html>
        </x-layout>
