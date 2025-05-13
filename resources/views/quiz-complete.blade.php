<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Complete</title>
    <link rel="stylesheet" href="/css/main.css">


    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
            color: black
        }

        .report-container {
            max-width: 900px;
            margin: auto;
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .report-title {
            font-size: 28px;
            font-weight: bold;
            color: #111;
            text-align: center;
            margin-bottom: 10px;
        }

        .report-intro {
            text-align: center;
            font-size: 16px;
            color: #444;
            margin-bottom: 30px;
        }

        .summary-list {
            list-style-type: disc;
            padding-left: 40px;
            color: #111;
        }

        .summary-list li {
            margin-bottom: 18px;
        }

        .summary-list strong {
            color: #222;
        }

        .suggestion {
            font-style: italic;
            color: #555;
            display: block;
            margin-top: 5px;
        }

        .recommendations {
            background-color: #f3e8ff;
            border: 1px solid #d0bdf4;
            padding: 15px;
            margin-top: 30px;
            font-size: 14px;
            color: #333;
            border-radius: 8px;
        }

        .download-button {
            display: inline-block;
            background-color: #e0e0e0;
            color: black;
            font-weight: bold;
            padding: 10px 20px;
            margin-top: 20px;
            text-decoration: none;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .download-button:hover {
            background-color: #d6d6d6;
        }

        .nav-links {
            text-align: center;
            margin-top: 40px;
        }

        .nav-links a {
            margin: 0 15px;
            color: #0066cc;
            text-decoration: underline;
        }

        .nav-links a:hover {
            color: #004999;
        }
        .responsive-image-container {
        display: none; /* hidden by default */
        }

        @media (min-width: 768px) {
        .responsive-image-container {
            display: block;
            width: 50%;
        }
        }

        .responsive-image {
        max-width: 100%;
        display: block;
        margin: 0 auto;
        max-width: 400px; /* equivalent to Tailwind's max-w-md */
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
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .report-content .intro {
            font-size: 18px;
            margin-bottom: 20px;
        }

        .bullets {
            margin-bottom: 20px;
            padding-left: 20px;
        }

        .bullets li {
            font-size: 16px;
            margin-bottom: 10px;
        }

        .download-btn {
            display: inline-block;
            background: #f5f5f5;
            border: 1px solid #ccc;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: bold;
            margin-bottom: 20px;
            text-decoration: none;
            color: #000;
        }

        .recommendations {
            background-color: #f0e9ff;
            padding: 15px;
            border-radius: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
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

    </style>
</head>
<body>

<x-layout>
    
    <div class="container">
    <div class="report-content">
        <h1>Your Death Literacy Report</h1>
        <p class="intro">You scored high on <strong>{{ $areaSummaries[0]['area'] ?? '...' }}</strong> but could improve on <strong>{{ $areaSummaries[1]['area'] ?? '...' }}</strong>.</p>

        <ul class="bullets">
            @foreach ($areaSummaries as $summary)
                <li>{{ $summary['howYouScored'] }}</li>
                <li>{{ $summary['meaning'] }}</li>
                <li>{{ $summary['suggestion'] }}</li>
                <p>___________________________</p>
            @endforeach
        </ul>

        <a href="#" onclick="window.print()" class="download-btn">Download Report ⬇</a>

        <div class="recommendations">
            <strong>Recommendations:</strong><br>
            • Read our guide on grief management<br>
            • Explore our funeral planning guide <a href="https://example.com">example.com</a>
        </div>
    </div>

    <div class="illustration">
        <img src="/images/report-illustration.png" alt="Clipboard report" />
    </div>
</div>

</x-layout>





    


</body>
</html>
