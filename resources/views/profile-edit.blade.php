<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <link rel="stylesheet" href="/css/main.css">
    </head>
    <body>

        <x-layout>
            
            <form method="POST" action="/account-edit/{{auth()->user()->id}}">
                @csrf
            <div class="container mx-auto p-4 " style="color: black">
                <h2 class="text-xl font-bold text-black mb-4">User Profile</h2>
                <p class="text-black"><strong>Username:</strong> {{ $profile->username }}</p>
                <p class="text-black"><strong>Date of Birth:</strong></p> <input type="date" id="DOB" name="DOB" value="{{$profile->DOB}}">
                <label style="padding-top: 20px; padding-bottom: 10px;" for="email"><strong>Email:</strong></label>
                    <input type="text" id="email" name="email" value="{{$profile->email}}">
            
                    <label style="padding-top: 20px; padding-bottom: 10px;" for="password"><strong>Password:</strong></label>
                    <input type="password" id="password" name="password" required>
            </div>
        
            <div style="display: flex;justify-content: center;align-items: center; height: 60px;">
            <button style="padding: 10px 20px;font-size: 16px;background-color: blue;color: white;border: none;cursor: pointer;" type="submit" class="">Submit</button>
        </div>
        
        </form>
        
        <div style="display: flex;justify-content: center;align-items: center; height: 60px;">
        <a href="/change-password/{{auth()->user()->id}}"><button style="padding: 10px 20px;font-size: 16px;background-color: blue;color: white;border: none;cursor: pointer;" type="submit" class="">Change Password</button></a>
        </div>
        
        <div style="display: flex;justify-content: center;align-items: center; height: 60px;">
        <a href="/delete-account/{{auth()->user()->id}}"><button style="padding: 10px 20px;font-size: 16px;background-color: red;color: white;border: none;cursor: pointer;" type="submit" class="">Delete Account</button></a>
        </div>
        </body>
                </html>
        </x-layout>
