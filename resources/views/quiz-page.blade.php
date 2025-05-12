<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Quiz</title>
        <link rel="stylesheet" href="/css/main.css">
    </head>
    <body>

        <x-layout>

            <a href="take-quiz/{{auth()->user()->id}}"><button class="btn login-btn" >Click here to take the HELP quiz</button></a>

            @if (auth()->user()->isAdmin == 1)
            
            
            <a href="edit-quiz/{{auth()->user()->id}}"><button class="btn login-btn">Or here to edit it</button></a>
            

            @endif

            <h1 style="color: red">THIS IS THE QUIZ PAGE</h1>
            <a href="quiz-chart/{{auth()->user()->id}}"><h1 style="color: red">See your results</h1></a>

            @if (session('failure'))
    <div style="color: red; background-color: #ffe5e5; padding: 10px; margin: 10px 0; border: 1px solid red;">
        {{ session('failure') }}
    </div>
@endif

@if (session('success'))
    <div style="color: green; background-color: #e5ffe5; padding: 10px; margin: 10px 0; border: 1px solid green;">
        {{ session('success') }}
    </div>
@endif
              
        
        </body>
                </html>
        </x-layout>
