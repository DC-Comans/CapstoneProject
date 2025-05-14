<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <link rel="stylesheet" href="/css/main.css">

        <style>
            .download-button {
            background-color: #3b82f6; /* Tailwind's blue-500 */
            color: black;
            padding: 8px 16px; /* py-2 px-4 */
            border: none;
            border-radius: 0.5rem; /* rounded */
            cursor: pointer;
            font-size: 16px;
            }

            .download-button:hover {
            background-color: #2563eb; /* darker on hover */
            }
        </style>

    </head>
    <body>

        <x-layout>
            
            <h1 style="color: black">Admin Dashboard</h1>
            <p style="color: black">Welcome back, {{ Auth::user()->username }}</p>

            <div class="container mx-auto p-4 " style="color: black">
                
          
                <p>Total users: {{$total}}</p>
                <p>Non-staff users: {{$totalEdited}} </p>
                <a href="/admin/users"><p>See individual user scores</p></a>
                <p>Average scores:</p> 
                @foreach ($areaSummaries as $summary)
                <p>{{ $summary['area'] }}: {{ $summary['average'] }}</p>
                @endforeach 
                <br>

                 @if (!empty($areaSummaries))
                <div class="report-content" style="margin-bottom: 40px;">
                    <h1>Average Death Literacy Report</h1>
                    <p class="intro">
                        Users scored high on <strong>{{ $areaSummaries[0]['area'] ?? '...' }}</strong>
                        but could improve on <strong>{{ $areaSummaries[1]['area'] ?? '...' }}</strong>.
                    </p>

                    <ul class="bullets">
                        @foreach ($areaSummaries as $summary)
                            <li>{{ $summary['howYouScored'] }}</li>
                            <li>{{ $summary['meaning'] }}</li>
                            <li><em>{{ $summary['suggestion'] }}</em></li>
                            <hr style="margin: 10px 0;">
                        @endforeach
                    </ul>
                </div>
            @endif

                
                

                
        
                

                <form method="POST" action="/admin/export">
    @csrf 
    <button type="submit" class="download-button">
        ⬇️ Download CSV
    </button>
</form>
            </div>

            
              
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
        
        </body>
                </html>
        </x-layout>
