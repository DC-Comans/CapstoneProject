<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <link rel="stylesheet" href="/css/main.css">
    </head>
    <body>

        <x-layout>


                    @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('failure'))
            <div class="alert alert-danger">
                {{ session('failure') }}
            </div>
        @endif



            <div class="container">
                <div class="outer-box">
                <div class="login-box">
                  <form method="POST" action="/login">

                    @csrf

                    <label for="username"><strong>Username / Email:</strong></label>
                    <input type="text" id="loginusername" name="loginusername">

                    
            
                    <label for="password"><strong>Password:</strong></label>
                    <input type="password" id="loginpassword" name="loginpassword">

                    
            
                    <button type="submit" class="btn login-btn">Login</button>
                  </form>
                </div>

                <div class="signup-box">
                    <span>Don't have an account? Just sign-up!</span>
                    <a href="/sign-up"><button class="btn signup-btn">Sign Up</button></a>
                  </div>
            
                
            </div>
              </div>
            
            
              
        
        </body>
                </html>
        </x-layout>


  