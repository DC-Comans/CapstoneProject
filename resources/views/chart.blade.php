<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Pie Chart</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="stylesheet" href="/css/main.css">
</head>
<body>
<x-layout>
    <h2 style="text-align: center; color: black;">User {{ $userId }} – Last 3 Tests</h2>
  
    <div class="chart-wrapper">
      @foreach ($charts as $index => $chart)
        <div class="chart-container">
          <h4 style="text-align: center; color: black;">Test #{{ $chart['testNumber'] }}</h4>
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
                backgroundColor: [
                  '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
                  '#9966FF', '#FF9F40', '#E7E9ED', '#8DD1E1'
                ]
              }]
            },
            options: {
              responsive: true,
              maintainAspectRatio: true,
              aspectRatio: 1
            }
          });
        </script>
      @endforeach

        


    </div>

    <div class="chart-wrapper-1">
        <div class="chart-container-1">
          <!--<h4 style="text-align: center; color: black;">Latest Test – Test #</h4> -->
          <canvas id="latestChartCanvas"></canvas>
        </div>
      </div>
      
      <script>
        window.addEventListener('DOMContentLoaded', () => {
          const ctx = document.getElementById('latestChartCanvas')?.getContext('2d');
          if (ctx) {
            new Chart(ctx, {
              type: 'pie',
              data: {
                labels: {!! json_encode($latestChart['labels']) !!},
                datasets: [{
                  data: {!! json_encode($latestChart['values']) !!},
                  backgroundColor: [
                    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
                    '#9966FF', '#FF9F40', '#E7E9ED', '#8DD1E1'
                  ]
                }]
              },
              options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 1
              }
            });
          }
        });
      </script>
      
      <h4 id="CorrectText" style="text-align: center; color: black;">What you got right:</h4>
</x-layout>
  </body>
  
</html>
