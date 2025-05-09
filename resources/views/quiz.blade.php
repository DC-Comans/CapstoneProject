<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Quiz</title>
        <link rel="stylesheet" href="/css/main.css">
    </head>
    <body>

        <x-layout>

            

            <h4 style="color: black">Question {{ $step + 1 }} of {{ $total }}</h4>

            <h2 style="color: black">{{ $question }}</h2>

            <form method="POST" action="/submit-answer">
                @csrf
                @foreach ($options as $option)
                    <div>
                        <label style="color: black">
                            <input type="radio" name="selected" value="{{ $option }}" required>
                            {{ $option }}
                        </label>
                    </div>
                @endforeach
            
                <input type="hidden" name="correct" value="{{ $correct }}">
                <input type="hidden" name="step" value="{{ $step }}">
            
                <button type="submit">Next</button>
            </form>
            
            

            
              
        
        </body>
                </html>
        </x-layout>
