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
        <h2 style="color: black" class="text-2xl font-bold mb-6">{{ $question }}</h2>

        <!-- Options -->
        <form method="POST" action="/submit-answer">
          @csrf
          <input type="hidden" name="correct" value="{{ $correct }}">
          <input type="hidden" name="step" value="{{ $step }}">

          @php
            //$shapes = ['▲', '■', '●', '★'];
           // shuffle($shapes);
          @endphp

          @foreach ($options as $index => $option)
          <label class="option-block bg-{{ ['red-100', 'green-100', 'blue-100', 'purple-100'][$index % 4] }}
          text-black peer-checked:bg-{{ ['red-200', 'green-200', 'blue-200', 'purple-200'][$index % 4] }}
          peer-checked:translate-y-1 peer-checked:scale-[1.02] transition-all duration-200 ease-in-out">
              <input type="radio" name="selected" value="{{ $option }}" class="hidden peer" required>
              <span class="peer-checked:text-lg peer-checked:font-semibold transition-all duration-200">
                {{ $option }}
              </span>
            </label>
          @endforeach

          <div class="mt-6 flex justify-between">
            <button type="button" onclick="window.history.back()" class="bg-red-200 text-red-800 px-4 py-2 rounded">← Last</button>
            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">Next →</button>
          </div>
        </form>
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
