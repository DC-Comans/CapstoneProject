<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Test Charts</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="stylesheet" href="/css/main.css">
</head>
<body>


<x-layout2>
  <h1 style="color: red">EDIT QUIZ</h1>

  <form method="POST" action="/submit-quiz">
    @csrf

    <div id="quiz-fields">
      @foreach ($questions ?? [] as $question)
        <div class="qa-pair">
          <input type="text" name="questions[]" value="{{ $question->question }}" placeholder="Question" required>
          <!--<input type="text" name="answers[]" value="{{ $question->answer }}" placeholder="Answer (optional)" required> -->
          <select name="category[]" required>
            <option value="t" {{ $question->category == 't' ? 'selected' : '' }}>Title</option>
            <option value="s4" {{ $question->category == 's4' ? 'selected' : '' }}>Scale 4</option>
            <option value="s5" {{ $question->category == 's5' ? 'selected' : '' }}>Scale 5</option>
            <option value="s6" {{ $question->category == 's6' ? 'selected' : '' }}>Scale 6</option>
            <option value="s7" {{ $question->category == 's7' ? 'selected' : '' }}>Scale 7</option>
            <option value="s8" {{ $question->category == 's8' ? 'selected' : '' }}>Scale 8</option>
            <option value="yn" {{ $question->category == 'yn' ? 'selected' : '' }}>Yes/No</option>
            <option value="dd" {{ $question->category == 'dd' ? 'selected' : '' }}>Dropdown</option>
          </select>
          <select name="area[]">
            <option value="" {{ $question->area == '' ? 'selected' : '' }}></option>
            <option value="Community Support 1" {{ $question->area == 'Community Support 1' ? 'selected' : '' }}>Community Support 1</option>
            <option value="Community Support 2" {{ $question->area == 'Community Support 2' ? 'selected' : '' }}>Community Support 2</option>
            <option value="Community (Overall)" {{ $question->area == 'Community (Overall)' ? 'selected' : '' }}>Community (Overall)</option>
            <option value="Talking Support" {{ $question->area == 'Talking Support' ? 'selected' : '' }}>Talking Support</option>
            <option value="Hands-on Support" {{ $question->area == 'Hands-on Support' ? 'selected' : '' }}>Hands-on Support</option>
            <option value="Experience" {{ $question->area == 'Experience' ? 'selected' : '' }}>Experience</option>
            <option value="Knowledge" {{ $question->area == 'Knowledge' ? 'selected' : '' }}>Knowledge</option>
            <option value="Practical Knowledge (Overall)" {{ $question->area == 'Practical Knowledge (Overall)' ? 'selected' : '' }}>Practical Knowledge (Overall)</option>
          
          
          
          </select>
          <input size="50" type="text" name="options[]" value="{{ $question->options ?? '' }}" placeholder="">
          <button type="button" onclick="removeQuestion(this)">❌</button>
        </div>
        <input type="hidden" name="ids[]" value="{{ $question->id ?? '' }}">
      @endforeach
    </div>

    <button type="button" style="margin-left: 20px;" onclick="addQuestion()">➕ Add Question</button>
    <br><br>
    <button type="submit" style="margin-left: 20px; background-color: #DC2626; color: white; padding: 8px 16px; border-radius: 4px;">Save Quiz</button>
  </form>

  <script>
    function addQuestion() {
      const container = document.getElementById('quiz-fields');
      const pair = document.createElement('div');
      pair.classList.add('qa-pair');
      pair.innerHTML = `
        <input type="text" name="questions[]" placeholder="Enter question" required>
      
        <select name="category[]" required>
          <option  value="t" {{ $question->category == 't' ? 'selected' : '' }}>Title</option>
            <option value="s4" {{ $question->category == 's4' ? 'selected' : '' }}>Scale 4</option>
            <option value="s5" {{ $question->category == 's5' ? 'selected' : '' }}>Scale 5</option>
            <option value="s6" {{ $question->category == 's6' ? 'selected' : '' }}>Scale 6</option>
            <option value="s7" {{ $question->category == 's7' ? 'selected' : '' }}>Scale 7</option>
            <option value="s8" {{ $question->category == 's8' ? 'selected' : '' }}>Scale 8</option>
            <option value="yn" {{ $question->category == 'yn' ? 'selected' : '' }}>Yes/No</option>
            <option value="dd" {{ $question->category == 'dd' ? 'selected' : '' }}>Dropdown</option>
        </select>
        <select name="area[]">
            <option value="" {{ $question->area == '' ? 'selected' : '' }}></option>
            <option value="Community Support 1" {{ $question->area == 'Community Support 1' ? 'selected' : '' }}>Community Support 1</option>
            <option value="Community Support 2" {{ $question->area == 'Community Support 2' ? 'selected' : '' }}>Community Support 2</option>
            <option value="Community (Overall)" {{ $question->area == 'Community (Overall)' ? 'selected' : '' }}>Community (Overall)</option>
            <option value="Talking Support" {{ $question->area == 'Talking Support' ? 'selected' : '' }}>Talking Support</option>
            <option value="Hands-on Support" {{ $question->area == 'Hands-on Support' ? 'selected' : '' }}>Hands-on Support</option>
            <option value="Experience" {{ $question->area == 'Experience' ? 'selected' : '' }}>Experience</option>
            <option value="Knowledge" {{ $question->area == 'Knowledge' ? 'selected' : '' }}>Knowledge</option>
            <option value="Practical Knowledge (Overall)" {{ $question->area == 'Practical Knowledge (Overall)' ? 'selected' : '' }}>Practical Knowledge (Overall)</option>
          
          
          
          </select>
        <input size="50" type="text" name="options[]" placeholder="Comma-separated options">
        <button type="button" onclick="removeQuestion(this)">❌</button>
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
      padding-left: 20px;
    }

    .qa-pair input,
    .qa-pair select {
      margin-right: 10px;
    }
  </style>
</x-layout2>
<footer style="margin-top: 50px" id="footer2">
    <p>HELP Application. All rights reserved. {{ date('Y') }}</p>
  </footer>
</body>
</html>