<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Test Charts</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="stylesheet" href="/css/main.css">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f9f9f9;
      margin: 0;
      padding: 20px 20px 0 20px;
      color: black;
    }

    .container {
      display: flex;
      flex-direction: row;
      max-width: 1200px;
      margin: auto;
      padding: 40px;
      background-color: #fff;
      border-radius: 15px;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }

    .report-content {
      flex: 2;
      padding-right: 40px;
    }

    .report-content h1 {
      font-size: 28px;
      font-weight: bold;
      margin-bottom: 20px;
    }

    .chart-container {
      width: 100%;
      max-width: 400px;
      margin-bottom: 20px;
    }

    .illustration {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .illustration img {
      max-width: 100%;
      max-height: 350px;
    }

    .results-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 30px;
    }

    .results-table th,
    .results-table td {
      border: 1px solid #ccc;
      padding: 8px;
    }

    .results-table th {
      background-color: #f0f0f0;
      font-weight: bold;
    }

    #footer2 {
      background-color: aliceblue;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: black;
      padding: 15px;
      text-align: center;
      bottom: 0;
      width: 100%;
      margin-top: 30px;
    }
  </style>
</head>
<body>
<x-layout2>
  <div class="container">
    <div class="report-content">
      <!--<h1>{{ auth()->user()->username }} – Last 3 Tests</h1>

      @foreach ($charts as $index => $chart)
        <div class="chart-container">
          <h4>Test #{{ $chart['testNumber'] }}</h4>
          <canvas id="chart{{ $index }}"></canvas>
        </div>

        <script>
          const ctx{{ $index }} = document.getElementById('chart{{ $index }}').getContext('2d');
          new Chart(ctx{{ $index }}, {
            type: 'pie',
            data: {
              labels: {!! json_encode($chart['labels']) !!},
              datasets: [{
                data: {!! json_encode($chart['values']) !!},
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40', '#E7E9ED', '#8DD1E1']
              }]
            },
            options: {
              responsive: true,
              maintainAspectRatio: true,
              aspectRatio: 1
            }
          });
        </script>
      @endforeach -->

      <a href="/admin/users" style="color: black">Back</a>

      @if (count($questions) > 0)
        <h4 style="margin-top: 2rem;">Detailed Answers for: <br>
          id: {{$quizuser->id}}<br>
          username: {{$quizuser->username}}<br>
          email: {{$quizuser->email}}
        
        </h4>
        <table class="results-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Question</th>
              <th>Given Answer</th>
              
            </tr>
          </thead>
          <tbody>
            @foreach ($questions as $item)
            <tr>
              <td>{{ $item['number'] }}</td>
              <td>{{ $item['question'] }}</td>
              <td>{{ $item['userAnswer'] }}</td>
              
            </tr>
            @endforeach
          </tbody>
        </table>
      @endif
    </div>

    <!-- <div class="illustration">
      <img src="/images/report-illustration.png" alt="Chart visual" />
    </div> -->
  </div>

  <footer id="footer2">
    <p>HELP Application. All rights reserved. {{ date('Y') }}</p>
  </footer>
</x-layout2>
</body>
</html>
