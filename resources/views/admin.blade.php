<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <link rel="stylesheet" href="/css/main.css">
    </head>
    <body>

        <x-layout>
            
            <h1 style="color:black">ADMIN SCREEN</h1>

            <div class="container mx-auto p-4 " style="color: black">
                
          
                <p>Total users: {{$total}}</p>
                <p>Non-staff users: {{$totalEdited}} </p>
                <p>Average test score: {{$averageScore}}%</p> 
            
          
        
                

                
            </div>
              
        
        </body>
                </html>
        </x-layout>
