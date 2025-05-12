<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Quiz</title>
  <link rel="stylesheet" href="/css/main.css">
  <script src="https://cdn.tailwindcss.com"></script> <!-- Optional: if using Tailwind -->
  <style>
    .option-block {
      display: flex;
      align-items: center;
      border-radius: 10px;
      padding: 15px;
      margin-bottom: 10px;
      cursor: pointer;
      transition: 0.2s;
    }
    .option-block:hover {
      opacity: 0.9;
    }
    .shape {
      font-size: 1.5rem;
      margin-right: 10px;
    }
  </style>
</head>
<body class="min-h-screen flex flex-col bg-white">
  <x-layout>
    <div class="flex-grow flex items-center justify-center">
        <div class="w-full max-w-6xl flex flex-col md:flex-row items-center justify-between gap-10 p-6">
  

      <!-- Left Content -->
      <div class="w-full md:w-1/2 px-6">
        <!-- Progress -->
        <div class="mb-4">
          <p class="text-lg font-semibold" style="color: black">Question {{ $step + 1 }} of {{ $total }}</p>
          <div class="bg-gray-300 h-2 w-full rounded">
            <div class="bg-black h-2 rounded" style="width: {{ round((($step+1)/$total)*100) }}%"></div>
          </div>
        </div>

        <!-- Question -->
        @if ($category !== 't')
        <h2 style="color: black" class="text-2xl font-bold mb-6">{{ $question }}</h2>
        @endif

        <!-- Options or Title-only Section -->
@if ($category === 's4' || $category === 's5' || $category === 'yn' || $category === 'dd')
  <!-- Questions with inputs -->
  <form method="POST" action="/submit-answer">
    @csrf
    <input type="hidden" name="correct" value="{{ $correct }}">
    <input type="hidden" name="step" value="{{ $step }}">

    @if ($category === 's4' || $category === 's5')
      <!-- Scale with labels -->
      <div class="flex flex-col gap-2">
        @foreach ($options as $index => $label)
          <label class="option-block bg-gray-100 text-black">
            <input type="radio" name="selected" value="{{ $index + 1 }}" class="hidden peer" required>
            <span class="peer-checked:text-lg peer-checked:font-semibold">
              {{ $index + 1 }}{{ $label ? ' – ' . $label : '' }}
            </span>
          </label>
        @endforeach
      </div>
    @elseif ($category === 'yn')
      <!-- Yes/No -->
      <div class="flex flex-col gap-2">
        @foreach (['Yes', 'No'] as $option)
          <label class="option-block bg-gray-100 text-black">
            <input type="radio" name="selected" value="{{ $option }}" class="hidden peer" required>
            <span class="peer-checked:text-lg peer-checked:font-semibold">
              {{ $option }}
            </span>
          </label>
        @endforeach
      </div>
    @elseif ($category === 'dd')
      <!-- Dropdown -->
      <div style="color: black">
        <select name="selected" required class="w-full border px-4 py-2 rounded">
          <option disabled selected value="">Choose one...</option>
          @foreach ($options as $option)
            <option value="{{ $option }}">{{ $option }}</option>
          @endforeach
        </select>
      </div>
    @endif

    <div class="mt-6 flex justify-between">
      <button type="button" onclick="window.history.back()" class="bg-red-200 text-red-800 px-4 py-2 rounded">← Last</button>
      <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">Next →</button>
    </div>
  </form>
@elseif ($category === 't')
  <!-- Title-only step (no form) -->
  <div style="color: black; font-weight: bold; font-size: 20px; text-align: center; margin-top: 40px;">
      {{ $question }}
  </div>
  <p style="text-align: center; margin-top: 20px; color: black;">Press next to continue.</p>

  <div class="mt-6 flex justify-between">
    <button type="button" onclick="window.history.back()" class="bg-red-200 text-red-800 px-4 py-2 rounded">← Last</button>
    <a href="{{ route('quiz.take', ['userId' => Auth::id(), 'step' => $step + 1]) }}" class="bg-red-600 text-white px-4 py-2 rounded">Next →</a>
  </div>
@endif


      </div>

      <!-- Image -->
      <div class="hidden md:block md:w-1/2">
        <img src="/images/quiz-illustration.png" alt="Quiz" class="w-full max-w-md mx-auto">
      </div>
      
    </div>
  </div>
  </x-layout>
</body>

</html>
