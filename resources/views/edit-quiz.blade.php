<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edit Quiz</title>
        <link rel="stylesheet" href="/css/main.css">
    </head>
    <body>

        <x-layout>
            <h1 style="color: red">EDIT QUIZ</h1>

            <form method="POST" action="/submit-quiz">
                @csrf
            
                <div id="quiz-fields">
                    @foreach ($questions ?? [] as $question)
                        <div class="qa-pair">
                            <input type="text" name="questions[]" value="{{ $question->question }}" placeholder="Question" required>
                            <input type="text" name="answers[]" value="{{ $question->answer }}" placeholder="Answer" required>
                            <button type="button" onclick="removeQuestion(this)">❌</button>
                        </div>
                    @endforeach
                </div>
            
                <button type="button" onclick="addQuestion()">➕ Add Question</button>
                <br><br>
                <button type="submit">Save Quiz</button>
            </form>
            

        <script>
            function addQuestion() {
                const container = document.getElementById('quiz-fields');
                const pair = document.createElement('div');
                pair.classList.add('qa-pair');
                pair.innerHTML = `
                    <input type="text" name="questions[]" placeholder="Enter question" required>
                    <input type="text" name="answers[]" placeholder="Enter answer" required>
                `;
                container.appendChild(pair);
            }

            function removeQuestion(button) {
        const pair = button.parentNode;
        pair.remove();
    }
        </script>

        <style>
            .qa-pair {
                margin-bottom: 10px;
            }
        </style>

        </x-layout>
        
