<x-layout>
  <h1 style="color: red">EDIT QUIZ</h1>

  <form method="POST" action="/submit-quiz">
    @csrf

    <div id="quiz-fields">
      @foreach ($questions ?? [] as $question)
        <div class="qa-pair">
          <input type="text" name="questions[]" value="{{ $question->question }}" placeholder="Question" required>
          <input type="text" name="answers[]" value="{{ $question->answer }}" placeholder="Answer (optional)" required>
          <select name="categories[]" required>
            <option value="s" {{ $question->category == 's' ? 'selected' : '' }}>Scale</option>
            <option value="yn" {{ $question->category == 'yn' ? 'selected' : '' }}>Yes/No</option>
            <option value="dd" {{ $question->category == 'dd' ? 'selected' : '' }}>Dropdown</option>
          </select>
          <input type="text" name="options[]" value="{{ $question->options ?? '' }}" placeholder="Comma-separated options (e.g., Yes,No)">
          <button type="button" onclick="removeQuestion(this)">❌</button>
        </div>
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
        <input type="text" name="answers[]" placeholder="Answer (optional)" required>
        <select name="categories[]" required>
          <option value="s">Scale</option>
          <option value="yn">Yes/No</option>
          <option value="dd">Dropdown</option>
        </select>
        <input type="text" name="options[]" placeholder="Comma-separated options">
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
</x-layout>
