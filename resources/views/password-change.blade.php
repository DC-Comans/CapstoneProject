<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <link rel="stylesheet" href="/css/main.css">
    </head>
    <body>

        <x-layout>
            
            <form method="POST" action="/change-password/{{auth()->user()->id}}">
                @csrf
            <div class="container mx-auto p-4 " style="color: black">
                <h2 class="text-xl font-bold text-black mb-4">User Profile</h2>
                <p class="text-black"><strong>Username:</strong> {{ $profile->username }}</p>
                
                
            
                    <label style="padding-top: 20px; padding-bottom: 10px;" for="password"><strong>Old Password:</strong></label>
                    <input type="password" id="password" name="password" required>

                    <label style="padding-top: 20px; padding-bottom: 10px;" for="newpassword"><strong>New password:</strong></label>
                    <input type="password" id="newpassword" name="newpassword" required>

                    <label style="padding-top: 20px; padding-bottom: 10px;" for="newpasswordcheck"><strong>Re-type New Password:</strong></label>
                    <input type="password" id="newpasswordcheck" name="newpasswordcheck" required>
            </div>
        
            <div style="display: flex;justify-content: center;align-items: center; height: 60px;">
            <button style="padding: 10px 20px;font-size: 16px;background-color: blue;color: white;border: none;cursor: pointer;" type="submit" class="">Submit</button>
        </div>
        
        </form>

        <div style="display: flex;justify-content: center;align-items: center; height: 60px;">
            <a href="/account-edit/{{auth()->user()->id}}"><button style="padding: 10px 20px;font-size: 16px;background-color: blue;color: white;border: none;cursor: pointer;" type="submit" class="">Back</button></a>
        </div>
        
        
        
        
        </body>
                </html>
        </x-layout>
