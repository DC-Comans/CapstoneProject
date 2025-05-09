<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Quiz</title>
        <link rel="stylesheet" href="/css/main.css">
    </head>
    <body>

        <x-layout>

            <a href="take-quiz/{{auth()->user()->id}}"><button class="btn login-btn" >Click here to take your latest quiz</button></a>

            <a href="edit-quiz/{{auth()->user()->id}}"><button class="btn login-btn">Or here to edit it</button></a>
            
            <h1 style="color: red">THIS IS THE QUIZ PAGE</h1>
            <a href="quiz-chart/{{auth()->user()->id}}"><h1 style="color: red">See results</h1></a>

            
              
        
        </body>
                </html>
        </x-layout>
