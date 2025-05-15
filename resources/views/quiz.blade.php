<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Quiz</title>
  <link rel="stylesheet" href="/css/main.css">
  <script src="https://cdn.tailwindcss.com"></script>
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
  </style>
</head>
<body class="min-h-screen flex flex-col bg-white">
  <x-layout>
    <div class="flex-grow flex items-center justify-center">
      <div class="w-full max-w-6xl flex flex-col md:flex-row items-center justify-between gap-10 p-6">

        <!-- Left Content -->
        <div class="w-full md:w-1/2 px-6">

          <!-- Progress Bar (only for real questions) -->
          @if ($category !== 't')
          <div class="mb-4">
            <p class="text-lg font-semibold" style="color: black">Question {{ $step + 1 }} of {{ $total }}</p>
            <div class="bg-gray-300 h-2 w-full rounded">
              <div class="bg-black h-2 rounded" style="width: {{ round((($step+1)/$total)*100) }}%"></div>
            </div>
          </div>
          @endif

          <!-- Inject Section Title if one exists for this step -->
          @if (isset($titlesByIndex[$currentIndex]))
            <div class="text-xl font-bold mb-4 text-center text-gray-800 bg-gray-100 p-4 rounded shadow">
              {{ $titlesByIndex[$currentIndex] }}
            </div>
          @endif

          <!-- Question Title (skip if category is title-only) -->
          @if ($category !== 't')
            <h2 style="color: black" class="text-2xl font-bold mb-6">{{ $question }}</h2>
          @endif

          <!-- Question Inputs -->
          @if (in_array($category, ['s4', 's5', 's7', 'yn', 'dd']))
          <form method="POST" action="/submit-answer">
            @csrf
            <input type="hidden" name="correct" value="{{ $correct }}">
            <input type="hidden" name="step" value="{{ $step }}">

            @if (in_array($category, ['s4', 's5', 's7']))
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
            <div style="color: black">
              <select name="selected" id="dropdown" required class="w-full border px-4 py-2 rounded" onchange="handleDropdownChange(this)">
                <option disabled selected value="">Choose one...</option>
                @foreach ($options as $option)
                  <option value="{{ $option }}">{{ $option }}</option>
                @endforeach
              </select>
              <div id="otherInputWrapper" class="mt-4 hidden">
                <label for="otherText" class="block text-sm font-medium text-gray-700 mb-1">Please specify:</label>
                <input type="text" id="otherText" name="otherText" class="w-full border px-4 py-2 rounded" placeholder="Enter your answer here">
              </div>
            </div>
            @endif

            <div class="mt-6 flex justify-between" style="margin-bottom: 50px">
              <button type="button" onclick="window.history.back()" class="bg-red-200 text-red-800 px-4 py-2 rounded">← Last</button>
              <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">Next →</button>
            </div>
          </form>
          @elseif ($category === 't')
          <!-- Title-only screen -->
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




        <!-- Right-side image -->

        @php
      $fourth = ceil($total / 4);
        @endphp

        @if ($step < $fourth)
        <div class="hidden md:block md:w-1/2">
          <img src="/images/quiz-illustration.png" alt="Quiz" class="w-full max-w-md mx-auto">
        </div>

        

        @elseif($step < $fourth * 2)
        <div class="hidden md:block md:w-1/2">
          <img src="/images/quiz-illustration_2.png" alt="Quiz" class="w-full max-w-md mx-auto">
        </div>

         @elseif($step < $fourth * 3)
        <div class="hidden md:block md:w-1/2">
          <img src="/images/quiz-illustration_3.png" alt="Quiz" class="w-full max-w-md mx-auto">
        </div>

        @else
        <div class="hidden md:block md:w-1/2">
          <img src="/images/quiz-illustration_4.png" alt="Quiz" class="w-full max-w-md mx-auto">
        </div>


        @endif

        
        <

      </div>
    </div>
  </x-layout>

  <script>
    function handleDropdownChange(select) {
      const otherInput = document.getElementById('otherInputWrapper');
      const otherText = document.getElementById('otherText');
      if (select.value === 'Other (please specify)') {
        otherInput.classList.remove('hidden');
        otherText.setAttribute('required', 'required');
      } else {
        otherInput.classList.add('hidden');
        otherText.removeAttribute('required');
        otherText.value = '';
      }
    }
  </script>
</body>
</html>

