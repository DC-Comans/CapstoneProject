<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <link rel="stylesheet" href="/css/main.css">
    </head>
    <body>
    <div style="height: 100vh; background: linear-gradient(to bottom, #ccf5e7, #ffffff);">
        <x-layout>
            
            
            <div class="container mx-auto p-4 " style="color: black">
                <h2 class="text-xl font-bold text-black mb-4">User Profile</h2>
        
                <p class="text-black"><strong>Username:</strong> {{ $profile->username }}</p>
                <p class="text-black"><strong>Date of Birth:</strong> {{ $profile->DOB }}</p>
                <p class="text-black"><strong>Email:</strong> {{ $profile->email }}</p>

                <a href="/account-edit/{{auth()->user()->id}}"><button>Edit Account Information</button></a>
            </div>
              
        </div>
        </body>
                </html>
        </x-layout>
