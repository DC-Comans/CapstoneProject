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
  </style>
</head>
<body>
<x-layout2>
    <h1 style="text-align: center;">View User Results</h1>

    <table style="width: 80%; margin: auto; border-collapse: collapse; table-layout: fixed;">
        <thead>
            <tr style="background-color: #f1f1f1;">
                <th style="padding: 10px;">Username</th>
                <th style="padding: 10px;">Email</th>
                <th style="padding: 10px;">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr>
                <td style="padding: 10px; padding-left: 210px;">{{ $user->username }}</td>
                <td style="padding: 10px; padding-left: 210px;">{{ $user->email }}</td>
                <td style="padding: 10px; padding-left: 210px;">
                    <a href="{{ url('/quiz-chart-admin/' . $user->id) }}" style="color: blue;">View Report</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="text-align: center; margin-top: 20px;">
        {{ $users->links() }}
    </div>

    
    
</x-layout2>
<footer style="margin-top: 590px" id="footer2">
    <p>HELP Application. All rights reserved. {{ date('Y') }}</p>
  </footer>
</body>
</html>