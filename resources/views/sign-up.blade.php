<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <link rel="stylesheet" href="/css/main.css">
    </head>
    <body>

        <x-layout>
            <div class="container">
                <div class="outer-box">
                <div class="login-box">
                  <form>
                    <label for="username"><strong>Username:</strong></label>
                    <input type="text" id="username" name="username">

                    <label for="email"><strong>Email:</strong></label>
                    <input type="text" id="email" name="email">
            
                    <label for="password"><strong>Password:</strong></label>
                    <input type="password" id="password" name="password">

                    <label for="passwordCheck"><strong>Re-type Password:</strong></label>
                    <input type="password" id="passwordCheck" name="passwordCheck">
            
                    <button type="submit" class="btn login-btn">Sign up</button>
                  </form>
                </div>
            
                <div class="signup-box">
                  <span>Already have an account? Just Login!</span>
                  <a href="/"><button class="btn signup-btn">Login</button></a>
                </div>
            </div>
              </div>
            
            
              
        
        </body>
                </html>
        </x-layout>


  