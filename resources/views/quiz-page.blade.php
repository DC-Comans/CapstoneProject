<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Quiz</title>
        <link rel="stylesheet" href="/css/main.css">
    
    <style>
        body {
            background: linear-gradient(to bottom right, #f0fdf4, #e0f7fa);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .quiz-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 80px 20px 100px;
            text-align: center;
        }

        h1 {
            font-size: 2.4rem;
            color: #2f4f4f;
            margin-bottom: 20px;
        }

        .btn {
            background-color: #2e7d32; /* Darker green for visual clarity */
            color: white;
            font-size: 1.05rem;
            font-weight: 600;
            padding: 14px 28px;
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.12);
            cursor: pointer;
            transition: all 0.25s ease-in-out;
        }

        .btn:hover {
            background-color: #1b5e20; /* Slightly deeper green on hover */
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.15);
            transform: translateY(-1px);
        }

        .result-link {
        color: #1565c0;
        font-weight: bold;
        font-size: 1.1rem;
        margin-top: 30px;
        text-decoration:     none;
        border-bottom: 2px solid transparent;
        transition: all 0.3s ease;
    }

    .result-link:hover {
        color: #0d47a1;
        border-bottom: 2px solid #0d47a1;
    }

        .message {
            max-width: 500px;
            margin: 20px auto;
            padding: 12px;
            border-radius: 6px;
            font-size: 0.95rem;
        }


        .quiz-intro {
    max-width: 700px;
    text-align: center;
    margin-bottom: 30px;
        }
        .quiz-intro h1 {
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.05);
        }

        .admin-btn {
        background-color: #bbb;
        color: #333;
    }
    .admin-btn:hover {
        background-color: #999;
    }


        </style>
    
    </head>
    <body>

        <x-layout>
            <div class="quiz-wrapper">
            <img src="/images/flowers.png" alt="Decorative banner" style="max-width: 100%; border-radius: 12px; margin-bottom: -650px;">
            <div class="quiz-intro">
            <h1 style="color: black">Welcome to the healthy end of life planning quiz</h1>
            <p style="color: #555; margin-top: -5px; font-size: 18px;">
            We appreciate your time in reflecting on life, death, and what matters most.
            </p>
            </div>

            <a href="take-quiz/{{auth()->user()->id}}"><button class="btn login-btn" >Click here to take the HELP quiz</button></a>

            @if (auth()->user()->isAdmin == 1)
            
            
            <a href="edit-quiz/{{auth()->user()->id}}"><button class="btn admin-btn">Or here to edit it</button></a>
            

            @endif

            
            <a class="result-link" href="quiz-chart/{{auth()->user()->id}}">See your last results</a>

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
              
        </div>
        </body>
                </html>
        </x-layout>
